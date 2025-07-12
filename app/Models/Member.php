<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'phone',
        'status',
        'invited_by',
        'cell_id',
        'fold_id',
        'first_visit_date',
        'times_attended',
        'invitees_count',
        'planters_count',
        'date_converted',
        'last_attended',
        'notes',
    ];

    public function inviter()
    {
        return $this->belongsTo(Member::class, 'invited_by');
    }

    public function invitees()
    {
        return $this->hasMany(\App\Models\FirstTimer::class, 'invited_by');
    }

    public function getInviteesCountAttribute()
    {
        return $this->invitees()->count();
    }

    public function getPlantersCountAttribute()
    {
        return $this->invitees()->where('purpose', 'stay')->count();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }

    public function fold()
    {
        return $this->belongsTo(Fold::class);
    }

    public function ledCell()
    {
        return $this->hasOne(Cell::class, 'cell_leader_id');
    }

    public function assistantLedCell()
    {
        return $this->hasOne(Cell::class, 'assistant_leader_id');
    }

    public function ledFold()
    {
        return $this->hasOne(Fold::class, 'fold_leader_id');
    }

    public function assistantLedFold()
    {
        return $this->hasOne(Fold::class, 'assistant_leader_id');
    }
}
