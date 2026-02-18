<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\GeneralVisit;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * Display the specified child's profile.
     */
    public function show(Child $child)
    {
        // Load all relevant relationships for child profile
        $child->load([
            'patient',
            'childVisits' => function ($query) {
                $query->with('immunizationActions')
                    ->orderBy('visit_date', 'desc');
            },
            'generalVisits' => function ($query) {
                $query->with('prescriptions')
                    ->orderBy('visit_date', 'desc')
                    ->limit(10);
            },
            'growthRecords' => function ($query) {
                $query->orderBy('record_date', 'desc');
            }
        ]);

        return view('children.show', compact('child'));
    }

    /**
     * Display immunization visit detail.
     */
    public function showVisit(Child $child, ChildVisit $childVisit)
    {
        // Ensure the visit belongs to this child
        abort_unless($childVisit->child_id === $child->id, 404);

        // Load relationships
        $childVisit->load(['immunizationActions.vaccine']);
        $child->load('patient');

        return view('children.immunization-visit-show', compact('child', 'childVisit'));
    }

    /**
     * Display general visit detail for a child.
     */
    public function showGeneralVisit(Child $child, GeneralVisit $generalVisit)
    {
        // Ensure the visit belongs to this child
        abort_unless($generalVisit->child_id === $child->id, 404);

        // Load relationships
        $generalVisit->load('prescriptions');
        $child->load('patient');

        return view('children.general-visit-show', compact('child', 'generalVisit'));
    }
}
