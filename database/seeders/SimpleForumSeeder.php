<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class SimpleForumSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create categories
        $generalCategory = ForumCategory::firstOrCreate(
            ['slug' => 'general-discussion'],
            [
                'name' => 'General Discussion',
                'description' => 'General topics, announcements, and community discussions',
                'sort_order' => 1,
            ]
        );

        $academicCategory = ForumCategory::firstOrCreate(
            ['slug' => 'academic-support'],
            [
                'name' => 'Academic Support',
                'description' => 'Get help with homework, study tips, and academic questions',
                'sort_order' => 2,
            ]
        );

        // Get or create sample users
        $student = User::where('email', 'student@example.com')->first();
        $teacher = User::where('email', 'teacher@example.com')->first();

        if (! $student) {
            $student = User::create([
                'name' => 'Test Student',
                'email' => 'student@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        if (! $teacher) {
            $teacher = User::create([
                'name' => 'Test Teacher',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create sample threads
        if ($generalCategory && $student) {
            $thread1 = ForumThread::create([
                'category_id' => $generalCategory->id,
                'user_id' => $student->id,
                'title' => 'Welcome to the Forum!',
                'slug' => 'welcome-to-the-forum-'.time(),
                'content' => 'This is a welcome thread to test our new forum functionality. Feel free to reply and test out the features!',
                'last_post_at' => now(),
            ]);

            ForumPost::create([
                'thread_id' => $thread1->id,
                'user_id' => $student->id,
                'content' => 'Hello everyone! I\'m excited to announce that our new forum is now live. This will be a great place for students and teachers to connect and share knowledge.',
            ]);
        }

        if ($academicCategory && $teacher) {
            $thread2 = ForumThread::create([
                'category_id' => $academicCategory->id,
                'user_id' => $teacher->id,
                'title' => 'Study Tips for Mathematics',
                'slug' => 'study-tips-mathematics-'.time(),
                'content' => 'Share your best study tips and strategies for learning mathematics effectively.',
                'last_post_at' => now(),
            ]);

            ForumPost::create([
                'thread_id' => $thread2->id,
                'user_id' => $teacher->id,
                'content' => 'Here are some effective study tips for mathematics:\n\n1. Practice daily - even 30 minutes makes a huge difference\n2. Focus on understanding concepts, not memorizing formulas\n3. Work through problems step by step\n4. Don\'t be afraid to ask questions!\n\nWhat are your favorite math study techniques?',
            ]);
        }

        $this->command->info('Simple forum data seeded successfully!');
        $this->command->info('Categories: '.ForumCategory::count());
        $this->command->info('Threads: '.ForumThread::count());
        $this->command->info('Posts: '.ForumPost::count());
        $this->command->info('Test your forum at: http://127.0.0.1:8000/forum');
    }
}
