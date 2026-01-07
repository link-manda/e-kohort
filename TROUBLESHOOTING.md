# 🔧 TROUBLESHOOTING GUIDE - E-KOHORT LIVEWIRE

## Common Issues & Solutions

### Issue 1: "Incorrect integer value: empty string" SQL Error ⚡ NEW

**Symptoms:**

```
SQLSTATE[22007]: Invalid datetime format: 1366 Incorrect integer value: ''
for column `e_kohort_klinik`.`pregnancies`.`pregnancy_gap` at row 1
```

**Cause:** Empty string (`''`) being sent to integer/numeric columns that are nullable.

**Solution:**

```php
// BEFORE (CAUSES ERROR):
Pregnancy::create([
    'pregnancy_gap' => $this->pregnancy_gap, // Empty string ''
    'risk_score_initial' => $this->risk_score_initial, // Empty string ''
]);

// AFTER (FIXED):
Pregnancy::create([
    'pregnancy_gap' => $this->pregnancy_gap ?: null,
    'risk_score_initial' => $this->risk_score_initial ?: null,
]);
```

**Explanation:**

-   Livewire sends empty form inputs as empty strings (`''`)
-   MySQL integer columns cannot accept empty strings
-   Use `?: null` to convert empty string to `NULL`
-   This works for all nullable numeric fields (integer, float, decimal)

**Applied to:**

-   ✅ `app/Livewire/PregnancyRegistration.php` - pregnancy_gap, risk_score_initial
-   ✅ `app/Livewire/AncVisitWizard.php` - weight, lila, tfu, djj, hb, blood_sugar

---

### Issue 2: "Unable to resolve dependency" Error

**Symptoms:**

```
Unable to resolve dependency [Parameter #0 [ <required> $parameterName ]]
```

**Cause:** Parameter name mismatch between Livewire call and mount method.

**Solution:**

```php
// In View:
@livewire('component-name', ['patient_id' => $patient_id])

// In Component:
public function mount($patient_id) { // Must match exactly!
```

**Check:**

-   Parameter names must be snake_case on both sides
-   Cannot use camelCase in mount() if passing snake_case

---

### Issue 3: "Property not found" in Blade

**Symptoms:**

```
Property [$propertyName] not found on component
```

**Cause:** Property not declared as public in Livewire component.

**Solution:**

```php
class MyComponent extends Component
{
    public $myProperty; // Must be public!

    public function mount()
    {
        $this->myProperty = 'value';
    }
}
```

---

### Issue 4: "Trying to get property of non-object"

**Symptoms:**

```
Trying to get property 'name' of non-object
```

**Cause:** Relationship not loaded or object is null.

**Solution:**

```php
// In mount():
$this->pregnancy = Pregnancy::with('patient')->findOrFail($id);

// In Blade:
@if($pregnancy && $pregnancy->patient)
    {{ $pregnancy->patient->name }}
@endif
```

---

### Issue 5: Real-time Calculation Not Working

**Symptoms:** MAP/HPL doesn't update when inputs change.

**Cause:** Not using `wire:model.live`.

**Solution:**

```blade
<!-- WRONG: -->
<input wire:model="systolic">

<!-- CORRECT: -->
<input wire:model.live="systolic">
```

**In Component:**

```php
public function updated($propertyName)
{
    if (in_array($propertyName, ['systolic', 'diastolic'])) {
        $this->calculateMAP();
    }
}
```

---

### Issue 6: Form Submission Doesn't Work

**Symptoms:** Nothing happens when clicking submit button.

**Cause:** Missing `wire:submit.prevent` or wrong method name.

**Solution:**

```blade
<form wire:submit.prevent="save">
    <!-- form fields -->
    <button type="submit">Submit</button>
</form>
```

```php
public function save()
{
    $this->validate();
    // save logic
}
```

---

### Issue 7: Validation Errors Not Showing

**Symptoms:** Red border doesn't appear on invalid fields.

**Cause:** Missing `@error` directive.

**Solution:**

```blade
<input wire:model="field_name"
       class="border @error('field_name') border-red-500 @enderror">
@error('field_name')
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
@enderror
```

---

### Issue 8: Session Flash Messages Not Appearing

**Symptoms:** Success/error messages don't show.

**Cause:** Missing session check in Blade.

**Solution:**

```blade
@if (session()->has('success'))
    <div class="bg-green-50 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-50 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif
```

---

### Issue 9: Redirect Not Working

**Symptoms:** Page doesn't redirect after save.

**Cause:** Missing `return` statement.

**Solution:**

```php
// WRONG:
public function save()
{
    // save logic
    redirect()->route('some.route');
}

// CORRECT:
public function save()
{
    // save logic
    return redirect()->route('some.route');
}
```

---

### Issue 10: Component Not Loading

**Symptoms:** Blank page or "Component not found".

**Cause:** Component not registered or wrong name.

**Solution:**

1. Check component name in Blade:

```blade
@livewire('pregnancy-registration', ['patient_id' => $id])
```

2. Verify file location:

```
app/Livewire/PregnancyRegistration.php
```

3. Check namespace:

```php
namespace App\Livewire;
class PregnancyRegistration extends Component
```

4. Clear cache:

```bash
php artisan livewire:discover
php artisan optimize:clear
```

---

### Issue 11: Database Not Saving

**Symptoms:** No error but data not in database.

**Cause:** Missing fillable fields or wrong column names.

**Solution:**

```php
// In Model:
protected $fillable = [
    'patient_id',
    'gravida',
    'hpht',
    // ... all fields you want to save
];

// In Component:
Pregnancy::create([
    'patient_id' => $this->patient_id, // Match fillable
    'gravida' => $this->gravida,
]);
```

**Debug:**

```php
public function save()
{
    $this->validate();

    try {
        $pregnancy = Pregnancy::create([...]);
        \Log::info('Pregnancy created:', $pregnancy->toArray());
    } catch (\Exception $e) {
        \Log::error('Save error:', ['message' => $e->getMessage()]);
        session()->flash('error', $e->getMessage());
    }
}
```

---

## Quick Diagnostics Commands

```bash
# Clear all caches
php artisan optimize:clear

# Rediscover Livewire components
php artisan livewire:discover

# Check routes
php artisan route:list --path=pregnancies
php artisan route:list --path=anc-visits

# Check database connection
php artisan tinker
>>> App\Models\Patient::count()

# Run migrations
php artisan migrate:fresh --seed

# Check for syntax errors
php -l app/Livewire/PregnancyRegistration.php
```

---

## Development Tools

### Enable Debug Mode

```env
# .env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Watch for Changes

```bash
npm run dev
```

### Check Livewire Requests (Browser DevTools)

1. Open Chrome DevTools (F12)
2. Go to Network tab
3. Filter: `livewire`
4. Watch for Livewire update requests
5. Check request/response payloads

---

## Testing Checklist

Before reporting an issue, verify:

-   [ ] Laravel server is running (`php artisan serve`)
-   [ ] Database is accessible
-   [ ] Migrations are up to date
-   [ ] Demo data is seeded
-   [ ] Browser cache is cleared (Ctrl+Shift+Delete)
-   [ ] Laravel cache is cleared (`php artisan optimize:clear`)
-   [ ] JavaScript console shows no errors (F12)
-   [ ] Network requests are successful (200 status)
-   [ ] User is logged in
-   [ ] Correct route is being accessed

---

## Contact & Support

If issues persist after following this guide:

1. Check `storage/logs/laravel.log` for detailed errors
2. Enable debug mode in `.env`
3. Verify all file modifications are saved
4. Restart Laravel server
5. Run comprehensive test: `php test-livewire.php`

---

**Last Updated:** 7 Januari 2026
**Version:** 1.0.0
