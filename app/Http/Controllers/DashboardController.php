<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        // Total Patients (including children for Bayi/Balita category)
        $totalPatients = Patient::count() + Child::count();

        // Patient Categories Breakdown
        $patientCategories = [
            'Umum' => Patient::where('category', 'Umum')->count(),
            'Bumil' => Patient::where('category', 'Bumil')->count(),
            'Bayi/Balita' => Child::count(),
            'Lansia' => Patient::where('category', 'Lansia')->count(),
        ];

        // Active Pregnancies
        $activePregnancies = Pregnancy::where('status', 'Aktif')->count();

        // Total ANC Visits This Month
        $visitsThisMonth = AncVisit::whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();

        // High Risk Patients (last 30 days)
        $highRiskPatients = AncVisit::where('risk_category', 'Tinggi')
            ->orWhere('risk_category', 'Ekstrem')
            ->where('visit_date', '>=', now()->subDays(30))
            ->with('pregnancy.patient')
            ->latest('visit_date')
            ->limit(5)
            ->get();

        // Recent Visits (last 7 days)
        $recentVisits = AncVisit::with('pregnancy.patient')
            ->where('visit_date', '>=', now()->subDays(7))
            ->latest('visit_date')
            ->limit(10)
            ->get();

        // MAP Alert Count (MAP > 100)
        $mapAlertCount = AncVisit::where('map_score', '>', 100)
            ->where('visit_date', '>=', now()->subDays(30))
            ->count();

        // Triple Elimination Reactive Count
        $tripleEliminationCount = AncVisit::where(function ($query) {
            $query->where('hiv_status', 'R')
                ->orWhere('syphilis_status', 'R')
                ->orWhere('hbsag_status', 'R');
        })
            ->where('visit_date', '>=', now()->subDays(30))
            ->count();

        return view('dashboard', compact(
            'totalPatients',
            'patientCategories',
            'activePregnancies',
            'visitsThisMonth',
            'highRiskPatients',
            'recentVisits',
            'mapAlertCount',
            'tripleEliminationCount'
        ));
    }
}
