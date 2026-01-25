<?php

namespace App\Livewire;

use App\Models\Child;
use App\Models\ChildGrowthRecord;
use App\Services\GrowthCalculatorService;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GrowthEntry extends Component
{
    public $childId;
    public $child;

    // Form inputs
    public $recordDate;
    public $weight;
    public $height;
    public $headCircumference;
    public $measurementMethod = 'Terlentang';
    public $vitaminA = 'Tidak';
    public $dewormingMedicine = false;
    public $pmtGiven = false;
    public $notes;
    public $midwifeName;

    // Calculated properties
    public $ageInMonths;
    public $correctedHeight;
    public $heightCorrectionApplied = false;

    // Status indicators
    public $statusBBU = null;
    public $zscoreBBU = null;
    public $statusTBU = null;
    public $zscoreTBU = null;
    public $statusBBTB = null;
    public $zscoreBBTB = null;

    // Alert flags
    public $hasStunting = false;
    public $hasWasting = false;
    public $hasUnderweight = false;

    // Loading state
    public $isCalculating = false;

    protected $rules = [
        'recordDate' => 'required|date',
        'weight' => 'required|numeric|min:0|max:50',
        'height' => 'required|numeric|min:30|max:150',
        'headCircumference' => 'nullable|numeric|min:20|max:70',
        'measurementMethod' => 'required|in:Terlentang,Berdiri',
        'vitaminA' => 'required|in:Tidak,Biru (6-11 bln),Merah (1-5 thn)',
        'dewormingMedicine' => 'boolean',
        'pmtGiven' => 'boolean',
        'notes' => 'nullable|string|max:500',
        'midwifeName' => 'required|string|max:100',
    ];

    public function mount($childId)
    {
        $this->childId = $childId;
        $this->child = Child::findOrFail($childId);
        $this->recordDate = now()->format('Y-m-d');
        $this->midwifeName = auth()->user()->name ?? '';
    }

    public function updated($propertyName)
    {
        // Trigger real-time calculation when relevant fields change
        if (in_array($propertyName, ['weight', 'height', 'recordDate', 'measurementMethod'])) {
            $this->calculateRealtime();
        }
    }

    public function calculateRealtime()
    {
        // Validasi awal: pastikan semua komponen ada dan valid (bukan string kosong atau 0)
        if (empty($this->weight) || empty($this->height) || empty($this->recordDate)) {
            $this->resetCalculations();
            return;
        }

        $this->isCalculating = true;

        try {
            // Pastikan Service ada. Jika error "Class not found", berarti namespace service salah.
            if (!class_exists(GrowthCalculatorService::class)) {
                throw new \Exception("Service Kalkulator tidak ditemukan.");
            }

            $service = new GrowthCalculatorService();

            // Konversi tipe data untuk memastikan akurasi
            $weight = (float) $this->weight;
            $height = (float) $this->height;
            $date = Carbon::parse($this->recordDate);

            $results = $service->calculateAllIndicators(
                $this->child,
                $date,
                $weight,
                $height,
                $this->measurementMethod
            );

            $this->ageInMonths = $results['age_in_months'];
            $this->correctedHeight = $results['corrected_height'];
            $this->heightCorrectionApplied = $results['height_correction_applied'];

            // BB/U - Check if data exists
            if (isset($results['bb_u']['zscore'])) {
                $this->zscoreBBU = $results['bb_u']['zscore'];
                $this->statusBBU = $results['bb_u']['status'];
                $this->hasUnderweight = $this->zscoreBBU !== null && $this->zscoreBBU < -2;
            } else {
                $this->zscoreBBU = null;
                $this->statusBBU = null; // Set null, not string - database expects null or valid enum
                $this->hasUnderweight = false;
            }

            // TB/U - Check if data exists
            if (isset($results['tb_u']['zscore'])) {
                $this->zscoreTBU = $results['tb_u']['zscore'];
                $this->statusTBU = $results['tb_u']['status'];
                $this->hasStunting = $this->zscoreTBU !== null && $this->zscoreTBU < -2;
            } else {
                $this->zscoreTBU = null;
                $this->statusTBU = null; // Set null, not string
                $this->hasStunting = false;
            }

            // BB/TB - Check if data exists
            if (isset($results['bb_tb']['zscore'])) {
                $this->zscoreBBTB = $results['bb_tb']['zscore'];
                $this->statusBBTB = $results['bb_tb']['status'];
                $this->hasWasting = $this->zscoreBBTB !== null && $this->zscoreBBTB < -2;
            } else {
                $this->zscoreBBTB = null;
                $this->statusBBTB = null; // Set null, not string
                $this->hasWasting = false;
            }

        } catch (\Exception $e) {
            $this->resetCalculations();
            session()->flash('error', 'Gagal menghitung indikator: ' . $e->getMessage());
        } finally {
            $this->isCalculating = false;
        }
    }

    private function resetCalculations()
    {
        $this->statusBBU = null;
        $this->zscoreBBU = null;
        $this->statusTBU = null;
        $this->zscoreTBU = null;
        $this->statusBBTB = null;
        $this->zscoreBBTB = null;
        $this->hasStunting = false;
        $this->hasWasting = false;
        $this->hasUnderweight = false;
        $this->ageInMonths = null;
    }

    public function save()
    {
        $this->validate();

        $this->calculateRealtime();

        // Check if ALL WHO standards data is null (not partially available)
        if ($this->zscoreBBU === null && $this->zscoreTBU === null && $this->zscoreBBTB === null) {
            session()->flash('error', 'Data standar WHO tidak tersedia untuk umur ' . $this->ageInMonths . ' bulan. Saat ini sistem hanya mendukung umur 0-12 bulan. Silakan hubungi administrator untuk menambahkan data WHO Standards untuk umur yang lebih tinggi.');
            return;
        }

        // If some data is available, allow save even if some indicators are null
        // This is because BB/TB might work even if BB/U and TB/U don't have data

        try {
            ChildGrowthRecord::create([
                'child_id' => $this->childId,
                'record_date' => $this->recordDate,
                'age_in_months' => $this->ageInMonths,
                'weight' => $this->weight,
                'height' => $this->height,
                'head_circumference' => $this->headCircumference,
                'measurement_method' => $this->measurementMethod,
                'zscore_bb_u' => $this->zscoreBBU,
                'status_bb_u' => $this->statusBBU,
                'zscore_tb_u' => $this->zscoreTBU,
                'status_tb_u' => $this->statusTBU,
                'zscore_bb_tb' => $this->zscoreBBTB,
                'status_bb_tb' => $this->statusBBTB,
                'vitamin_a' => $this->vitaminA,
                'deworming_medicine' => $this->dewormingMedicine,
                'pmt_given' => $this->pmtGiven,
                'notes' => $this->notes,
                'midwife_name' => $this->midwifeName,
            ]);

            session()->flash('success', 'Data pertumbuhan berhasil disimpan!');

            $this->reset([
                'weight', 'height', 'headCircumference', 'vitaminA',
                'dewormingMedicine', 'pmtGiven', 'notes',
                'zscoreBBU', 'statusBBU', 'zscoreTBU', 'statusTBU',
                'zscoreBBTB', 'statusBBTB', 'hasStunting', 'hasWasting', 'hasUnderweight'
            ]);

            $this->recordDate = now()->format('Y-m-d');

            $this->loadChartData();

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public $chartRecords = [];

    public function loadChartData()
    {
        $records = $this->child->growthRecords()
            ->orderBy('record_date')
            ->get(['record_date', 'age_in_months', 'weight', 'height', 'zscore_bb_u', 'zscore_tb_u', 'zscore_bb_tb']);

        // Logging untuk debug chart data
        Log::info('GrowthEntry::loadChartData', [
            'child_id' => $this->childId,
            'records_count' => $records->count(),
            'first_record' => $records->first() ? $records->first()->toArray() : null,
        ]);

        // Simpan data chart pada properti publik agar dapat diakses di view sebagai fallback
        $this->chartRecords = $records->toArray();
    }

    public function getStatusColorClass($zscore)
    {
        // PERBAIKAN: Gunakan warna biru/indigo yang jelas jika data belum ada (null), jangan abu-abu pucat.
        if ($zscore === null) return 'bg-gradient-to-br from-slate-600 to-slate-800';

        if ($zscore < -3) return 'bg-gradient-to-br from-red-700 to-red-900'; // Gizi Buruk
        if ($zscore < -2) return 'bg-gradient-to-br from-orange-500 to-red-600'; // Gizi Kurang
        if ($zscore < -1) return 'bg-gradient-to-br from-yellow-500 to-orange-600'; // Risiko
        if ($zscore <= 1) return 'bg-gradient-to-br from-emerald-500 to-green-700'; // Normal
        if ($zscore <= 2) return 'bg-gradient-to-br from-yellow-500 to-orange-600'; // Risiko Lebih
        if ($zscore <= 3) return 'bg-gradient-to-br from-orange-500 to-red-600'; // Gizi Lebih
        return 'bg-gradient-to-br from-red-700 to-red-900'; // Obesitas
    }

    public function render()
    {
        $growthHistory = $this->child->growthRecords()
            ->orderBy('record_date', 'desc')
            ->limit(10)
            ->get();

        // Load chart data on initial render
        $this->loadChartData();

        return view('livewire.growth-entry', [
            'growthHistory' => $growthHistory,
            'chartData' => $this->chartRecords,
        ]);
    }
}