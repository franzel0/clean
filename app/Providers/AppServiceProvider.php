<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use App\Observers\DefectReportObserver;
use App\Observers\PurchaseOrderObserver;

class AppServiceProvider extends ServiceProvider
{
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
        DefectReport::observe(DefectReportObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }
}
