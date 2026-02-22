<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use App\Models\KbVisit;
use App\Models\DeliveryRecord;
use App\Models\GeneralVisit;
use App\Models\ChildGrowthRecord;
use App\Policies\PatientPolicy;
use App\Policies\PregnancyPolicy;
use App\Policies\AncVisitPolicy;
use App\Policies\KbVisitPolicy;
use App\Policies\GeneralVisitPolicy;
use App\Policies\ChildGrowthRecordPolicy;
use App\Observers\DeliveryRecordObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Patient::class => PatientPolicy::class,
        Pregnancy::class => PregnancyPolicy::class,
        AncVisit::class => AncVisitPolicy::class,
        KbVisit::class => KbVisitPolicy::class,
        GeneralVisit::class => GeneralVisitPolicy::class,
        ChildGrowthRecord::class => ChildGrowthRecordPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prevent lazy loading in development to catch N+1 queries early
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->isProduction());

        // Register Observer untuk auto-create child & HB0
        DeliveryRecord::observe(DeliveryRecordObserver::class);

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
