<?php

namespace App\Models;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'student_id',
        'provider',
        'amount',
        'currency',
        'status',
        'provider_reference',
        'checkout_url',
        'paid_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'provider' => PaymentProvider::class,
            'status' => PaymentStatus::class,
            'meta' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentIntent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PaymentIntent::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isSucceeded(): bool
    {
        return $this->status === PaymentStatus::Succeeded;
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::Pending;
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::Failed;
    }
}
