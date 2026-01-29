<?php

namespace Tests\Feature;

use App\Livewire\ChildIndex;
use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\ImmunizationAction;
use App\Models\Patient;
use App\Models\Vaccine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ImmunizationFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_immunization_filter_works()
    {
        // 1. Setup Data
        // Create Patient (Mother)
        $patient = Patient::create([
             'name' => 'Mother',
             'phone' => '081234567890',
             'dob' => '1990-01-01',
             'address' => 'Test Address',
        ]);

        // Child 1: Complete (10 vaccines)
        $childComplete = Child::create([
            'patient_id' => $patient->id,
            'name' => 'Child Complete',
            'no_rm' => 'RM001',
            'gender' => 'L',
            'dob' => now()->subYear(),
            'status' => 'Hidup'
        ]);

        $vaccines = ['HB0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'IPV'];

        foreach ($vaccines as $vaccineType) {
             if (Vaccine::where('code', $vaccineType)->doesntExist()) {
                  Vaccine::create(['code' => $vaccineType, 'name' => $vaccineType]);
             }

             $visit = ChildVisit::create([
                 'child_id' => $childComplete->id,
                 'visit_date' => now(),
                 'age_month' => 5,
             ]);

             ImmunizationAction::create([
                 'child_visit_id' => $visit->id,
                 'vaccine_type' => $vaccineType,
             ]);
        }


        // Child 2: Partial (5 vaccines)
        $childPartial = Child::create([
            'patient_id' => $patient->id,
            'name' => 'Child Partial',
            'no_rm' => 'RM002',
            'gender' => 'P',
            'dob' => now()->subYear(),
            'status' => 'Hidup'
        ]);

        $partialVaccines = array_slice($vaccines, 0, 5);
        foreach ($partialVaccines as $vaccineType) {
             $visit = ChildVisit::create([
                 'child_id' => $childPartial->id,
                 'visit_date' => now(),
                 'age_month' => 5,
             ]);

             ImmunizationAction::create([
                 'child_visit_id' => $visit->id,
                 'vaccine_type' => $vaccineType,
             ]);
        }

        // Child 3: None (0 vaccines)
        $childNone = Child::create([
            'patient_id' => $patient->id,
            'name' => 'Child None',
            'no_rm' => 'RM003',
            'gender' => 'L',
            'dob' => now()->subYear(),
            'status' => 'Hidup'
        ]);
        // Create a visit but no immunization
        ChildVisit::create([
             'child_id' => $childNone->id,
             'visit_date' => now(),
             'age_month' => 5,
        ]);


        // 2. Test 'complete'
        Livewire::test(ChildIndex::class)
            ->set('immunizationFilter', 'complete')
            ->assertViewHas('children', function ($children) use ($childComplete) {
                return $children->count() === 1 && $children->first()->id === $childComplete->id;
            });

        // 3. Test 'partial'
        Livewire::test(ChildIndex::class)
            ->set('immunizationFilter', 'partial')
            ->assertViewHas('children', function ($children) use ($childPartial) {
                return $children->count() === 1 && $children->first()->id === $childPartial->id;
            });

        // 4. Test 'none'
        Livewire::test(ChildIndex::class)
            ->set('immunizationFilter', 'none')
            ->assertViewHas('children', function ($children) use ($childNone) {
                return $children->count() === 1 && $children->first()->id === $childNone->id;
            });

        // 5. Test 'all'
        Livewire::test(ChildIndex::class)
            ->set('immunizationFilter', 'all')
            ->assertViewHas('children', function ($children) {
                return $children->count() === 3;
            });
    }
}
