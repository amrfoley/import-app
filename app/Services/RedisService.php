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
            return !empty($value)? Redis::set($key, $value, $time ?? self::LIFETIME) : false;
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

    public static function add($key, $value, $time = null): bool
    {
        try
        {
            return !empty($value)? Redis::sadd($key, $value, $time ?? self::LIFETIME) : false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public static function remove($list, $key): bool
    {
        try
        {
            return Redis::srem($list, $key);
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public static function all($list)
    {
        try
        {
            $members = Redis::smembers($list);
            if(!empty($members))
            {
                $collections = [];
                foreach($members as $member)
                {
                    $collections[] = json_decode($member, true);
                }
                return $collections;
            }
        }
        catch(\Exception $e)
        {
            return null;
        }
    }

    public static function fetch($list, $key)
    {
        $members = self::all($list);

        if(!empty($members))
        {
            foreach($members as $member)
            {
                if(
                    (isset($member['id']) && $member['id'] === $key) || 
                    (isset($member['part_number']) && $member['part_number'] === $key)
                )
                {
                    return $member;
                }
            }
        }

        return null;
    }
}