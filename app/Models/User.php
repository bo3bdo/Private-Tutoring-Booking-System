<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function teacherBookings(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Booking::class,
            TeacherProfile::class,
            'user_id',
            'teacher_id',
            'id',
            'id'
        );
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function courseEnrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseEnrollment::class, 'student_id');
    }

    public function lessonProgress(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LessonProgress::class, 'student_id');
    }

    public function coursePurchases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CoursePurchase::class, 'student_id');
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function conversationsAsUserOne(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function conversationsAsUserTwo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }

    public function conversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->conversationsAsUserOne()->union($this->conversationsAsUserTwo()->toBase());
    }

    public function sentMessages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function resources(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function supportTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function assignedTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    public function ticketReplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicketReply::class);
    }

    public function totalUnreadMessagesCount(): int
    {
        $userId = $this->id;

        return \App\Models\Message::whereHas('conversation', function ($query) use ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                    ->orWhere('user_two_id', $userId);
            });
        })
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function totalUnreadSupportTicketsCount(): int
    {
        if ($this->isStudent()) {
            // For students, count tickets that are not closed (they can see updates)
            return $this->supportTickets()
                ->where('status', '!=', 'closed')
                ->count();
        }

        if ($this->isAdmin()) {
            // For admin, count open or in_progress tickets
            return \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])
                ->count();
        }

        return 0;
    }

    public function pendingBookingsCount(): int
    {
        if ($this->isStudent()) {
            // For students, count bookings awaiting payment or confirmed (upcoming)
            return $this->bookings()
                ->whereIn('status', [
                    \App\Enums\BookingStatus::AwaitingPayment->value,
                    \App\Enums\BookingStatus::Confirmed->value,
                ])
                ->where('start_at', '>', now())
                ->count();
        }

        if ($this->isTeacher()) {
            // For teachers, count pending bookings (awaiting payment or confirmed)
            return $this->teacherBookings()
                ->whereIn('status', [
                    \App\Enums\BookingStatus::AwaitingPayment->value,
                    \App\Enums\BookingStatus::Confirmed->value,
                ])
                ->where('start_at', '>', now())
                ->count();
        }

        return 0;
    }

    public function pendingReviewsCount(): int
    {
        if ($this->isAdmin()) {
            // For admin, count reviews that are not approved yet
            return \App\Models\Review::where('is_approved', false)
                ->count();
        }

        return 0;
    }

    public function isOnline(): bool
    {
        if (! $this->last_seen_at) {
            return false;
        }

        // Consider user online if they were active in the last 90 seconds
        // This gives a buffer for the 30-second update interval
        // Use abs() to ensure positive value regardless of order
        return abs($this->last_seen_at->diffInSeconds(now())) <= 90;
    }
}
