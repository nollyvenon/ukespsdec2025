<?php

namespace App\Services;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class SecurityService
{
    /**
     * Check if the user's account should be locked due to too many failed attempts.
     */
    public function isAccountLocked(string $key): bool
    {
        // Check if there have been too many attempts (e.g., 10 failed attempts)
        return RateLimiter::tooManyAttempts($key, 10);
    }

    /**
     * Get the number of remaining attempts before lockout.
     */
    public function remainingAttempts(string $key): int
    {
        return RateLimiter::retriesLeft($key, 10);
    }

    /**
     * Get the time until the lockout expires.
     */
    public function lockoutTime(string $key): int
    {
        return RateLimiter::availableIn($key);
    }

    /**
     * Record a failed login attempt.
     */
    public function recordFailedAttempt(string $key): void
    {
        RateLimiter::hit($key, 3600); // Lock for 1 hour after 10 failed attempts
    }

    /**
     * Clear failed attempts for a user.
     */
    public function clearFailedAttempts(string $key): void
    {
        RateLimiter::clear($key);
    }

    /**
     * Generate a throttle key for the given email and IP address.
     */
    public function throttleKey(string $email, string $ip): string
    {
        return Str::transliterate(Str::lower($email).'|'.$ip);
    }
}