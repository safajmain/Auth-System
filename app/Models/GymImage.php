<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymImage extends Model
{
    protected $table = 'gym_images';

    protected $primaryKey = 'image_id';

    public $timestamps = false;

    protected $fillable = [
        'gym_id',
        'image_path',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'gym_id');
    }
}
