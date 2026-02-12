<?php

namespace App\Http\Controllers;

use App\Models\PostnatalVisit;
use Illuminate\Http\Request;

class PostnatalVisitController extends Controller
{
    /**
     * Display postnatal visit detail
     */
    public function show(PostnatalVisit $postnatalVisit)
    {
        // Eager load relationships
        $postnatalVisit->load([
            'pregnancy.patient',
            'pregnancy.deliveryRecord'
        ]);

        return view('postnatal-visits.show', [
            'visit' => $postnatalVisit,
            'patient' => $postnatalVisit->pregnancy->patient,
            'delivery' => $postnatalVisit->pregnancy->deliveryRecord,
        ]);
    }

    /**
     * Show edit form (redirect to Postnatal Entry)
     */
    public function edit(PostnatalVisit $postnatalVisit)
    {
        return redirect()->route('pregnancies.postnatal', [
            'pregnancy' => $postnatalVisit->pregnancy_id,
            'visit_id' => $postnatalVisit->id
        ]);
    }

    /**
     * Delete visit (soft delete)
     */
    public function destroy(PostnatalVisit $postnatalVisit)
    {
        $patientId = $postnatalVisit->pregnancy->patient_id;
        $postnatalVisit->delete();

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Data kunjungan nifas berhasil dihapus');
    }
}
