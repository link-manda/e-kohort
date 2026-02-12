<?php

namespace App\Http\Controllers;

use App\Models\KbVisit;
use Illuminate\Http\Request;

class KbVisitController extends Controller
{
    /**
     * Display KB visit detail
     */
    public function show(KbVisit $kbVisit)
    {
        // Eager load relationships
        $kbVisit->load(['patient', 'kbMethod']);

        return view('kb-visits.show', [
            'visit' => $kbVisit,
            'patient' => $kbVisit->patient,
        ]);
    }

    /**
     * Show edit form (redirect to KB Entry with visit data)
     */
    public function edit(KbVisit $kbVisit)
    {
        return redirect()->route('kb-entry.index', [
            'patient_id' => $kbVisit->patient_id,
            'visit_id' => $kbVisit->id
        ]);
    }

    /**
     * Delete visit (soft delete)
     */
    public function destroy(KbVisit $kbVisit)
    {
        $patientId = $kbVisit->patient_id;
        $kbVisit->delete();

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Data kunjungan KB berhasil dihapus');
    }
}
