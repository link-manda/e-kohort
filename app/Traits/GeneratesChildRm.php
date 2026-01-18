<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GeneratesChildRm
{
    /**
     * Boot the trait and register model events.
     */
    protected static function bootGeneratesChildRm(): void
    {
        static::creating(function ($child) {
            if (empty($child->no_rm)) {
                $child->no_rm = static::generateUniqueChildRm();
            }
        });
    }

    /**
     * Generate unique Child RM number with format: ANAK-{YEAR}-{SEQUENCE}
     * Example: ANAK-2026-0001, ANAK-2026-0002, etc.
     *
     * Uses database lock to prevent duplicate numbers in concurrent requests.
     */
    protected static function generateUniqueChildRm(): string
    {
        return DB::transaction(function () {
            $year = date('Y');
            $prefix = "ANAK-{$year}-";

            // Lock the table to prevent race condition
            // Find the last RM number for current year
            $lastChild = static::withTrashed()
                ->where('no_rm', 'LIKE', $prefix . '%')
                ->lockForUpdate()
                ->orderByRaw('CAST(SUBSTRING(no_rm, 11) AS UNSIGNED) DESC')
                ->first();

            if ($lastChild) {
                // Extract sequence number from last RM
                $lastSequence = (int) substr($lastChild->no_rm, 10);
                $newSequence = $lastSequence + 1;
            } else {
                // First child of the year
                $newSequence = 1;
            }

            // Format: ANAK-2026-0001 (4 digits with leading zeros)
            $rmNumber = $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            return $rmNumber;
        });
    }

    /**
     * Check if RM number already exists.
     */
    public static function rmExists(string $rmNumber): bool
    {
        return static::withTrashed()->where('no_rm', $rmNumber)->exists();
    }

    /**
     * Get the next available RM number (for preview/display purposes).
     */
    public static function getNextRmNumber(): string
    {
        $year = date('Y');
        $prefix = "ANAK-{$year}-";

        $lastChild = static::withTrashed()
            ->where('no_rm', 'LIKE', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(no_rm, 11) AS UNSIGNED) DESC')
            ->first();

        if ($lastChild) {
            $lastSequence = (int) substr($lastChild->no_rm, 10);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }
}
