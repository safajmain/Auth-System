<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $table = 'user_preferences';

    protected $primaryKey = 'preference_id';

    protected $fillable = [
        'user_id',
        'location',
        'gender',
        'height_cm',
        'weight_kg',
        'fitness_goal',
        'max_budget',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
