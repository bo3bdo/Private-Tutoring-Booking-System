<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data first to avoid duplicates
        ForumCategory::query()->delete();
        ForumThread::query()->delete();
        ForumPost::query()->delete();

        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'General topics, announcements, and community discussions',
                'sort_order' => 1,
            ],
            [
                'name' => 'Academic Support',
                'slug' => 'academic-support',
                'description' => 'Get help with homework, study tips, and academic questions',
                'sort_order' => 2,
            ],
            [
                'name' => 'Subject Specific',
                'slug' => 'subject-specific',
                'description' => 'Discussions about specific subjects like Math, Science, Languages, etc.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Study Groups',
                'slug' => 'study-groups',
                'description' => 'Form and join study groups for collaborative learning',
                'sort_order' => 4,
            ],
            [
                'name' => 'Resources & Materials',
                'slug' => 'resources-materials',
                'description' => 'Share and find study resources, notes, and learning materials',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::create($category);
        }

        $this->command->info('Forum categories created successfully!');
    }
}
