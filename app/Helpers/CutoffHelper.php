<?php

namespace App\Helpers;

use App\Models\MilkPrice;
use Carbon\Carbon;

class CutoffHelper
{
    /**
     * Check if the cutoff time has passed for a given milk type.
     */
    public static function isPastCutoff(string $milkType): bool
    {
        $mp = MilkPrice::forType($milkType);
        if (!$mp || !$mp->cutoff_time) return false;

        $cutoff = Carbon::today()->setTimeFromTimeString($mp->cutoff_time);
        return now()->greaterThan($cutoff);
    }

    /**
     * Adjust a requested date: if it's today or tomorrow and past cutoff, push to day after tomorrow.
     * After cutoff on day 13 → day 14 delivery is locked → earliest is day 15.
     */
    public static function adjustDate(Carbon $requestedDate, string $milkType): Carbon
    {
        $mp = MilkPrice::forType($milkType);
        if (!$mp || !$mp->cutoff_time) return $requestedDate;

        $cutoff = Carbon::today()->setTimeFromTimeString($mp->cutoff_time);

        if (now()->greaterThan($cutoff)) {
            // Past cutoff — tomorrow's delivery is locked, earliest is day after tomorrow
            $earliest = Carbon::today()->addDays(2);
            if ($requestedDate->lessThan($earliest)) {
                return $earliest;
            }
        }

        return $requestedDate;
    }

    /**
     * Get the earliest allowed date for extra milk ordering.
     * Before cutoff: tomorrow. After cutoff: day after tomorrow.
     */
    public static function earliestDate(string $milkType): Carbon
    {
        return static::isPastCutoff($milkType) ? Carbon::today()->addDays(2) : Carbon::tomorrow();
    }

    /**
     * Get cutoff time string (HH:MM) for a milk type.
     */
    public static function cutoffTime(string $milkType): ?string
    {
        $mp = MilkPrice::forType($milkType);
        return $mp ? substr($mp->cutoff_time ?? '', 0, 5) : null;
    }
}
