<?php

namespace App\Services;

use App\Models\JobListing;
use Illuminate\Support\Facades\DB;

class SalaryCheckerService
{
    /**
     * Get salary data for a specific role and location
     */
    public function getSalaryData(string $jobTitle, string $location, string $experienceLevel = 'mid'): array
    {
        // Normalize the job title and location for search
        $normalizedTitle = strtolower(trim($jobTitle));
        $normalizedLocation = strtolower(trim($location));
        
        // Get similar job postings to calculate averages
        $similarJobs = JobListing::where('job_status', 'published')
            ->where(function($query) use ($normalizedTitle) {
                $query->where(DB::raw('LOWER(title)'), 'LIKE', "%{$normalizedTitle}%")
                      ->orWhere(DB::raw('LOWER(job_title)'), 'LIKE', "%{$normalizedTitle}%")
                      ->orWhere(DB::raw('LOWER(position)'), 'LIKE', "%{$normalizedTitle}%");
            })
            ->where(function($query) use ($normalizedLocation) {
                $query->where(DB::raw('LOWER(location)'), 'LIKE', "%{$normalizedLocation}%")
                      ->orWhere(DB::raw('LOWER(city)'), 'LIKE', "%{$normalizedLocation}%")
                      ->orWhere(DB::raw('LOWER(region)'), 'LIKE', "%{$normalizedLocation}%");
            })
            ->select('salary_min', 'salary_max', 'salary_type', 'experience_level', 'job_type')
            ->get();

        // Calculate average salary data
        $salaryData = [
            'job_title' => $jobTitle,
            'location' => $location,
            'experience_level' => $experienceLevel,
            'market_average' => $this->calculateMarketAverage($similarJobs),
            'salary_range' => $this->calculateSalaryRange($similarJobs),
            'percentiles' => $this->calculatePercentiles($similarJobs),
            'trend' => $this->calculateTrend($similarJobs),
            'comparisons' => [
                'junior' => $this->calculateForExperience($similarJobs, 'junior'),
                'mid' => $this->calculateForExperience($similarJobs, 'mid'),
                'senior' => $this->calculateForExperience($similarJobs, 'senior'),
                'management' => $this->calculateForExperience($similarJobs, 'management'),
            ],
            'industries' => $this->getIndustryComparison($jobTitle),
        ];

        return $salaryData;
    }

    /**
     * Calculate market average from job data
     */
    private function calculateMarketAverage($jobs): float
    {
        if ($jobs->isEmpty()) {
            return 0.0;
        }

        $total = 0;
        $count = 0;

        foreach ($jobs as $job) {
            if ($job->salary_min && $job->salary_max) {
                $total += ($job->salary_min + $job->salary_max) / 2;
                $count++;
            } elseif ($job->salary_min) {
                $total += $job->salary_min;
                $count++;
            } elseif ($job->salary_max) {
                $total += $job->salary_max;
                $count++;
            }
        }

        return $count > 0 ? $total / $count : 0;
    }

    /**
     * Calculate salary range (min, max)
     */
    private function calculateSalaryRange($jobs): array
    {
        if ($jobs->isEmpty()) {
            return ['min' => 0, 'max' => 0];
        }

        $mins = [];
        $maxs = [];

        foreach ($jobs as $job) {
            if ($job->salary_min) {
                $mins[] = $job->salary_min;
            }
            if ($job->salary_max) {
                $maxs[] = $job->salary_max;
            }
        }

        return [
            'min' => !empty($mins) ? min($mins) : 0,
            'max' => !empty($maxs) ? max($maxs) : 0,
        ];
    }

    /**
     * Calculate percentiles (25th, 50th, 75th)
     */
    private function calculatePercentiles($jobs): array
    {
        if ($jobs->isEmpty()) {
            return ['25th' => 0, '50th' => 0, '75th' => 0];
        }

        $salaries = [];
        foreach ($jobs as $job) {
            if ($job->salary_min && $job->salary_max) {
                $avg = ($job->salary_min + $job->salary_max) / 2;
                $salaries[] = $avg;
            } elseif ($job->salary_min) {
                $salaries[] = $job->salary_min;
            } elseif ($job->salary_max) {
                $salaries[] = $job->salary_max;
            }
        }

        if (empty($salaries)) {
            return ['25th' => 0, '50th' => 0, '75th' => 0];
        }

        sort($salaries);
        $count = count($salaries);

        return [
            '25th' => $this->getPercentileValue($salaries, 25),
            '50th' => $this->getPercentileValue($salaries, 50), // Median
            '75th' => $this->getPercentileValue($salaries, 75),
        ];
    }

    /**
     * Get percentile value from sorted array
     */
    private function getPercentileValue(array $values, int $percentile): float
    {
        if (empty($values)) {
            return 0;
        }

        $index = ($percentile / 100) * (count($values) - 1);
        $lower = floor($index);
        $upper = ceil($index);

        if ($lower == $upper) {
            return $values[$lower];
        }

        $weight = $index - $lower;
        return $values[$lower] * (1 - $weight) + $values[$upper] * $weight;
    }

    /**
     * Calculate trend based on recent job postings
     */
    private function calculateTrend($jobs): string
    {
        if ($jobs->isEmpty()) {
            return 'neutral';
        }

        // Calculate based on recency of postings and salary changes
        $recentJobs = $jobs->filter(function ($job) {
            return $job->created_at && $job->created_at->gt(now()->subDays(90)); // Last 3 months
        });

        if ($recentJobs->count() < 5) {
            return 'insufficient-data';
        }

        $averageRecent = $this->calculateMarketAverage($recentJobs);
        $averageOverall = $this->calculateMarketAverage($jobs);

        if ($averageRecent > $averageOverall * 1.05) {
            return 'increasing'; // More than 5% higher
        } elseif ($averageRecent < $averageOverall * 0.95) {
            return 'decreasing'; // More than 5% lower
        } else {
            return 'stable';
        }
    }

    /**
     * Calculate salaries for specific experience level
     */
    private function calculateForExperience($jobs, string $level): float
    {
        if ($jobs->isEmpty()) {
            return 0.0;
        }

        // In a real implementation, this would filter by experience level
        // For now, we'll return adjusted values based on level
        $baseAverage = $this->calculateMarketAverage($jobs);

        $multipliers = [
            'junior' => 0.7,
            'mid' => 1.0,
            'senior' => 1.3,
            'management' => 1.6,
        ];

        return $baseAverage * ($multipliers[$level] ?? 1.0);
    }

    /**
     * Get industry comparison data
     */
    private function getIndustryComparison(string $jobTitle): array
    {
        // This would normally come from a larger dataset
        // For demo purposes, returning some sample data
        $industries = [
            'Technology' => [
                'median_salary' => rand(45000, 85000),
                'growth_rate' => '+5.2%',
                'demand' => 'High'
            ],
            'Healthcare' => [
                'median_salary' => rand(40000, 75000),
                'growth_rate' => '+3.8%',
                'demand' => 'Very High'
            ],
            'Finance' => [
                'median_salary' => rand(50000, 90000),
                'growth_rate' => '+2.1%',
                'demand' => 'Medium'
            ],
            'Education' => [
                'median_salary' => rand(30000, 55000),
                'growth_rate' => '+1.5%',
                'demand' => 'Stable'
            ]
        ];

        return $industries;
    }

    /**
     * Get cost of living adjustment for a location
     */
    public function getCostOfLivingAdjustment(string $location): float
    {
        // In a real application, this would connect to cost of living data
        $adjustments = [
            'London' => 1.3, // 30% higher cost of living
            'Manchester' => 1.0, // Average
            'Birmingham' => 0.95, // Slightly below average
            'Leeds' => 0.9, // Below average
            'Edinburgh' => 1.1, // Above average
            'Bristol' => 1.05, // Slightly above average
        ];

        $normalizedLocation = strtolower($location);
        
        foreach ($adjustments as $city => $adjustment) {
            if (stripos($normalizedLocation, strtolower($city)) !== false) {
                return $adjustment;
            }
        }

        // Default adjustment for other locations
        return 1.0;
    }

    /**
     * Compare salary against market rate
     */
    public function getSalaryCompetitiveness(float $offeredSalary, string $jobTitle, string $location): array
    {
        $marketData = $this->getSalaryData($jobTitle, $location);
        
        $competitiveness = 'unknown';
        $difference = $offeredSalary - $marketData['market_average'];
        $percentageDiff = $marketData['market_average'] > 0 ? 
                         ($difference / $marketData['market_average']) * 100 : 0;

        if ($percentageDiff > 20) {
            $competitiveness = 'excellent';
        } elseif ($percentageDiff > 10) {
            $competitiveness = 'good';
        } elseif ($percentageDiff > -10) {
            $competitiveness = 'fair';
        } elseif ($percentageDiff > -20) {
            $competitiveness = 'below_market';
        } else {
            $competitiveness = 'poor';
        }

        return [
            'offered_salary' => $offeredSalary,
            'market_average' => $marketData['market_average'],
            'competitiveness' => $competitiveness,
            'difference' => $difference,
            'percentage_difference' => round($percentageDiff, 2),
            'comparison' => $this->getCompetitivenessDescription($competitiveness),
        ];
    }

    /**
     * Get description for competitiveness level
     */
    private function getCompetitivenessDescription(string $level): string
    {
        $descriptions = [
            'excellent' => 'Well above market rate - highly competitive offer',
            'good' => 'Above market rate - good, competitive offer',
            'fair' => 'At market rate - fair and reasonable offer',
            'below_market' => 'Below market rate - may struggle to attract top talent',
            'poor' => 'Significantly below market rate - may face recruitment challenges',
        ];

        return $descriptions[$level] ?? 'Competitiveness level unknown';
    }
}