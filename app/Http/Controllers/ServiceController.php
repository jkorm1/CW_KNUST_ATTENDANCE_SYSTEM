<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\ServiceGeneratorService;
use Carbon\Carbon;

class ServiceController extends Controller
{
    protected $serviceGenerator;

    public function __construct(ServiceGeneratorService $serviceGenerator)
    {
        $this->serviceGenerator = $serviceGenerator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get manually created services and map to array structure
        $manualServices = Service::orderBy('service_date', 'desc')->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'service_date' => $service->service_date, // use correct column
                'type' => $service->type ?? 'Manual',
                'is_auto_generated' => false,
                'day_of_week' => \Carbon\Carbon::parse($service->service_date)->format('l'),
                'service_number' => null,
            ];
        })->keyBy(function ($service) {
            return $service['name'] . '_' . $service['service_date'];
        });

        // Get auto-generated services for the next 7 days
        $autoServices = collect($this->serviceGenerator->getAllServices(7))->keyBy(function ($service) {
            return $service['name'] . '_' . $service['service_date'];
        });
        
        // Merge the two collections, with manual services overwriting auto-generated ones
        $allServices = $autoServices->merge($manualServices);

        // Sort the final collection by date
        $allServices = $allServices->sortBy('service_date');

        return view('services.index', compact('allServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
