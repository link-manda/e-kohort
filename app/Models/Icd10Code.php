<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Icd10Code extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'keywords',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope untuk hanya kode yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kategori tertentu.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Search ICD-10 codes berdasarkan keyword.
     */
    public static function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        $search = strtolower($query);

        return self::active()
            ->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(code) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(keywords) LIKE ?', ["%{$search}%"]);
            })
            ->orderBy('code')
            ->get();
    }

    /**
     * Get formatted display text.
     */
    public function getDisplayTextAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }
}
