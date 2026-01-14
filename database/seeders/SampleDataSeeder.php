<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'description' => 'Math tutoring'],
            ['name' => 'English', 'description' => 'English language'],
            ['name' => 'Science', 'description' => 'Science subjects'],
            ['name' => 'Arabic', 'description' => 'Arabic language'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['name' => $subject['name']], $subject);
        }

        $locations = [
            ['name' => 'Main Office', 'address' => '123 Education Street, Manama'],
            ['name' => 'Branch Office', 'address' => '456 Learning Avenue, Riffa'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(['name' => $location['name']], $location);
        }

        for ($i = 1; $i <= 3; $i++) {
            $teacher = User::firstOrCreate(
                ['email' => "teacher{$i}@example.com"],
                [
                    'name' => "Teacher {$i}",
                    'password' => Hash::make('password'),
                ]
            );

            if (! $teacher->hasRole('teacher')) {
                $teacher->assignRole('teacher');
            }

            $profile = TeacherProfile::firstOrCreate(
                ['user_id' => $teacher->id],
                [
                    'bio' => "Experienced teacher {$i}",
                    'hourly_rate' => 20 + ($i * 5),
                    'is_active' => true,
                    'supports_online' => true,
                    'supports_in_person' => true,
                ]
            );

            $profile->subjects()->sync(Subject::inRandomOrder()->limit(2)->pluck('id'));
        }
    }
}
