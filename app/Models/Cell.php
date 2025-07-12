<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'cell_leader_id',
        'assistant_leader_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the main cell leader
     */
    public function cellLeader()
    {
        return $this->belongsTo(User::class, 'cell_leader_id');
    }

    /**
     * Get the assistant cell leader
     */
    public function assistantLeader()
    {
        return $this->belongsTo(User::class, 'assistant_leader_id');
    }

    /**
     * Get all folds in this cell
     */
    public function folds()
    {
        return $this->hasMany(Fold::class);
    }

    /**
     * Get all members in this cell
     */
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get all attendance records for this cell
     */
    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Fold::class);
    }

    /**
     * Get active folds only
     */
    public function activeFolds()
    {
        return $this->folds()->where('is_active', true);
    }

    /**
     * Get active members only
     */
    public function activeMembers()
    {
        return $this->members()->where('status', 'active');
    }

    /**
     * Check if a user is a leader of this cell
     */
    public function isLeader(User $user)
    {
        return $user->id === $this->cell_leader_id || $user->id === $this->assistant_leader_id;
    }

    /**
     * Get attendance statistics for a specific service
     */
    public function getAttendanceStats($serviceId)
    {
        $totalMembers = $this->activeMembers()->count();
        $presentCount = $this->attendances()
            ->where('service_id', $serviceId)
            ->where('present', true)
            ->where('status', 'approved')
            ->count();
        $absentCount = $this->attendances()
            ->where('service_id', $serviceId)
            ->where('present', false)
            ->where('status', 'approved')
            ->count();

        return [
            'total_members' => $totalMembers,
            'present' => $presentCount,
            'absent' => $absentCount,
            'unmarked' => $totalMembers - $presentCount - $absentCount,
            'attendance_rate' => $totalMembers > 0 ? round(($presentCount / $totalMembers) * 100, 2) : 0,
        ];
    }
} 