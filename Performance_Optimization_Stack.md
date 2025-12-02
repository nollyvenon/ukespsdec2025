# Ukesps Performance Optimization Guide - Full Stack Approach

## Table of Contents
1. [Performance Overview](#performance-overview)
2. [Caching Layers](#cachinglayers)
3. [Redis Implementation](#redisimplementation)
4. [Queue Processing](#queueprocessing)
5. [Database Optimization](#databaseoptimization)
6. [Asset Optimization](#assetoptimization)
7. [CDN Integration](#cdnintegration)
8. [Monitoring & Profiling](#monitoringprofiling)
9. [Complete Configuration Guide](#completeconfigguide)

## Performance Overview

Ukesps will be optimized using multiple layers of caching, queue processing, and database optimization techniques:

- **Application Cache**: Laravel's cache system with Redis backend
- **Database Cache**: Query result caching, Eloquent relationship caching
- **HTTP Cache**: Response caching, ESI (Edge Side Includes)
- **Queue Processing**: Background job processing for heavy operations
- **OpCode Cache**: PHP OPcache configuration
- **Asset Optimization**: Minification, compression, and CDN delivery

## Caching Layers

### 1. Redis Installation & Configuration

#### Install Redis
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install redis-server

# CentOS/RHEL
sudo yum install epel-release
sudo yum install redis
sudo systemctl enable redis
sudo systemctl start redis

# Check if Redis is running
redis-cli ping
# Should return: PONG
```

#### Configure Redis Server
```bash
# Edit Redis configuration
sudo nano /etc/redis/redis.conf

# Important settings for performance:
bind 127.0.0.1
port 6379
timeout 300
tcp-keepalive 300
databases 16
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression yes
rdbchecksum yes
dbfilename dump.rdb
dir /var/lib/redis
maxmemory 256mb
maxmemory-policy allkeys-lru
appendonly no
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
lua-time-limit 5000
slowlog-log-slower-than 10000
slowlog-max-len 128
latency-monitor-threshold 0
notify-keyspace-events ""
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-size -2
list-compress-depth 0
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64
hll-sparse-max-bytes 3000
activerehashing yes
client-output-buffer-limit normal 0 0 0
client-output-buffer-limit replica 256mb 64mb 60
client-output-buffer-limit pubsub 32mb 8mb 60
hz 10
aof-rewrite-incremental-fsync yes
rdb-save-incremental-fsync yes
```

### 2. Cache Configuration in Laravel

Update your `.env` file with Redis settings:
```env
# Cache Configuration
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis Configuration
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLUSTER=redis
REDIS_PREFIX=ukesps:
```

Update `config/cache.php` for performance:
```php
<?php
return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],
    ],
    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'ukesps'), '_').'_cache_'),
];
?>
```

Update `config/database.php` Redis section:
```php
<?php
return [
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'ukesps'), '_').'_database_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];
?>
```

## Redis Implementation

### 1. Cache Configuration in AppServiceProvider

```php
<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register bindings with proper scoping
    }

    public function boot()
    {
        // Configure cache tags if using them
        if (! app()->runningInConsole()) {
            // Auto-cache configuration in production
            if ($this->app->environment('production')) {
                Artisan::call('config:cache');
                Artisan::call('route:cache');
                Artisan::call('view:cache');
            }
        }
    }
}
?>
```

### 2. Model Caching Implementation

Create a trait for automatic model caching:

```php
<?php
// app/Traits/Cacheable.php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    public function cacheKey($id = null)
    {
        return sprintf('%s-%s', strtolower(class_basename($this)), $id ?: $this->getKey());
    }

    public static function bootCacheable()
    {
        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    public function clearCache()
    {
        $keys = [
            $this->cacheKey(),
            sprintf('%s-all', strtolower(class_basename($this))),
        ];

        Cache::tags([$this->getTable()])->flush();
    }
}
?>
```

Apply to models like Course, Event, JobListing:
```php
<?php
// Example in Course model
namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Cacheable; // Add Cacheable trait
    
    // ... rest of the model
}
?>
```

### 3. Route Caching and Optimization

Create optimized controllers with caching:

```php
<?php
// Example optimized controller
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OptimizedCoursesController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'courses_index_' . serialize($request->all());
        
        return Cache::remember($cacheKey, 3600, function() use ($request) { // Cache for 1 hour
            $query = Course::where('course_status', 'published');
            
            // Apply filters
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
            }
            
            if ($request->filled('level')) {
                $query->where('level', $request->level);
            }
            
            return $query->paginate(10);
        });
    }

    public function show($slug)
    {
        $cacheKey = "course_show_{$slug}";
        
        return Cache::remember($cacheKey, 7200, function() use ($slug) { // Cache for 2 hours
            return Course::where('slug', $slug)
                         ->where('course_status', 'published')
                         ->firstOrFail();
        });
    }
}
?>
```

## Queue Processing

### 1. Queue Configuration

Update `.env` for queues:
```env
QUEUE_CONNECTION=redis
QUEUE_FAILED_DRIVER=database
```

### 2. Create High-Performance Queue Jobs

```php
<?php
// app/Jobs/ProcessHeavyCalculation.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessHeavyCalculation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout
    public $tries = 3;
    public $backoff = [10, 30, 60]; // Retry with increasing delays

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        // Perform heavy calculation
        foreach ($this->data as $item) {
            // Process data efficiently
            $this->processItem($item);
        }
    }

    private function processItem($item)
    {
        // Efficient processing logic here
    }
}
?>
```

### 3. Queue Worker Configuration

Create optimized queue worker:
```bash
# For production, use supervisor to manage queue workers
# Create supervisor config
sudo nano /etc/supervisor/conf.d/ukesps-worker.conf
```

```ini
[program:ukesps-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/vhosts/yourdomain.com/httpdocs/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/vhosts/yourdomain.com/logs/worker.log
stopwaitsecs=300
```

Start the queue workers:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ukesps-worker:*
```

## Database Optimization

### 1. MySQL Configuration for Performance

Update MySQL config (`/etc/mysql/mysql.conf.d/mysqld.cnf`):
```ini
[mysqld]
# Connection settings
max_connections = 200
connect_timeout = 5
wait_timeout = 600
interactive_timeout = 600
max_allowed_packet = 64M
net_read_timeout = 30
net_write_timeout = 60

# Query Cache (deprecated in MySQL 8.0+, but useful if using older version)
query_cache_type = 1
query_cache_size = 256M
query_cache_limit = 2M

# Buffer settings
tmp_table_size = 1G
max_heap_table_size = 1G
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_log_buffer_size = 16M
innodb_flush_log_at_trx_commit = 2
innodb_lock_wait_timeout = 50
innodb_thread_concurrency = 0

# Performance settings
key_buffer_size = 128M
bulk_insert_buffer_size = 64M
sort_buffer_size = 4M
read_buffer_size = 2M
read_rnd_buffer_size = 8M
myisam_sort_buffer_size = 128M
thread_cache_size = 8
table_open_cache = 400

# Log settings
log_error = /var/log/mysql/error.log
slow_query_log = 1
long_query_time = 2
slow_query_log_file = /var/log/mysql/slow.log
```

### 2. Eloquent Model Optimization

```php
<?php
// Optimized Eloquent model with eager loading
namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Cacheable;

    protected $fillable = [
        'title', 'description', 'duration', 'level', 'start_date', 'end_date',
        'course_status', 'max_enrollment', 'prerequisites', 'syllabus', 'instructor_id'
    ];

    protected $with = ['instructor']; // Eager load for better performance
    protected $withCount = ['enrollments']; // Count relationships efficiently

    public function scopePublished(Builder $query)
    {
        return $query->where('course_status', 'published');
    }

    public function scopePopular(Builder $query)
    {
        return $query->withCount('enrollments')
                    ->orderBy('enrollments_count', 'desc');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    // Optimize relationship queries
    public function scopeWithEfficientData(Builder $query)
    {
        return $query->select([
            'courses.*',
            'users.name as instructor_name'
        ])
        ->leftJoin('users', 'courses.instructor_id', '=', 'users.id');
    }
}
?>
```

### 3. Database Indexing Strategy

Create optimized migration for indexes:
```php
<?php
// Example migration for optimizing existing tables
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add composite indexes for common queries
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['course_status', 'level', 'start_date'], 'idx_course_status_level_start');
            $table->index(['instructor_id', 'course_status'], 'idx_instructor_status');
            $table->index(['level', 'duration'], 'idx_level_duration');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['event_status', 'start_date'], 'idx_event_status_date');
            $table->index(['event_type', 'category'], 'idx_event_type_category');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->index(['job_status', 'created_at'], 'idx_job_status_created');
            $table->index(['job_type', 'experience_level'], 'idx_job_type_exp');
        });

        // Add full-text indexes for search
        Schema::table('courses', function (Blueprint $table) {
            $table->fullText(['title', 'description'], 'ft_title_description');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->fullText(['title', 'description', 'location'], 'ft_event_search');
        });
    }

    public function down()
    {
        // Drop indexes
    }
};
?>
```

## Asset Optimization

### 1. OPcache Configuration

Update PHP INI settings (`/etc/php/8.x/apache2/php.ini` or `/etc/php/8.x/fpm/php.ini`):
```ini
; OPcache settings
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=12
opcache.max_accelerated_files=7963
opcache.validate_timestamps=0  ; Set to 0 in production for better performance
opcache.save_comments=1
opcache.fast_shutdown=1
opcache.revalidate_freq=0
opcache.file_cache=1
opcache.file_cache_only=0
opcache.max_file_size=0
opcache.consistency_checks=0
opcache.huge_code_pages=1  ; If supported
```

### 2. Laravel Mix & Asset Pipeline

Update `webpack.mix.js` for optimized builds:
```javascript
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .options({
       processCssUrls: false,
       uglify: {
           compress: {
               drop_console: true,  // Remove console logs in production
           }
       }
   })
   .version()  // Add versioning for cache busting
   .sourceMaps();  // Enable source maps

// Production optimizations
if (mix.inProduction()) {
    mix.version();
}
```

### 3. HTTP Caching Headers

Create middleware for HTTP caching:
```php
<?php
// app/Http/Middleware/ResponseCache.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ResponseCache
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $key = 'response.' . $request->getRequestUri();
            $response = Cache::get($key);

            if ($response) {
                return unserialize($response);
            }

            $response = $next($request);

            if ($response instanceof Response && $response->getStatusCode() === 200) {
                // Cache successful GET responses for 5 minutes
                Cache::put($key, serialize($response), now()->addMinutes(5));
            }

            return $response;
        }

        return $next($request);
    }
}
?>
```

Register the middleware in `app/Http/Kernel.php`:
```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\ResponseCache::class, // Add at the end
    ],
];
```

## CDN Integration

### 1. Asset Delivery via CDN

Update `.env` for CDN:
```env
ASSET_URL=https://your-cdn-url.com
```

Configure in `config/app.php`:
```php
'asset_url' => env('ASSET_URL', null),
```

### 2. Image Optimization

Install image optimization packages:
```bash
composer require intervention/image
npm install --save-dev imagemin imagemin-mozjpeg imagemin-pngquant
```

Create image optimization service:
```php
<?php
// app/Services/ImageOptimizer.php
namespace App\Services;

use Intervention\Image\Facades\Image;

class ImageOptimizer
{
    public static function optimizeAndSave($file, $path, $width = 1200, $quality = 80)
    {
        $image = Image::make($file);
        
        // Resize and optimize
        if ($image->width() > $width) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Save with optimized quality
        $optimized = $image->encode('jpg', $quality);
        
        // Store the optimized image
        return $optimized->save(storage_path('app/public/' . $path));
    }
}
?>
```

## Monitoring & Profiling

### 1. Performance Monitoring Setup

Add debugbar for development:
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 2. Create Performance Metrics Service

```php
<?php
// app/Services/PerformanceMetrics.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceMetrics
{
    public static function getQueryCount()
    {
        return count(DB::getQueryLog());
    }

    public static function getCacheHitRate()
    {
        // Custom logic to track cache hits/misses
        return Cache::get('cache_stats.hits', 0) / max(Cache::get('cache_stats.total', 1), 1);
    }

    public static function logRequestMetrics($startTime, $request, $response)
    {
        $executionTime = microtime(true) - $startTime;
        $queryCount = self::getQueryCount();
        
        // Log performance metrics
        \Log::info('Request Performance', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'execution_time' => $executionTime,
            'query_count' => $queryCount,
            'response_size' => strlen($response->getContent()),
        ]);
    }
}
?>
```

## Complete Configuration Guide

### 1. Production Environment Setup

Create a comprehensive deploy script:
```bash
#!/bin/bash
# deploy.sh - Production deployment script

echo "Starting Ukesps production deployment..."

# Pull latest code
git pull origin main

# Install/update PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Install/update Node.js dependencies
npm ci --production
npm run build

# Clear and cache configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations (safely)
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear all caches
php artisan cache:clear
php artisan queue:restart

# Optimize Composer autoloader
composer dump-autoload --optimize --classmap-authoritative

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Deployment completed successfully!"
echo "Starting queue workers..."
supervisorctl start ukesps-worker:*

echo "Application is now running with full performance stack!"
```

### 2. Server Configuration Summary

For maximum performance, ensure your server is configured with:

**Software Stack:**
- PHP 8.3+ with OPcache enabled
- Redis 6.0+ for caching and sessions
- MySQL 8.0+ or PostgreSQL 13+ with proper indexing
- Nginx/Apache with PHP-FPM
- Node.js 18+ for asset compilation
- Supervisor for queue workers

**Performance Settings:**
- Redis as primary cache and session driver
- MySQL with proper indexing and query optimization
- OPcache with validate_timestamps=0 in production
- Queue workers managed by Supervisor
- Asset versioning and CDN delivery
- HTTP response caching for GET requests

**Cron Jobs for Maintenance:**
```bash
# Add to crontab
* * * * * cd /var/www/your-app && php artisan schedule:run >> /dev/null 2>&1
0 2 * * * php artisan queue:restart  # Restart queues nightly
0 3 * * * php artisan cache:gc      # Garbage collect cache
```

This comprehensive performance optimization setup will significantly improve the speed and scalability of your Ukesps application, making it capable of handling high traffic loads efficiently.