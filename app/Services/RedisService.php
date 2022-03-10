<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    const LIFETIME = 14400;

    public static function set($key, $value, $time = null): bool
    {
        try
        {
            return Redis::set($key, $value, $time ?? self::LIFETIME);
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public static function get($key)
    {
        try
        {
            return Redis::get($key);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }

    public static function del($key): bool
    {
        try
        {
            return Redis::del($key);
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public static function has($key): bool
    {
        try
        {
            return Redis::exists($key);
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
}