<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get cells where this user is the main leader
     */
    public function ledCells()
    {
        return $this->hasMany(Cell::class, 'cell_leader_id');
    }

    /**
     * Get cells where this user is the assistant leader
     */
    public function assistedCells()
    {
        return $this->hasMany(Cell::class, 'assistant_leader_id');
    }

    /**
     * Get all cells this user leads (main or assistant)
     */
    public function allLedCells()
    {
        return Cell::where('cell_leader_id', $this->id)
            ->orWhere('assistant_leader_id', $this->id);
    }

    /**
     * Get folds where this user is the main leader
     */
    public function ledFolds()
    {
        return $this->hasMany(Fold::class, 'fold_leader_id');
    }

    /**
     * Get folds where this user is the assistant leader
     */
    public function assistedFolds()
    {
        return $this->hasMany(Fold::class, 'assistant_leader_id');
    }

    /**
     * Get all folds this user leads (main or assistant)
     */
    public function allLedFolds()
    {
        return Fold::where('fold_leader_id', $this->id)
            ->orWhere('assistant_leader_id', $this->id);
    }

    /**
     * Check if user is a cell leader
     */
    public function isCellLeader()
    {
        return $this->ledCells()->exists() || $this->assistedCells()->exists();
    }

    /**
     * Check if user is a fold leader
     */
    public function isFoldLeader()
    {
        return $this->ledFolds()->exists() || $this->assistedFolds()->exists();
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is an apostle/church leader
     */
    public function isApostle()
    {
        return $this->hasRole('apostle');
    }

    /**
     * Get the cell this user leads (if any)
     */
    public function getLedCell()
    {
        return $this->ledCells()->first() ?? $this->assistedCells()->first();
    }

    /**
     * Get the fold this user leads (if any)
     */
    public function getLedFold()
    {
        return $this->ledFolds()->first() ?? $this->assistedFolds()->first();
    }

    /**
     * Get members this user can manage based on their role
     */
    public function getManageableMembers()
    {
        if ($this->isAdmin() || $this->isApostle()) {
            return Member::query();
        }

        if ($this->isCellLeader()) {
            $cell = $this->getLedCell();
            return $cell ? $cell->members() : Member::whereNull('id');
        }

        if ($this->isFoldLeader()) {
            $fold = $this->getLedFold();
            return $fold ? $fold->members() : Member::whereNull('id');
        }

        return Member::whereNull('id'); // No members if no leadership role
    }

    /**
     * Get attendance records this user can manage
     */
    public function getManageableAttendances()
    {
        if ($this->isAdmin() || $this->isApostle()) {
            return Attendance::all();
        }

        if ($this->isCellLeader()) {
            $cell = $this->getLedCell();
            return $cell ? $cell->attendances() : Attendance::whereNull('id');
        }

        if ($this->isFoldLeader()) {
            $fold = $this->getLedFold();
            return $fold ? $fold->attendances() : Attendance::whereNull('id');
        }

        return Attendance::whereNull('id'); // No attendances if no leadership role
    }
}
