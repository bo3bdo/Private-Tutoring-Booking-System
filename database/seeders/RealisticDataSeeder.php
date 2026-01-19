<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\CoursePurchase;
use App\Models\LessonProgress;
use App\Models\Location;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Resource;
use App\Models\Review;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\TeacherAvailability;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealisticDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Subjects with detailed descriptions
        $subjects = [
            [
                'name' => 'Mathematics',
                'description' => 'Comprehensive mathematics tutoring covering algebra, geometry, calculus, and statistics',
                'is_active' => true,
            ],
            [
                'name' => 'Physics',
                'description' => 'Physics lessons including mechanics, thermodynamics, electromagnetism, and modern physics',
                'is_active' => true,
            ],
            [
                'name' => 'Chemistry',
                'description' => 'Chemistry tutoring covering organic, inorganic, and physical chemistry',
                'is_active' => true,
            ],
            [
                'name' => 'English Language',
                'description' => 'English language learning including grammar, writing, reading comprehension, and conversation',
                'is_active' => true,
            ],
            [
                'name' => 'Arabic Language',
                'description' => 'Arabic language instruction covering grammar, literature, and conversation skills',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Science',
                'description' => 'Programming, algorithms, data structures, and computer fundamentals',
                'is_active' => true,
            ],
            [
                'name' => 'Biology',
                'description' => 'Life sciences including cell biology, genetics, ecology, and human anatomy',
                'is_active' => true,
            ],
            [
                'name' => 'History',
                'description' => 'World history, ancient civilizations, and modern historical events',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['name' => $subject['name']], $subject);
        }

        // Create realistic locations in Bahrain
        $locations = [
            [
                'name' => 'Manama Education Center',
                'address' => 'Building 123, Road 2811, Block 428, Manama, Bahrain',
                'is_active' => true,
            ],
            [
                'name' => 'Riffa Learning Hub',
                'address' => 'Avenue 45, Block 912, East Riffa, Bahrain',
                'is_active' => true,
            ],
            [
                'name' => 'Muharraq Academic Center',
                'address' => 'Street 2614, Block 226, Muharraq, Bahrain',
                'is_active' => true,
            ],
            [
                'name' => 'Isa Town Knowledge Center',
                'address' => 'Road 5138, Block 520, Isa Town, Bahrain',
                'is_active' => true,
            ],
            [
                'name' => 'Sitra Learning Institute',
                'address' => 'Building 456, Road 3523, Sitra, Bahrain',
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(['name' => $location['name']], $location);
        }

        // Create realistic teachers with Arabic and English names
        $teachers = [
            [
                'name' => 'Dr. Ahmed Al-Khalifa',
                'email' => 'ahmed.alkhalifa@example.com',
                'bio' => 'PhD in Mathematics with 15 years of teaching experience. Specialized in calculus and algebra for university entrance preparation. Qualifications: PhD Mathematics - University of Bahrain, MSc Applied Mathematics.',
                'hourly_rate' => 35.00,
                'subjects' => ['Mathematics', 'Physics'],
            ],
            [
                'name' => 'Prof. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'bio' => 'Native English speaker with TEFL certification. 10+ years teaching English to Arabic speakers of all ages. Qualifications: MA TESOL - Oxford University, CELTA Certified.',
                'hourly_rate' => 30.00,
                'subjects' => ['English Language'],
            ],
            [
                'name' => 'Dr. Mohammed Al-Mannai',
                'email' => 'mohammed.almannai@example.com',
                'bio' => 'Chemical Engineer and experienced chemistry tutor. Helping students excel in GCSE, A-Levels, and IB Chemistry. Qualifications: PhD Chemical Engineering - Imperial College London.',
                'hourly_rate' => 32.00,
                'subjects' => ['Chemistry', 'Physics'],
            ],
            [
                'name' => 'Fatima Al-Zayani',
                'email' => 'fatima.alzayani@example.com',
                'bio' => 'Experienced Arabic language teacher specializing in classical and modern standard Arabic. Expert in Arabic literature. Qualifications: MA Arabic Literature - Cairo University.',
                'hourly_rate' => 28.00,
                'subjects' => ['Arabic Language', 'History'],
            ],
            [
                'name' => 'Eng. Khalid Hassan',
                'email' => 'khalid.hassan@example.com',
                'bio' => 'Software Engineer with passion for teaching. Specializing in Python, Java, and web development for beginners. Qualifications: BSc Computer Science - King Fahd University, AWS Certified.',
                'hourly_rate' => 40.00,
                'subjects' => ['Computer Science'],
            ],
            [
                'name' => 'Dr. Amira Al-Dosari',
                'email' => 'amira.aldosari@example.com',
                'bio' => 'Medical doctor with expertise in biology education. Making complex biological concepts easy to understand. Qualifications: MBBS - Arabian Gulf University, MSc Molecular Biology.',
                'hourly_rate' => 33.00,
                'subjects' => ['Biology', 'Chemistry'],
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@example.com',
                'bio' => 'International school teacher with 12 years experience. Expert in IGCSE and A-Level Physics and Mathematics. Qualifications: BSc Physics - Cambridge University, QTS UK.',
                'hourly_rate' => 38.00,
                'subjects' => ['Physics', 'Mathematics'],
            ],
            [
                'name' => 'Maryam Al-Khalili',
                'email' => 'maryam.alkhalili@example.com',
                'bio' => 'History teacher passionate about Middle Eastern and World history. Engaging lessons with real-world connections. Qualifications: MA History - University of Jordan.',
                'hourly_rate' => 26.00,
                'subjects' => ['History', 'Arabic Language'],
            ],
        ];

        $createdTeachers = [];
        foreach ($teachers as $teacherData) {
            $user = User::firstOrCreate(
                ['email' => $teacherData['email']],
                [
                    'name' => $teacherData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now()->subDays(rand(30, 365)),
                ]
            );

            if (! $user->hasRole('teacher')) {
                $user->assignRole('teacher');
            }

            $location = Location::inRandomOrder()->first();

            $profile = TeacherProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $teacherData['bio'],
                    'hourly_rate' => $teacherData['hourly_rate'],
                    'is_active' => true,
                    'supports_online' => true,
                    'supports_in_person' => rand(0, 1) === 1,
                    'default_location_id' => $location?->id,
                    'default_meeting_provider' => 'zoom',
                ]
            );

            $subjectIds = Subject::whereIn('name', $teacherData['subjects'])->pluck('id');
            $profile->subjects()->sync($subjectIds);

            $createdTeachers[] = $user;

            // Create teacher availability (Mon-Fri 9am-6pm)
            for ($day = 1; $day <= 5; $day++) {
                TeacherAvailability::firstOrCreate([
                    'teacher_id' => $profile->id,
                    'weekday' => $day,
                    'start_time' => '09:00',
                    'end_time' => '18:00',
                    'is_active' => true,
                ]);
            }

            // Add some time slots for upcoming days
            for ($i = 1; $i <= 14; $i++) {
                $date = now()->addDays($i);
                if ($date->dayOfWeek >= 1 && $date->dayOfWeek <= 5) {
                    // Morning slots
                    TimeSlot::create([
                        'teacher_id' => $profile->id,
                        'start_at' => $date->copy()->setTime(9, 0),
                        'end_at' => $date->copy()->setTime(10, 30),
                        'status' => rand(0, 3) !== 0 ? 'available' : 'blocked', // 75% available
                    ]);

                    TimeSlot::create([
                        'teacher_id' => $profile->id,
                        'start_at' => $date->copy()->setTime(11, 0),
                        'end_at' => $date->copy()->setTime(12, 30),
                        'status' => rand(0, 3) !== 0 ? 'available' : 'blocked',
                    ]);

                    // Afternoon slots
                    TimeSlot::create([
                        'teacher_id' => $profile->id,
                        'start_at' => $date->copy()->setTime(14, 0),
                        'end_at' => $date->copy()->setTime(15, 30),
                        'status' => rand(0, 3) !== 0 ? 'available' : 'blocked',
                    ]);

                    TimeSlot::create([
                        'teacher_id' => $profile->id,
                        'start_at' => $date->copy()->setTime(16, 0),
                        'end_at' => $date->copy()->setTime(17, 30),
                        'status' => rand(0, 3) !== 0 ? 'available' : 'blocked',
                    ]);
                }
            }
        }

        // Create realistic students
        $students = [
            ['name' => 'Ali Al-Mannai', 'email' => 'ali.almannai@example.com', 'phone' => '+97333123456', 'age' => 16],
            ['name' => 'Layla Hassan', 'email' => 'layla.hassan@example.com', 'phone' => '+97333234567', 'age' => 14],
            ['name' => 'Omar Al-Khalifa', 'email' => 'omar.alkhalifa@example.com', 'phone' => '+97333345678', 'age' => 17],
            ['name' => 'Noor Al-Dosari', 'email' => 'noor.aldosari@example.com', 'phone' => '+97333456789', 'age' => 15],
            ['name' => 'Hassan Mohammed', 'email' => 'hassan.mohammed@example.com', 'phone' => '+97333567890', 'age' => 18],
            ['name' => 'Mariam Al-Zayani', 'email' => 'mariam.alzayani@example.com', 'phone' => '+97333678901', 'age' => 13],
            ['name' => 'Abdullah Al-Kuwari', 'email' => 'abdullah.alkuwari@example.com', 'phone' => '+97333789012', 'age' => 16],
            ['name' => 'Sara Al-Mahmood', 'email' => 'sara.almahmood@example.com', 'phone' => '+97333890123', 'age' => 15],
            ['name' => 'Yousef Al-Ansari', 'email' => 'yousef.alansari@example.com', 'phone' => '+97333901234', 'age' => 17],
            ['name' => 'Huda Al-Khalili', 'email' => 'huda.alkhalili@example.com', 'phone' => '+97333012345', 'age' => 14],
            ['name' => 'Khalid Al-Thani', 'email' => 'khalid.althani@example.com', 'phone' => '+97334123456', 'age' => 16],
            ['name' => 'Aisha Mohammed', 'email' => 'aisha.mohammed@example.com', 'phone' => '+97334234567', 'age' => 18],
            ['name' => 'Fahad Al-Malki', 'email' => 'fahad.almalki@example.com', 'phone' => '+97334345678', 'age' => 15],
            ['name' => 'Reem Al-Sulaiti', 'email' => 'reem.alsulaiti@example.com', 'phone' => '+97334456789', 'age' => 14],
            ['name' => 'Hamad Al-Baker', 'email' => 'hamad.albaker@example.com', 'phone' => '+97334567890', 'age' => 17],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now()->subDays(rand(10, 200)),
                ]
            );

            if (! $user->hasRole('student')) {
                $user->assignRole('student');
            }

            StudentProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $studentData['phone'],
                ]
            );

            $createdStudents[] = $user;
        }

        // Create realistic bookings (past and upcoming)
        $bookingCounter = 0;
        foreach ($createdStudents as $student) {
            // Create 2-5 bookings per student
            $numBookings = rand(2, 5);

            for ($i = 0; $i < $numBookings; $i++) {
                $teacherUser = $createdTeachers[array_rand($createdTeachers)];
                $teacherProfile = $teacherUser->teacherProfile;

                if (! $teacherProfile) {
                    continue;
                }

                $teacherSubjects = $teacherProfile->subjects;

                if ($teacherSubjects->isEmpty()) {
                    continue;
                }

                $subject = $teacherSubjects->random();
                $isPast = $i < ($numBookings - 2); // Most bookings are in the past
                $status = $isPast ? ($i % 5 === 0 ? 'cancelled' : 'completed') : 'confirmed';

                $lessonMode = $teacherProfile->supports_in_person && rand(0, 2) === 0
                    ? 'in_person'
                    : 'online';

                $startDate = $isPast
                    ? now()->subDays(rand(1, 90))
                    : now()->addDays(rand(15, 30));

                // Add unique minutes based on booking counter to avoid conflicts
                $startAt = $startDate->copy()->setTime(rand(9, 17), 0)->addMinutes($bookingCounter % 60);
                $endAt = $startAt->copy()->addMinutes(90);
                $bookingCounter++;

                // Create or find a time slot for this booking
                $timeSlot = TimeSlot::firstOrCreate(
                    [
                        'teacher_id' => $teacherProfile->id,
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                    ],
                    [
                        'subject_id' => $subject->id,
                        'status' => 'booked',
                    ]
                );

                // Update status if it exists
                if ($timeSlot->status !== 'booked') {
                    $timeSlot->update(['status' => 'booked']);
                }

                $booking = Booking::create([
                    'student_id' => $student->id,
                    'teacher_id' => $teacherProfile->id,
                    'subject_id' => $subject->id,
                    'time_slot_id' => $timeSlot->id,
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                    'lesson_mode' => $lessonMode,
                    'status' => $status,
                    'location_id' => $lessonMode === 'in_person' ? Location::inRandomOrder()->first()?->id : null,
                    'meeting_provider' => $lessonMode === 'online' ? 'zoom' : 'none',
                    'meeting_url' => $lessonMode === 'online' ? 'https://zoom.us/j/'.rand(1000000000, 9999999999) : null,
                    'notes' => $i % 3 === 0 ? 'Focus on exam preparation for upcoming tests.' : null,
                    'cancelled_at' => $status === 'cancelled' ? $startAt->copy()->subHours(2) : null,
                    'completed_at' => $status === 'completed' ? $endAt : null,
                    'cancellation_reason' => $status === 'cancelled' ? 'Student had emergency' : null,
                ]);

                // Create payment for completed and confirmed bookings
                if ($status !== 'cancelled') {
                    $hourlyRate = $teacherProfile->hourly_rate;
                    $amount = ($hourlyRate * 90) / 60; // 1.5 hours

                    $provider = ['stripe', 'benefitpay'][rand(0, 1)];
                    $paymentStatus = $status === 'completed' ? 'succeeded' : 'pending';

                    Payment::create([
                        'booking_id' => $booking->id,
                        'student_id' => $student->id,
                        'provider' => $provider,
                        'amount' => $amount,
                        'currency' => 'BHD',
                        'status' => $paymentStatus,
                        'provider_reference' => strtoupper($provider).'_'.strtoupper(substr(md5(uniqid()), 0, 12)),
                        'paid_at' => $paymentStatus === 'succeeded' ? $booking->completed_at ?? now() : null,
                    ]);
                }

                // Create review for some completed bookings
                if ($status === 'completed' && rand(0, 2) === 0) {
                    Review::create([
                        'user_id' => $student->id,
                        'reviewable_id' => $booking->id,
                        'reviewable_type' => 'App\Models\Booking',
                        'rating' => rand(3, 5),
                        'comment' => [
                            'Excellent teacher! Very patient and explains concepts clearly.',
                            'Great session, learned a lot. Highly recommended!',
                            'Very knowledgeable and helpful. Looking forward to next session.',
                            'Good teacher but would prefer more practice exercises.',
                            'Amazing experience! My grades improved significantly.',
                        ][rand(0, 4)],
                        'is_approved' => true,
                        'approved_at' => now(),
                    ]);
                }
            }
        }

        // Create realistic courses
        $courseData = [
            [
                'subject' => 'Mathematics',
                'title' => 'Complete Algebra Mastery',
                'description' => 'Master algebra from basics to advanced topics. Perfect for students preparing for IGCSE and A-Levels. Covers equations, inequalities, functions, and more.',
                'price' => 45.00,
                'lessons' => [
                    ['title' => 'Introduction to Algebra', 'summary' => 'Understanding variables, expressions, and basic operations', 'duration' => 1200],
                    ['title' => 'Solving Linear Equations', 'summary' => 'Step-by-step approach to solving linear equations', 'duration' => 1500],
                    ['title' => 'Quadratic Equations', 'summary' => 'Factoring, completing the square, and quadratic formula', 'duration' => 1800],
                    ['title' => 'Functions and Graphs', 'summary' => 'Understanding functions, domain, range, and graphing', 'duration' => 1600],
                    ['title' => 'Systems of Equations', 'summary' => 'Solving systems using substitution and elimination', 'duration' => 1400],
                ],
            ],
            [
                'subject' => 'Physics',
                'title' => 'Physics Fundamentals: Mechanics',
                'description' => 'Comprehensive course on classical mechanics. Learn Newton\'s laws, energy, momentum, and motion through engaging video lessons and practice problems.',
                'price' => 50.00,
                'lessons' => [
                    ['title' => 'Introduction to Physics', 'summary' => 'Understanding measurement, units, and scientific method', 'duration' => 900],
                    ['title' => 'Motion in One Dimension', 'summary' => 'Displacement, velocity, and acceleration explained', 'duration' => 1200],
                    ['title' => 'Newton\'s Laws of Motion', 'summary' => 'The three fundamental laws that govern motion', 'duration' => 1500],
                    ['title' => 'Work, Energy, and Power', 'summary' => 'Understanding energy transfer and conservation', 'duration' => 1400],
                    ['title' => 'Momentum and Collisions', 'summary' => 'Conservation of momentum and collision analysis', 'duration' => 1300],
                    ['title' => 'Circular Motion', 'summary' => 'Centripetal force and angular velocity', 'duration' => 1200],
                ],
            ],
            [
                'subject' => 'English Language',
                'title' => 'English Grammar and Writing Skills',
                'description' => 'Improve your English grammar, writing, and communication skills. Covers all essential topics from basic to advanced level with practical exercises.',
                'price' => 35.00,
                'lessons' => [
                    ['title' => 'Parts of Speech', 'summary' => 'Nouns, verbs, adjectives, adverbs, and more', 'duration' => 1000],
                    ['title' => 'Sentence Structure', 'summary' => 'Building clear and effective sentences', 'duration' => 1200],
                    ['title' => 'Verb Tenses Mastery', 'summary' => 'All 12 tenses explained with examples', 'duration' => 1800],
                    ['title' => 'Paragraph Writing', 'summary' => 'Structure and develop strong paragraphs', 'duration' => 1300],
                    ['title' => 'Essay Writing', 'summary' => 'Write compelling essays with proper structure', 'duration' => 1600],
                ],
            ],
            [
                'subject' => 'Computer Science',
                'title' => 'Python Programming for Beginners',
                'description' => 'Learn Python from scratch! No prior programming experience needed. Build real projects and understand programming fundamentals.',
                'price' => 55.00,
                'lessons' => [
                    ['title' => 'Getting Started with Python', 'summary' => 'Installation, IDE setup, and first program', 'duration' => 1100],
                    ['title' => 'Variables and Data Types', 'summary' => 'Understanding different types of data', 'duration' => 1300],
                    ['title' => 'Control Flow', 'summary' => 'If statements, loops, and logical operators', 'duration' => 1500],
                    ['title' => 'Functions and Modules', 'summary' => 'Writing reusable code with functions', 'duration' => 1400],
                    ['title' => 'Lists and Dictionaries', 'summary' => 'Working with Python data structures', 'duration' => 1600],
                    ['title' => 'File Handling', 'summary' => 'Reading and writing files in Python', 'duration' => 1200],
                    ['title' => 'Final Project', 'summary' => 'Build a complete Python application', 'duration' => 2000],
                ],
            ],
            [
                'subject' => 'Chemistry',
                'title' => 'IGCSE Chemistry Complete Course',
                'description' => 'Comprehensive IGCSE Chemistry course covering all topics from the syllabus. Perfect for exam preparation with detailed explanations.',
                'price' => 48.00,
                'lessons' => [
                    ['title' => 'Atomic Structure', 'summary' => 'Atoms, elements, and the periodic table', 'duration' => 1200],
                    ['title' => 'Chemical Bonding', 'summary' => 'Ionic, covalent, and metallic bonds', 'duration' => 1400],
                    ['title' => 'Chemical Reactions', 'summary' => 'Types of reactions and reaction equations', 'duration' => 1500],
                    ['title' => 'Acids, Bases and Salts', 'summary' => 'pH, neutralization, and salt preparation', 'duration' => 1300],
                    ['title' => 'Organic Chemistry', 'summary' => 'Introduction to carbon compounds', 'duration' => 1600],
                ],
            ],
            [
                'subject' => 'Biology',
                'title' => 'Human Biology and Anatomy',
                'description' => 'Explore the human body systems in detail. Perfect for biology students and anyone interested in understanding how our bodies work.',
                'price' => 42.00,
                'lessons' => [
                    ['title' => 'Cell Structure and Function', 'summary' => 'The building blocks of life', 'duration' => 1100],
                    ['title' => 'Digestive System', 'summary' => 'From mouth to intestines - how we process food', 'duration' => 1300],
                    ['title' => 'Respiratory System', 'summary' => 'Breathing and gas exchange', 'duration' => 1200],
                    ['title' => 'Circulatory System', 'summary' => 'Heart, blood vessels, and blood', 'duration' => 1400],
                    ['title' => 'Nervous System', 'summary' => 'Brain, nerves, and neural communication', 'duration' => 1500],
                ],
            ],
        ];

        foreach ($courseData as $data) {
            $teacher = User::whereHas('teacherProfile.subjects', function ($query) use ($data) {
                $query->where('name', $data['subject']);
            })->inRandomOrder()->first();

            if (! $teacher) {
                continue;
            }

            $subject = Subject::where('name', $data['subject'])->first();

            $course = Course::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'title' => $data['title'],
                'slug' => \Illuminate\Support\Str::slug($data['title']),
                'description' => $data['description'],
                'price' => $data['price'],
                'currency' => 'BHD',
                'is_published' => true,
                'published_at' => now()->subDays(rand(5, 60)),
            ]);

            foreach ($data['lessons'] as $index => $lessonData) {
                CourseLesson::create([
                    'course_id' => $course->id,
                    'title' => $lessonData['title'],
                    'summary' => $lessonData['summary'],
                    'sort_order' => $index,
                    'video_provider' => 'youtube',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'duration_seconds' => $lessonData['duration'],
                    'is_free_preview' => $index === 0,
                ]);
            }

            // Enroll some students in courses
            $numEnrollments = rand(3, 8);
            $enrolledStudents = collect($createdStudents)->random(min($numEnrollments, count($createdStudents)));

            foreach ($enrolledStudents as $student) {
                $enrolledAt = now()->subDays(rand(1, 30));

                // Create payment first
                $provider = rand(0, 1) === 0 ? 'stripe' : 'benefitpay';
                $transactionId = 'CRS'.strtoupper(substr(md5(uniqid()), 0, 12));

                $payment = Payment::create([
                    'booking_id' => null,
                    'student_id' => $student->id,
                    'provider' => $provider,
                    'amount' => $course->price,
                    'currency' => 'BHD',
                    'status' => 'succeeded',
                    'provider_reference' => strtoupper($provider).'_'.$transactionId,
                    'paid_at' => $enrolledAt,
                ]);

                // Then create purchase with payment_id
                $purchase = CoursePurchase::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'payment_id' => $payment->id,
                    'purchased_at' => $enrolledAt,
                ]);

                // Create enrollment
                $enrollment = CourseEnrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => $enrolledAt,
                ]);

                // Create some lesson progress
                $lessons = $course->lessons;
                $progressCount = rand(1, min(3, $lessons->count()));

                foreach ($lessons->take($progressCount) as $lesson) {
                    $isCompleted = rand(0, 2) !== 0;
                    $watchedSeconds = $isCompleted ? $lesson->duration_seconds : rand(100, $lesson->duration_seconds - 100);

                    LessonProgress::create([
                        'course_id' => $course->id,
                        'lesson_id' => $lesson->id,
                        'student_id' => $student->id,
                        'watched_seconds' => $watchedSeconds,
                        'completed_at' => $isCompleted ? now()->subDays(rand(1, 20)) : null,
                    ]);
                }

                // Some students leave reviews
                if (rand(0, 2) === 0) {
                    Review::create([
                        'user_id' => $student->id,
                        'reviewable_id' => $course->id,
                        'reviewable_type' => 'App\Models\Course',
                        'rating' => rand(4, 5),
                        'comment' => [
                            'Excellent course! Very well explained and easy to follow.',
                            'This course helped me understand the subject much better. Highly recommend!',
                            'Great content and presentation. The examples were very helpful.',
                            'Perfect for exam preparation. Covered everything I needed to know.',
                            'Outstanding course! The teacher explains everything clearly.',
                        ][rand(0, 4)],
                        'is_approved' => true,
                        'approved_at' => now(),
                    ]);
                }
            }
        }

        // Create some conversations between students and teachers
        $numConversations = 20;
        $createdConversations = [];

        for ($i = 0; $i < $numConversations; $i++) {
            $student = $createdStudents[array_rand($createdStudents)];
            $teacher = $createdTeachers[array_rand($createdTeachers)];

            // Check if conversation already exists between these users
            $existingConv = collect($createdConversations)->first(function ($conv) use ($student, $teacher) {
                return ($conv['user_one'] === $student->id && $conv['user_two'] === $teacher->id)
                    || ($conv['user_one'] === $teacher->id && $conv['user_two'] === $student->id);
            });

            if ($existingConv) {
                continue;
            }

            $lastMessageTime = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

            $conversation = Conversation::create([
                'user_one_id' => min($student->id, $teacher->id), // Lower ID goes first
                'user_two_id' => max($student->id, $teacher->id),
                'booking_id' => null,
                'last_message_at' => $lastMessageTime,
            ]);

            $createdConversations[] = [
                'user_one' => $student->id,
                'user_two' => $teacher->id,
                'conversation' => $conversation,
            ];

            // Create 2-5 messages per conversation
            $numMessages = rand(2, 5);
            for ($j = 0; $j < $numMessages; $j++) {
                $isFromStudent = $j % 2 === 0;
                $messageTime = $lastMessageTime->copy()->subMinutes(($numMessages - $j) * 30);

                $isRead = $j < $numMessages - 1;

                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $isFromStudent ? $student->id : $teacher->id,
                    'body' => $isFromStudent
                        ? 'Hello, I have a question about '.['the upcoming lesson', 'my homework', 'the course material', 'scheduling'][rand(0, 3)].'.'
                        : 'Hello! I\'d be happy to help you with that. '.['Let me explain...', 'Can you provide more details?', 'Here is the information you need...'][rand(0, 2)],
                    'is_read' => $isRead,
                    'read_at' => $isRead ? $messageTime->copy()->addMinutes(rand(5, 60)) : null,
                    'created_at' => $messageTime,
                    'updated_at' => $messageTime,
                ]);
            }
        }

        // Create support tickets
        $ticketData = [
            ['subject' => 'Problem with payment processing', 'category' => 'billing'],
            ['subject' => 'Cannot access my booked session', 'category' => 'technical'],
            ['subject' => 'Issue with video playback in course', 'category' => 'technical'],
            ['subject' => 'Need help changing my profile information', 'category' => 'general'],
            ['subject' => 'Question about refund policy', 'category' => 'billing'],
            ['subject' => 'Technical issue with booking system', 'category' => 'technical'],
            ['subject' => 'Cannot login to my account', 'category' => 'technical'],
            ['subject' => 'Problem with notification emails', 'category' => 'technical'],
        ];

        $ticketCounter = 1000;
        foreach (array_rand($ticketData, 6) as $index) {
            $data = $ticketData[$index];
            $student = $createdStudents[array_rand($createdStudents)];
            $status = ['open', 'in_progress', 'resolved'][rand(0, 2)];

            $ticket = SupportTicket::create([
                'ticket_number' => 'TKT-'.str_pad($ticketCounter++, 6, '0', STR_PAD_LEFT),
                'user_id' => $student->id,
                'subject' => $data['subject'],
                'description' => 'I am experiencing an issue with '.$data['subject'].'. Could you please help me resolve this? This is affecting my ability to use the platform effectively.',
                'status' => $status,
                'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                'category' => $data['category'],
                'resolved_at' => $status === 'resolved' ? now()->subDays(rand(1, 5)) : null,
            ]);

            // Add admin reply for non-open tickets
            if ($status !== 'open') {
                $admin = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->first();

                if ($admin) {
                    // Update assigned_to
                    $ticket->update(['assigned_to' => $admin->id]);

                    SupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $admin->id,
                        'message' => 'Thank you for contacting us. We are looking into your issue and will get back to you shortly.',
                        'is_internal' => false,
                    ]);

                    if ($status === 'resolved') {
                        SupportTicketReply::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $admin->id,
                            'message' => 'This issue has been resolved. Please let us know if you need any further assistance.',
                            'is_internal' => false,
                        ]);
                    }
                }
            }
        }

        // Create some educational resources attached to courses
        foreach (Course::limit(3)->get() as $course) {
            if (! $course->teacher) {
                continue;
            }

            Resource::create([
                'user_id' => $course->teacher_id,
                'resourceable_id' => $course->id,
                'resourceable_type' => 'App\Models\Course',
                'title' => 'Course Materials for '.$course->title,
                'description' => 'Supplementary materials and resources for this course',
                'file_path' => 'resources/course_'.$course->id.'_materials.pdf',
                'file_name' => 'course_materials.pdf',
                'file_type' => 'application/pdf',
                'file_size' => rand(500000, 5000000),
                'is_public' => true,
            ]);
        }

        $this->command->info('Realistic data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- '.User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->count().' Teachers with availability and time slots');
        $this->command->info('- '.User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->count().' Students');
        $this->command->info('- '.Booking::count().' Bookings with payments');
        $this->command->info('- '.Course::count().' Courses with lessons');
        $this->command->info('- '.CourseEnrollment::count().' Course enrollments');
        $this->command->info('- '.Review::count().' Reviews');
        $this->command->info('- '.Conversation::count().' Conversations with messages');
        $this->command->info('- '.SupportTicket::count().' Support tickets');
        $this->command->info('- '.Resource::count().' Educational resources');
    }
}
