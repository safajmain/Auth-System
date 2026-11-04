<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymMembership extends Model
{
    protected $table = 'gym_memberships';

    protected $primaryKey = 'membership_id';

    public $timestamps = false;

    protected $fillable = [
        'gym_id',
        'duration_months',
        'price_jod',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(
            Gym::class,
            'gym_id',
            'gym_id'
        );
    }
}
