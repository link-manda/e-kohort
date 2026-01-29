# DELIVERY RECORDS - IMPLEMENTATION GUIDE

## ✅ COMPLETED IMPLEMENTATION

### 1. Database Schema

**File:** `database/migrations/2026_01_25_135449_create_delivery_records_table.php`

Tabel `delivery_records` sudah dibuat dengan struktur lengkap:

#### A. Data Persalinan Umum

- `delivery_date_time` - Tanggal & Jam Lahir (PENTING untuk Surat Keterangan Lahir)
- `gestational_age` - Umur Kehamilan (Minggu) saat lahir
- `birth_attendant` - Penolong: Bidan/Dokter
- `place_of_birth` - Tempat Lahir

#### B. Data Ibu - Proses Persalinan (Kala I-IV)

- `duration_first_stage` - Lama Kala I (jam)
- `duration_second_stage` - Lama Kala II (menit)
- `delivery_method` - Cara Persalinan (4 options)
- `placenta_delivery` - Cara Lahir Plasenta (Spontan/Manual/Sisa)
- `perineum_rupture` - Keadaan Perineum (6 options)
- `bleeding_amount` - Estimasi Perdarahan (ml)
- `blood_pressure` - Tensi Pasca Salin (Kala IV)

#### C. Data Bayi - Identitas & Antropometri

- `baby_name` - Nama Bayi (nullable, default: "By. Ny. X")
- `gender` - Jenis Kelamin (L/P)
- `birth_weight` - Berat Badan Lahir (gram)
- `birth_length` - Panjang Badan Lahir (cm)
- `head_circumference` - Lingkar Kepala (cm)

#### D. Data Bayi - Kondisi Lahir

- `apgar_score_1` - APGAR Score Menit ke-1
- `apgar_score_5` - APGAR Score Menit ke-5
- `condition` - Kondisi Bayi (Hidup/Meninggal/Asfiksia)
- `congenital_defect` - Kelainan Bawaan (text, nullable)

#### E. Manajemen Bayi Baru Lahir (Checklist)

- `imd_initiated` - Inisiasi Menyusu Dini < 1 Jam (boolean)
- `vit_k_given` - Injeksi Vitamin K1 (boolean)
- `eye_ointment_given` - Salep Mata (boolean)
- `hb0_given` - Imunisasi Hepatitis B0 (boolean)

**Relasi:** One-to-One dengan `pregnancies` via `pregnancy_id` (Foreign Key)

---

### 2. Model & Relationships

**File:** `app/Models/DeliveryRecord.php`

#### Features:

- ✅ Complete fillable attributes
- ✅ Type casting (datetime, float, boolean)
- ✅ Relationship: `belongsTo(Pregnancy::class)`
- ✅ Helper methods:
    - `hasHighBleedingRisk()` - Check if bleeding > 500ml
    - `needsNeonatalAttention()` - Check if APGAR < 7
    - `hasCompleteNewbornManagement()` - Check if all checklists done

**Updated:** `app/Models/Pregnancy.php`

- ✅ Added `hasOne(DeliveryRecord::class)` relationship

---

### 3. Auto-Update Logic (Observer)

**File:** `app/Observers/DeliveryRecordObserver.php`

#### Logic pada Event `created`:

1. **Update Pregnancy Status** → Set `pregnancies.status = 'Lahir'`
2. **Auto-Create Child Record** → Generate entry di tabel `children`:
    - Copy data: name, gender, dob, birth_weight, birth_length, etc.
    - Default name: "By. [Mother Name]" if baby_name is empty
    - Only if condition = 'Hidup'
3. **Auto-Create HB0 Immunization** → If `hb0_given = true`:
    - Create entry di tabel `immunization_actions`
    - Vaccine code: HB0
    - Date: sama dengan `delivery_date_time`

**Registered in:** `app/Providers/AppServiceProvider.php`

---

## 🔧 NEXT STEPS (Frontend Development)

### 4. Livewire Component - DeliveryEntry

**To Create:** `app/Livewire/DeliveryEntry.php`

#### Required Features:

1. **Select Pregnancy** - Dropdown pilih pregnancies dengan status 'Aktif'
2. **Section 1: Data Persalinan (Ibu)**
    - DateTime Picker untuk `delivery_date_time`
    - Input Cara Lahir, Penolong
    - Input Keadaan Jalan Lahir (Robekan Perineum)
    - Input Perdarahan (Alert Merah jika > 500ml)

3. **Section 2: Data Bayi (Outcome)**
    - Input JK, BB, PB, Lingkar Kepala
    - Input APGAR Score (1 & 5 menit)
    - Input Kondisi (Hidup/Meninggal/Asfiksia)
    - **Toggle Switch Checklist:**
        - ☐ IMD Dilakukan?
        - ☐ Vit K Diberikan?
        - ☐ Salep Mata Diberikan?
        - ☐ HB0 Diberikan?

4. **Section 3: Kesimpulan**
    - Status Ibu: Sehat / Rujuk
    - Status Bayi: Sehat / Rujuk / Meninggal

#### Validation Rules:

```php
return [
    'pregnancy_id' => 'required|exists:pregnancies,id',
    'delivery_date_time' => 'required|date',
    'gestational_age' => 'required|integer|min:20|max:44',
    'birth_attendant' => 'required|string',
    'place_of_birth' => 'required|string',
    'delivery_method' => 'required|in:Spontan Belakang Kepala,Sungsang,Vakum,Sectio Caesarea',
    'gender' => 'required|in:L,P',
    'birth_weight' => 'required|numeric|min:500|max:6000',
    'birth_length' => 'required|numeric|min:30|max:60',
    'apgar_score_1' => 'required|integer|min:0|max:10',
    'apgar_score_5' => 'required|integer|min:0|max:10',
    'condition' => 'required|in:Hidup,Meninggal,Asfiksia',
];
```

---

### 5. Blade View

**To Create:** `resources/views/livewire/delivery-entry.blade.php`

#### UI Structure:

```html
<div class="container">
    <!-- Header -->
    <div class="card-header">
        <h3>Form Persalinan & Bayi Baru Lahir</h3>
    </div>

    <!-- Form -->
    <form wire:submit.prevent="save">
        <!-- Section 1: Data Persalinan (Ibu) -->
        <div class="card mb-4">
            <div class="card-header bg-pink-100">
                <h4>📋 Data Persalinan (Ibu)</h4>
            </div>
            <div class="card-body">
                <!-- Inputs here -->
            </div>
        </div>

        <!-- Section 2: Data Bayi -->
        <div class="card mb-4">
            <div class="card-header bg-blue-100">
                <h4>👶 Data Bayi Baru Lahir</h4>
            </div>
            <div class="card-body">
                <!-- Inputs here -->

                <!-- Checklist Toggle Switches -->
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="imd_initiated" />
                        <span>IMD < 1 Jam</span>
                    </label>
                    <!-- ... -->
                </div>
            </div>
        </div>

        <!-- Section 3: Kesimpulan -->
        <div class="card mb-4">
            <div class="card-header bg-green-100">
                <h4>✅ Kesimpulan</h4>
            </div>
            <div class="card-body">
                <!-- Summary & Status -->
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
            Simpan Data Persalinan
        </button>
    </form>
</div>
```

---

### 6. Routing

**Add to:** `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    // ... existing routes

    Route::get('/delivery/create', \App\Livewire\DeliveryEntry::class)
        ->name('delivery.create');
});
```

---

## 🎯 MIGRATION BENEFITS

### Before (Old Structure):

```
pregnancies
├─ delivery_date (datetime)
├─ delivery_method (enum)
├─ birth_attendant (string)
├─ outcome (enum)
└─ baby_gender (enum)
```

**Problem:** Data terlalu sederhana, tidak sesuai standar Register Bidan

### After (New Structure):

```
pregnancies                    delivery_records
├─ id                         ├─ id
├─ status                     ├─ pregnancy_id (FK)
└─ ...                        ├─ delivery_date_time
                              ├─ gestational_age
                              ├─ Kala I-IV data (7 fields)
                              ├─ Baby identity & anthropometry (6 fields)
                              ├─ Baby condition (4 fields)
                              └─ Newborn management checklist (4 boolean)
```

**Benefit:**

- ✅ Data lengkap sesuai standar medis
- ✅ Auto-create child record (magic feature)
- ✅ Auto-create HB0 immunization
- ✅ Proper data separation (normalized)

---

## 📊 TESTING CHECKLIST

- [ ] Create delivery record via manual query
- [ ] Verify pregnancy status updated to 'Lahir'
- [ ] Verify child record auto-created in `children` table
- [ ] Verify HB0 immunization created if `hb0_given = true`
- [ ] Test helper methods: `hasHighBleedingRisk()`, etc.
- [ ] Create Livewire component `DeliveryEntry`
- [ ] Create Blade view with 3 sections
- [ ] Add form validation
- [ ] Test complete workflow: Form → Save → Auto-create

---

## 🔗 RELATED FILES

### Models:

- `app/Models/DeliveryRecord.php`
- `app/Models/Pregnancy.php`
- `app/Models/Child.php`
- `app/Models/ImmunizationAction.php`

### Database:

- `database/migrations/2026_01_25_135449_create_delivery_records_table.php`

### Observers:

- `app/Observers/DeliveryRecordObserver.php`
- `app/Providers/AppServiceProvider.php` (registration)

### Frontend (To Be Created):

- `app/Livewire/DeliveryEntry.php`
- `resources/views/livewire/delivery-entry.blade.php`

---

## 💡 ADDITIONAL NOTES

### Data Migration (Optional)

If you need to migrate old delivery data from `pregnancies` to `delivery_records`:

```sql
INSERT INTO delivery_records (
    pregnancy_id,
    delivery_date_time,
    birth_attendant,
    place_of_birth,
    delivery_method,
    gender,
    condition,
    created_at,
    updated_at
)
SELECT
    id as pregnancy_id,
    delivery_date as delivery_date_time,
    birth_attendant,
    place_of_birth,
    CASE
        WHEN delivery_method = 'Normal' THEN 'Spontan Belakang Kepala'
        WHEN delivery_method = 'Caesar/Sectio' THEN 'Sectio Caesarea'
        ELSE delivery_method
    END as delivery_method,
    baby_gender as gender,
    outcome as condition,
    NOW() as created_at,
    NOW() as updated_at
FROM pregnancies
WHERE status = 'Lahir'
  AND delivery_date IS NOT NULL;
```

**⚠️ WARNING:** Run this only once and backup database first!

---

## 🎉 SUMMARY

✅ **Database:** Tabel `delivery_records` created with 27 columns
✅ **Model:** DeliveryRecord model with relationships & helpers
✅ **Observer:** Auto-update logic (pregnancy status, child creation, HB0 immunization)
✅ **Migration:** Successfully migrated (759.38ms)

**Status:** Backend COMPLETE ✨
**Next:** Frontend Development (Livewire Component + Blade View)
