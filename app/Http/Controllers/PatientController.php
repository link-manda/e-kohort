<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::query()->with('activePregnancy');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by blood type
        if ($request->has('blood_type') && $request->blood_type != '') {
            $query->where('blood_type', $request->blood_type);
        }

        $patients = $query->latest()->paginate(15);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:patients,nik',
            'no_kk' => 'nullable|string|size:16',
            'no_bpjs' => 'nullable|string|max:13',
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'blood_type' => 'required|in:A,B,AB,O,Unknown',
            'husband_name' => 'nullable|string|max:255',
            'husband_nik' => 'nullable|string|size:16',
            'husband_job' => 'nullable|string|max:255',
        ]);

        $patient = Patient::create($validated);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load(['pregnancies.ancVisits']);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:patients,nik,' . $patient->id,
            'no_kk' => 'nullable|string|size:16',
            'no_bpjs' => 'nullable|string|max:13',
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'blood_type' => 'required|in:A,B,AB,O,Unknown',
            'husband_name' => 'nullable|string|max:255',
            'husband_nik' => 'nullable|string|size:16',
            'husband_job' => 'nullable|string|max:255',
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus!');
    }

    /**
     * Print patient complete record
     */
    public function print(Patient $patient)
    {
        // Eager load relationships
        $patient->load([
            'pregnancies.ancVisits.labResult',
            'pregnancies' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return view('patients.print', compact('patient'));
    }
}
