<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use App\Services\InstrumentStatusService;

class PurchaseOrderObserver
{
    protected $statusService;

    public function __construct(InstrumentStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Handle the PurchaseOrder "created" event.
     */
    public function created(PurchaseOrder $purchaseOrder): void
    {
        $this->statusService->updateStatusOnPurchaseOrder($purchaseOrder, 'created');
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        // Nur reagieren, wenn spezifische Felder geändert wurden, die eine Statusänderung rechtfertigen
        if ($purchaseOrder->wasChanged('is_delivered') && $purchaseOrder->is_delivered) {
            $this->statusService->updateStatusOnPurchaseOrder($purchaseOrder, 'delivered');
        }
        
        // Reagiere auf received_at Änderungen (neues System)
        if ($purchaseOrder->wasChanged('received_at') && $purchaseOrder->received_at) {
            $this->statusService->updateStatusOnPurchaseOrder($purchaseOrder, 'delivered');
        }
    }
}
