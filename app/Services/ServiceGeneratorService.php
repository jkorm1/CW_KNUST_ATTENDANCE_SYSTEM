<?php

namespace App\Services;

use App\Models\Service;
use Carbon\Carbon;

class ServiceGeneratorService
{
    /**
     * Generate services for the current week
     */
    public function generateWeeklyServices()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        
        // Generate services for the week
        $services = [
            [
                'name' => 'Sunday Service',
                'service_date' => $startOfWeek->copy()->addDays(0)->format('Y-m-d'), // Sunday
                'type' => 'regular',
            ],
            [
                'name' => 'Midweek Service',
                'service_date' => $startOfWeek->copy()->addDays(3)->format('Y-m-d'), // Wednesday
                'type' => 'regular',
            ],
            [
                'name' => 'Friday Leaders Meeting',
                'service_date' => $startOfWeek->copy()->addDays(5)->format('Y-m-d'), // Friday
                'type' => 'leaders',
            ],
        ];

        foreach ($services as $serviceData) {
            // Only create if it doesn't already exist for that date
            Service::firstOrCreate(
                ['service_date' => $serviceData['service_date']],
                $serviceData
            );
        }

        return $services;
    }

    /**
     * Generate services for the next week
     */
    public function generateNextWeekServices()
    {
        $nextWeek = Carbon::now()->addWeek()->startOfWeek();
        
        $services = [
            [
                'name' => 'Sunday Service',
                'service_date' => $nextWeek->copy()->addDays(0)->format('Y-m-d'), // Sunday
                'type' => 'regular',
            ],
            [
                'name' => 'Midweek Service',
                'service_date' => $nextWeek->copy()->addDays(3)->format('Y-m-d'), // Wednesday
                'type' => 'regular',
            ],
            [
                'name' => 'Friday Leaders Meeting',
                'service_date' => $nextWeek->copy()->addDays(5)->format('Y-m-d'), // Friday
                'type' => 'leaders',
            ],
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['service_date' => $serviceData['service_date']],
                $serviceData
            );
        }

        return $services;
    }

    /**
     * Generate services for a specific week
     */
    public function generateServicesForWeek($date)
    {
        $weekStart = Carbon::parse($date)->startOfWeek();
        
        $services = [
            [
                'name' => 'Sunday Service',
                'service_date' => $weekStart->copy()->addDays(0)->format('Y-m-d'),
                'type' => 'regular',
            ],
            [
                'name' => 'Midweek Service',
                'service_date' => $weekStart->copy()->addDays(3)->format('Y-m-d'),
                'type' => 'regular',
            ],
            [
                'name' => 'Friday Leaders Meeting',
                'service_date' => $weekStart->copy()->addDays(5)->format('Y-m-d'),
                'type' => 'leaders',
            ],
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['service_date' => $serviceData['service_date']],
                $serviceData
            );
        }

        return $services;
    }

    /**
     * Clean up old services (older than 4 weeks)
     */
    public function cleanupOldServices()
    {
        $fourWeeksAgo = Carbon::now()->subWeeks(4);
        
        Service::where('service_date', '<', $fourWeeksAgo)->delete();
    }
} 