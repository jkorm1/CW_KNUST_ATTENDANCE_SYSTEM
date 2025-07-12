<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'first_timer_id',
        'service_id',
        'fold_id',
        'present',
        'status',
        'notes',
        'submitted_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'present' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the member this attendance record belongs to
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the service this attendance record is for
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the fold this attendance record belongs to
     */
    public function fold()
    {
        return $this->belongsTo(Fold::class);
    }

    /**
     * Get the user who submitted this attendance
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the user who approved this attendance
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the first timer this attendance record belongs to
     */
    public function firstTimer()
    {
        return $this->belongsTo(FirstTimer::class);
    }

    /**
     * Scope to get pending attendance records
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved attendance records
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected attendance records
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get present members
     */
    public function scopePresent($query)
    {
        return $query->where('present', true);
    }

    /**
     * Scope to get absent members
     */
    public function scopeAbsent($query)
    {
        return $query->where('present', false);
    }

    /**
     * Check if attendance is pending approval
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if attendance is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if attendance is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve this attendance record
     */
    public function approve(User $approver)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject this attendance record
     */
    public function reject(User $rejecter, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejecter->id,
            'approved_at' => now(),
            'notes' => $notes,
        ]);
    }
}
