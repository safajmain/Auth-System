<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    protected $table = 'activities';

    protected $primaryKey = 'activity_id';

    public $timestamps = false;

    protected $fillable = [
        'activity_name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_preferred_activities',
            'activity_id',
            'user_id'
        );
    }

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(
            Gym::class,
            'gym_activities',
            'activity_id',
            'gym_id'
        );
    }
}
