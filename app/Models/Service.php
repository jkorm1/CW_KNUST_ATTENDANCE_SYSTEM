<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'service_date', 
        'type'
    ];

    protected $casts = [
        'service_date' => 'date',
    ];
} 