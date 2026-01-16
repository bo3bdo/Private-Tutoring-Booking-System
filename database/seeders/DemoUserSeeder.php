<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'John Teacher',
                'password' => Hash::make('password'),
            ]
        );
        $teacher->assignRole('teacher');

        $teacherProfile = TeacherProfile::firstOrCreate(
            ['user_id' => $teacher->id],
            [
                'bio' => 'Experienced teacher with 10+ years of experience.',
                'hourly_rate' => 25.00,
                'is_active' => true,
                'supports_online' => true,
                'supports_in_person' => true,
                'default_meeting_provider' => 'custom',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('password'),
            ]
        );
        $student->assignRole('student');

        StudentProfile::firstOrCreate(
            ['user_id' => $student->id],
            [
                'phone' => '+97312345678',
            ]
        );

        $location = Location::firstOrCreate(
            ['name' => 'Main Office'],
            [
                'address' => '123 Education Street, Manama, Bahrain',
                'is_active' => true,
            ]
        );

        $teacherProfile->update(['default_location_id' => $location->id]);

        $math = Subject::firstOrCreate(
            ['name' => 'Mathematics'],
            [
                'description' => 'Mathematics tutoring',
                'is_active' => true,
            ]
        );

        $english = Subject::firstOrCreate(
            ['name' => 'English'],
            [
                'description' => 'English language tutoring',
                'is_active' => true,
            ]
        );

        $teacherProfile->subjects()->sync([$math->id, $english->id]);
    }
}
