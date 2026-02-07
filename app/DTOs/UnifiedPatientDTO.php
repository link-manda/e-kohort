<?php

namespace App\DTOs;

use App\Models\Patient;
use App\Models\Child;
use Carbon\Carbon;

/**
 * Unified DTO to normalize Patient and Child data for display in PatientList
 */
class UnifiedPatientDTO
{
    public int $id;
    public string $type; // 'patient' or 'child'
    public string $name;
    public ?string $nik;
    public ?string $phone;
    public ?string $address;
    public string $gender;
    public Carbon $dob;
    public string $category;
    public Carbon $created_at;

    public function __construct(
        int $id,
        string $type,
        string $name,
        ?string $nik,
        ?string $phone,
        ?string $address,
        string $gender,
        Carbon $dob,
        string $category,
        Carbon $created_at
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->nik = $nik;
        $this->phone = $phone;
        $this->address = $address;
        $this->gender = $gender;
        $this->dob = $dob;
        $this->category = $category;
        $this->created_at = $created_at;
    }

    /**
     * Create DTO from Patient model
     */
    public static function fromPatient(Patient $patient): self
    {
        return new self(
            id: $patient->id,
            type: 'patient',
            name: $patient->name,
            nik: $patient->nik,
            phone: $patient->phone,
            address: $patient->address,
            gender: $patient->gender ?? 'P',
            dob: $patient->dob ?? now()->subYears(30),
            category: $patient->category ?? 'Umum',
            created_at: $patient->created_at ?? now()
        );
    }

    /**
     * Create DTO from Child model
     */
    public static function fromChild(Child $child): self
    {
        return new self(
            id: $child->id,
            type: 'child',
            name: $child->name,
            nik: $child->nik,
            phone: $child->parent_display_phone,
            address: $child->parent_display_address,
            gender: $child->gender,
            dob: $child->dob,
            category: 'Bayi/Balita',
            created_at: $child->created_at ?? now()
        );
    }

    /**
     * Get age in years
     */
    public function getAge(): int
    {
        return (int) floor($this->dob->floatDiffInYears(now()));
    }

    /**
     * Get formatted age for display
     */
    public function getFormattedAge(): string
    {
        $now = now();

        // Handle future DOB (should not happen, but safety check)
        if ($this->dob->isAfter($now)) {
            return '0 hari';
        }

        $years = (int) floor($this->dob->floatDiffInYears($now));
        $months = (int) floor($this->dob->floatDiffInMonths($now));
        $days = (int) floor($this->dob->floatDiffInDays($now));

        if ($years >= 1) {
            return $years . ' tahun';
        }

        if ($months >= 1) {
            return $months . ' bulan';
        }

        return $days . ' hari';
    }

    /**
     * Get route for show page
     */
    public function getShowRoute(): string
    {
        if ($this->type === 'child') {
            return route('children.show', $this->id);
        }
        return route('patients.show', $this->id);
    }
}
