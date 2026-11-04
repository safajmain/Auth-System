<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',

    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
    public function preferences()
    {
        return $this->hasOne(UserPreference::class, 'user_id', 'id');
    }
    public function availableTimes(): HasMany
    {
        return $this->hasMany(UserAvailableTime::class, 'user_id', 'id');
    }

    public function preferredActivities(): BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class,
            'user_preferred_activities',
            'user_id',
            'activity_id'
        );
    }
    public function preferredFacilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Facility::class,
            'user_preferred_facilities',
            'user_id',
            'facility_id'
        );
    }

    public function gyms(): HasMany
    {
        return $this->hasMany(Gym::class, 'owner_id', 'id');
    }

    public function favoriteGyms(): BelongsToMany
    {
        return $this->belongsToMany(
            Gym::class,
            'user_favorite_gyms',
            'user_id',
            'gym_id'
        )->withPivot('created_at');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(
            GymReview::class,
            'user_id',
            'id'
        );
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(
            AuditLog::class,
            'user_id',
            'id'
        );
    }


    public function reminders(): HasMany
    {
        return $this->hasMany(
            Reminder::class,
            'user_id',
            'id'
        );
    }
}
