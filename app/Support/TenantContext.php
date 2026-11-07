<?php

namespace App\Support;

class TenantContext
{
    protected static ?int $businessId = null;

    protected static ?int $branchId = null;

    public static function set(?int $businessId, ?int $branchId = null): void
    {
        static::$businessId = $businessId;
        static::$branchId = $branchId;
    }

    public static function businessId(): ?int
    {
        return static::$businessId;
    }

    public static function branchId(): ?int
    {
        return static::$branchId;
    }

    public static function clear(): void
    {
        static::$businessId = null;
        static::$branchId = null;
    }

    public static function run(?int $businessId, callable $callback, ?int $branchId = null): mixed
    {
        $previousBusiness = static::$businessId;
        $previousBranch = static::$branchId;
        static::set($businessId, $branchId);

        try {
            return $callback();
        } finally {
            static::set($previousBusiness, $previousBranch);
        }
    }
}
