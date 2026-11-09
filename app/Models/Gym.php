<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gym extends Model
{
    protected $table = 'gyms';

    protected $primaryKey = 'gym_id';

    protected $fillable = [
        'owner_id',
        'gym_name',
        'description',
        'city',
        'area',
        'address',
        'latitude',
        'longitude',
        'phone',
        'website_url',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
    public function images(): HasMany
    {
        return $this->hasMany(GymImage::class, 'gym_id', 'gym_id');
    }


    public function verificationDocuments(): HasMany
    {
        return $this->hasMany(
            GymVerificationDocument::class,
            'gym_id',
            'gym_id'
        );
    }


    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_favorite_gyms',
            'gym_id',
            'user_id'
        )->withPivot('created_at');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Facility::class,
            'gym_facilities',
            'gym_id',
            'facility_id'
        );
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(
            Activity::class,
            'gym_activities',
            'gym_id',
            'activity_id'
        );
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(
            GymMembership::class,
            'gym_id',
            'gym_id'
        );
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(
            GymWorkingHour::class,
            'gym_id',
            'gym_id'
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(
            GymReview::class,
            'gym_id',
            'gym_id'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'gym_id';
    }
}
