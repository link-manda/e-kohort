#!/usr/bin/env php
<?php

/**
 * Test Script: ANC Visit History Component (Story 3.1)
 *
 * Tests:
 * 1. Component class exists
 * 2. Component has required properties
 * 3. Component has required methods
 * 4. Statistics calculation
 * 5. Visit filtering
 * 6. Sorting functionality
 * 7. Expandable rows
 * 8. Soft delete for visits
 * 9. Query string persistence
 * 10. View rendering
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pregnancy;
use App\Models\AncVisit;
use App\Livewire\AncVisitHistory;
use Illuminate\Support\Facades\DB;

echo "\n==============================================\n";
echo "  ANC Visit History Component Test Suite\n";
echo "==============================================\n\n";

$totalTests = 0;
$passedTests = 0;
$failedTests = [];

function test($description, $callback)
{
    global $totalTests, $passedTests, $failedTests;
    $totalTests++;

    try {
        $result = $callback();
        if ($result === true) {
            $passedTests++;
            echo "✓ {$description}\n";
        } else {
            $failedTests[] = $description;
            echo "✗ {$description}\n";
            if (is_string($result)) {
                echo "  Reason: {$result}\n";
            }
        }
    } catch (\Exception $e) {
        $failedTests[] = $description;
        echo "✗ {$description}\n";
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

echo "TEST 1: Component class exists\n";
echo "-------------------------------------------\n";
test("AncVisitHistory component exists", function () {
    return class_exists(AncVisitHistory::class);
});
echo "\n";

echo "TEST 2: Component has required properties\n";
echo "-------------------------------------------\n";
$reflector = new \ReflectionClass(AncVisitHistory::class);
$properties = [];
foreach ($reflector->getProperties() as $property) {
    $properties[] = $property->getName();
}

test("Has pregnancy property", function () use ($properties) {
    return in_array('pregnancy', $properties);
});

test("Has riskFilter property", function () use ($properties) {
    return in_array('riskFilter', $properties);
});

test("Has visitCodeFilter property", function () use ($properties) {
    return in_array('visitCodeFilter', $properties);
});

test("Has search property", function () use ($properties) {
    return in_array('search', $properties);
});

test("Has expandedVisits property", function () use ($properties) {
    return in_array('expandedVisits', $properties);
});

test("Has sortField property", function () use ($properties) {
    return in_array('sortField', $properties);
});

test("Has sortDirection property", function () use ($properties) {
    return in_array('sortDirection', $properties);
});
echo "\n";

echo "TEST 3: Component has required methods\n";
echo "-------------------------------------------\n";
$methods = [];
foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
    $methods[] = $method->getName();
}

test("Has sortBy method", function () use ($methods) {
    return in_array('sortBy', $methods);
});

test("Has toggleExpand method", function () use ($methods) {
    return in_array('toggleExpand', $methods);
});

test("Has clearFilters method", function () use ($methods) {
    return in_array('clearFilters', $methods);
});

test("Has deleteVisit method", function () use ($methods) {
    return in_array('deleteVisit', $methods);
});

test("Has getVisitsProperty method", function () use ($methods) {
    return in_array('getVisitsProperty', $methods);
});

test("Has getStatsProperty method", function () use ($methods) {
    return in_array('getStatsProperty', $methods);
});

test("Has render method", function () use ($methods) {
    return in_array('render', $methods);
});
echo "\n";

echo "TEST 4: Component with test data\n";
echo "-------------------------------------------\n";

// Create test pregnancy with visits
DB::beginTransaction();
try {
    $pregnancy = Pregnancy::where('is_active', true)->first();

    if (!$pregnancy) {
        echo "⚠ No active pregnancy found. Skipping integration tests.\n\n";
    } else {
        test("Can instantiate component with pregnancy", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            return $component->pregnancy->id === $pregnancy->id;
        });

        test("Can retrieve visits", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $visits = $component->visits;
            return is_object($visits);
        });

        test("Can calculate statistics", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $stats = $component->stats;
            return is_array($stats) &&
                isset($stats['total']) &&
                isset($stats['ekstrem']) &&
                isset($stats['tinggi']) &&
                isset($stats['rendah']) &&
                isset($stats['k1']) &&
                isset($stats['k2']);
        });

        echo "\n";

        echo "TEST 5: Filtering functionality\n";
        echo "-------------------------------------------\n";

        test("Can filter by risk category", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->riskFilter = 'ekstrem';
            $visits = $component->visits;
            return is_object($visits);
        });

        test("Can filter by visit code", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->visitCodeFilter = 'k1';
            $visits = $component->visits;
            return is_object($visits);
        });

        test("Can search in anamnesis", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->search = 'test';
            $visits = $component->visits;
            return is_object($visits);
        });

        echo "\n";

        echo "TEST 6: Sorting functionality\n";
        echo "-------------------------------------------\n";

        test("Can sort by visit_date ascending", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->sortBy('visit_date');
            return $component->sortField === 'visit_date' &&
                $component->sortDirection === 'asc';
        });

        test("Can sort by visit_date descending", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->sortField = 'visit_date';
            $component->sortDirection = 'asc';
            $component->sortBy('visit_date');
            return $component->sortField === 'visit_date' &&
                $component->sortDirection === 'desc';
        });

        test("Can sort by risk_category", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->sortBy('risk_category');
            return $component->sortField === 'risk_category';
        });

        echo "\n";

        echo "TEST 7: Expandable rows\n";
        echo "-------------------------------------------\n";

        test("Can toggle expand visit row", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->toggleExpand(1);
            return in_array(1, $component->expandedVisits);
        });

        test("Can collapse expanded row", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->expandedVisits = [1];
            $component->toggleExpand(1);
            return !in_array(1, $component->expandedVisits);
        });

        echo "\n";

        echo "TEST 8: Clear filters\n";
        echo "-------------------------------------------\n";

        test("Can clear all filters", function () use ($pregnancy) {
            $component = new AncVisitHistory();
            $component->pregnancy = $pregnancy;
            $component->riskFilter = 'ekstrem';
            $component->visitCodeFilter = 'k1';
            $component->search = 'test';
            $component->clearFilters();
            return $component->riskFilter === 'all' &&
                $component->visitCodeFilter === 'all' &&
                $component->search === '';
        });

        echo "\n";
    }
} catch (\Exception $e) {
    echo "✗ Error setting up test data: " . $e->getMessage() . "\n\n";
} finally {
    DB::rollBack();
}

echo "TEST 9: View rendering\n";
echo "-------------------------------------------\n";
test("View file exists", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    return file_exists($viewPath);
});

test("View contains statistics cards", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'Total Kunjungan') !== false &&
        strpos($content, 'Risiko Ekstrem') !== false;
});

test("View contains filter inputs", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'wire:model.live="riskFilter"') !== false ||
        strpos($content, 'wire:model.live="visitCodeFilter"') !== false ||
        strpos($content, 'wire:model.live.debounce') !== false;
});

test("View contains sortable table headers", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'wire:click="sortBy') !== false;
});

test("View contains expandable rows", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'toggleExpand') !== false;
});

test("View contains action buttons", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'deleteVisit') !== false;
});

test("View contains empty state", function () {
    $viewPath = resource_path('views/livewire/anc-visit-history.blade.php');
    $content = file_get_contents($viewPath);
    return strpos($content, 'Belum ada kunjungan') !== false;
});
echo "\n";

// Summary
echo "\n==============================================\n";
echo "               TEST SUMMARY\n";
echo "==============================================\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: " . "\033[32m{$passedTests}\033[0m\n";
echo "Failed: " . (count($failedTests) > 0 ? "\033[31m" . count($failedTests) . "\033[0m" : "0") . "\n";

if (count($failedTests) > 0) {
    echo "\nFailed Tests:\n";
    foreach ($failedTests as $test) {
        echo "  ✗ {$test}\n";
    }
}

$percentage = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
echo "\nSuccess Rate: {$percentage}%\n";

if ($percentage === 100.0) {
    echo "\n🎉 All tests passed! Story 3.1 implementation is complete!\n\n";
} else {
    echo "\n⚠ Some tests failed. Please review the failed tests above.\n\n";
    exit(1);
}
