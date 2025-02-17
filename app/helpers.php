<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (! function_exists('cache_ttl')) {
    function cache_ttl($seconds = 60): string
    {
        return $seconds;
    }
}

if (! function_exists('cache_ttl_image')) {
    function cache_ttl_image($seconds = 3600): string
    {
        return $seconds;
    }
}

if (! function_exists('default_per_page')) {
    function default_per_page(): int
    {
        return 10;
    }
}

if (! function_exists('current_user')) {
    function current_user(): User|Authenticatable|null
    {
        return auth()->user();
    }
}
