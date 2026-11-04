<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    protected $table = 'facilities';

    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'facility_name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_preferred_facilities',
            'facility_id',
            'user_id'
        );
    }
    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(
            Gym::class,
            'gym_facilities',
            'facility_id',
            'gym_id'
        );
    }
}
