<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\ServiceGeneratorService;

class FirstTimer extends Model
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'phone',
        'residence',
        'purpose',
        'invited_by',
        'cell_id',
        'fold_id',
        'first_visit_date',
        'service_id',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'first_visit_date' => 'date',
    ];

    public function inviter()
    {
        return $this->belongsTo(Member::class, 'invited_by');
    }

    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }

    public function fold()
    {
        return $this->belongsTo(Fold::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the service name, handling both auto-generated and database services
     */
    public function getServiceNameAttribute()
    {
        if (str_starts_with($this->service_id, 'auto_')) {
            $serviceGenerator = app(ServiceGeneratorService::class);
            $parsedService = $serviceGenerator->parseServiceId($this->service_id);
            return $parsedService ? $parsedService['name'] : 'Unknown Service';
        } else {
            return $this->service ? $this->service->name : 'Unknown Service';
        }
    }
}
