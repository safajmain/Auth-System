<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymVerificationDocument extends Model
{
    protected $table = 'gym_verification_documents';

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'gym_id',
        'document_type',
        'file_path',
        'verification_status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(
            Gym::class,
            'gym_id',
            'gym_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by',
            'id'
        );
    }
}
