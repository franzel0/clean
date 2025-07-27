<?php

namespace App\Services;

use App\Models\Container;
use App\Models\Instrument;

class ContainerStatusService
{
    /**
     * Update container status based on instruments
     */
    public static function updateContainerStatus(Container $container): void
    {
        $container->updateStatusBasedOnInstruments();
    }

    /**
     * Update status for all containers
     */
    public static function updateAllContainerStatuses(): void
    {
        Container::where('is_active', true)->each(function ($container) {
            $container->updateStatusBasedOnInstruments();
        });
    }

    /**
     * Update container status when instrument status changes
     */
    public static function updateContainerStatusForInstrument(Instrument $instrument): void
    {
        if ($instrument->currentContainer) {
            self::updateContainerStatus($instrument->currentContainer);
        }

        // Also update previous container if instrument was moved
        $previousContainerId = $instrument->getOriginal('current_container_id');
        if ($previousContainerId && $previousContainerId !== $instrument->current_container_id) {
            $previousContainer = Container::find($previousContainerId);
            if ($previousContainer) {
                self::updateContainerStatus($previousContainer);
            }
        }
    }
}
