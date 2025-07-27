<?php

namespace App\Console\Commands;

use App\Models\Container;
use App\Services\ContainerStatusService;
use Illuminate\Console\Command;

class UpdateContainerStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'containers:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update container statuses based on their instruments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating container statuses...');
        
        $containers = Container::where('is_active', true)->get();
        $bar = $this->output->createProgressBar($containers->count());
        
        foreach ($containers as $container) {
            $container->updateStatusBasedOnInstruments();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Container statuses updated successfully!');
        
        // Show statistics
        $complete = Container::where('status', 'complete')->count();
        $incomplete = Container::where('status', 'incomplete')->count();
        $outOfService = Container::where('status', 'out_of_service')->count();
        
        $this->table(['Status', 'Count'], [
            ['Complete', $complete],
            ['Incomplete', $incomplete],
            ['Out of Service', $outOfService],
        ]);
    }
}
