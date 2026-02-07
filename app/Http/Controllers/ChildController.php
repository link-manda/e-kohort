<?php

namespace App\Http\Controllers;

use App\Models\Child;
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
}
