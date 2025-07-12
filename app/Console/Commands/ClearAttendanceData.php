<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;

class ClearAttendanceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:clear {--service= : Clear attendance for specific service ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear attendance data for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceId = $this->option('service');
        
        if ($serviceId) {
            $count = Attendance::where('service_id', $serviceId)->delete();
            $this->info("Cleared {$count} attendance records for service {$serviceId}");
        } else {
            $count = Attendance::count();
            Attendance::truncate();
            $this->info("Cleared all {$count} attendance records");
        }
        
        $this->info('Attendance data cleared successfully!');
    }
} 