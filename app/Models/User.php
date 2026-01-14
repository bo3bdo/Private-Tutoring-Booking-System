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
    use HasFactory, Notifiable, HasRoles;

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
}
