<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ServiceGeneratorService;

class GenerateWeeklyServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:generate-weekly {--week=current : Which week to generate (current, next, or specific date)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate weekly services for the church';

    /**
     * Execute the console command.
     */
    public function handle(ServiceGeneratorService $serviceGenerator)
    {
        $week = $this->option('week');
        
        $this->info('Generating weekly services...');
        
        switch ($week) {
            case 'current':
                $services = $serviceGenerator->generateWeeklyServices();
                $this->info('Generated services for current week');
                break;
                
            case 'next':
                $services = $serviceGenerator->generateNextWeekServices();
                $this->info('Generated services for next week');
                break;
                
            default:
                // Assume it's a date
                try {
                    $services = $serviceGenerator->generateServicesForWeek($week);
                    $this->info("Generated services for week starting: {$week}");
                } catch (\Exception $e) {
                    $this->error("Invalid date format: {$week}");
                    return 1;
                }
                break;
        }
        
        // Clean up old services
        $serviceGenerator->cleanupOldServices();
        $this->info('Cleaned up old services (older than 4 weeks)');
        
        $this->info('Weekly services generated successfully!');
        
        return 0;
    }
}
