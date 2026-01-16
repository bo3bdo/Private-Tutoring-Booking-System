<?php

namespace App\Models;

use App\Enums\PaymentIntentPurpose;
use App\Enums\PaymentProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentIntent extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentIntentFactory> */
    use HasFactory;

    protected $table = 'payment_intents';

    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'purpose',
        'course_id',
        'student_id',
        'provider',
        'provider_reference',
    ];

    protected function casts(): array
    {
        return [
            'purpose' => PaymentIntentPurpose::class,
            'provider' => PaymentProvider::class,
            'created_at' => 'datetime',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
