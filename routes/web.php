<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Instruments\InstrumentsList;
use App\Livewire\Instruments\EditInstrument;
use App\Livewire\DefectReports\CreateDefectReport;
use App\Livewire\DefectReports\DefectReportsList;

Route::view('/', 'welcome');

// Redirect home to dashboard
Route::redirect('/home', '/dashboard')->name('home');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Instrumente
    Route::get('/instruments', InstrumentsList::class)->name('instruments.index');
    Route::get('/instruments/create', EditInstrument::class)->name('instruments.create');
    Route::get('/instruments/{instrument}/edit', EditInstrument::class)->name('instruments.edit');
    Route::get('/instruments/{instrument}', \App\Livewire\Instruments\ShowInstrument::class)->name('instruments.show');
    
    // Defektmeldungen
    Route::get('/defect-reports', DefectReportsList::class)->name('defect-reports.index');
    Route::get('/defect-reports/create', CreateDefectReport::class)->name('defect-reports.create');
    Route::get('/defect-reports/{report}', \App\Livewire\DefectReports\ShowDefectReport::class)->name('defect-reports.show');
    Route::get('/defect-reports/{report}/edit', \App\Livewire\DefectReports\EditDefectReport::class)->name('defect-reports.edit');
    
    // Bestellungen
    Route::get('/purchase-orders', \App\Livewire\PurchaseOrders\PurchaseOrdersList::class)->name('purchase-orders.index');
    Route::get('/purchase-orders/create', \App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)->name('purchase-orders.create');
    Route::get('/purchase-orders/{order}', \App\Livewire\PurchaseOrders\ShowPurchaseOrder::class)->name('purchase-orders.show');
    
    // Container
    Route::get('/containers', \App\Livewire\Containers\ContainersList::class)->name('containers.index');
    Route::get('/containers/create', \App\Livewire\Containers\CreateContainer::class)->name('containers.create');
    Route::get('/containers/{container}', \App\Livewire\Containers\ShowContainer::class)->name('containers.show');
    Route::get('/containers/{container}/edit', \App\Livewire\Containers\EditContainer::class)->name('containers.edit');
    
    // Movements
    Route::get('/movements', \App\Livewire\Movements\MovementsList::class)->name('movements.index');
    
    // Benutzerverwaltung (nur für Admins)
    Route::get('/users', \App\Livewire\Users\UsersIndex::class)->name('users.index');
    
    // App Settings (nur für Admins)
    Route::get('/app-settings', \App\Livewire\AppSettings\SettingsIndex::class)->name('app-settings.index');
    
    // Berichte
    Route::get('/reports', \App\Livewire\Reports\SimpleReports::class)->name('reports.index');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
