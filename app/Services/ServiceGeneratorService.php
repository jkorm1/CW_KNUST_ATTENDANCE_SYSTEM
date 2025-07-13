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
     * Get all auto-generated services for the next N days (default 7)
     */
    public function getAllServices($days = 7)
    {
        $services = [];
        $today = Carbon::now();

        for ($i = 0; $i < $days; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->format('l');
            if ($dayOfWeek === 'Sunday') {
                $services[] = [
                    'id' => 'auto_' . $date->format('Y-m-d') . '_sunday',
                    'name' => 'Sunday Service',
                    'service_date' => $date->format('Y-m-d'),
                    'type' => 'regular',
                    'is_auto_generated' => true,
                    'day_of_week' => $dayOfWeek,
                    'service_number' => null,
                ];
            }
            if ($dayOfWeek === 'Wednesday') {
                $services[] = [
                    'id' => 'auto_' . $date->format('Y-m-d') . '_midweek',
                    'name' => 'Midweek Service',
                    'service_date' => $date->format('Y-m-d'),
                    'type' => 'regular',
                    'is_auto_generated' => true,
                    'day_of_week' => $dayOfWeek,
                    'service_number' => null,
                ];
            }
            if ($dayOfWeek === 'Friday') {
                $services[] = [
                    'id' => 'auto_' . $date->format('Y-m-d') . '_leaders',
                    'name' => 'Friday Leaders Meeting',
                    'service_date' => $date->format('Y-m-d'),
                    'type' => 'leaders',
                    'is_auto_generated' => true,
                    'day_of_week' => $dayOfWeek,
                    'service_number' => null,
                ];
            }
        }

        return $services;
    }

    /**
     * Get upcoming auto-generated services (after today) for the next N days (default 7)
     */
    public function getUpcomingServices($days = 7)
    {
        $today = Carbon::now();
        $services = $this->getAllServices($days);

        // Only return services with a date after today
        return array_filter($services, function ($service) use ($today) {
            return Carbon::parse($service['service_date'])->greaterThan($today);
        });
    }

    /**
     * Get auto-generated services for today
     */
    public function getTodayServices()
    {
        $today = Carbon::now()->format('Y-m-d');
        $services = $this->getAllServices(1); // Only today

        // Filter for services with today's date
        return array_filter($services, function ($service) use ($today) {
            return $service['service_date'] === $today;
        });
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