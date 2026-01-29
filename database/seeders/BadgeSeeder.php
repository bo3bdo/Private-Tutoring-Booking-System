<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Bronze Tier (100-499 points)
            [
                'name' => 'Fresh Starter',
                'slug' => 'fresh-starter',
                'description' => 'Welcome to the learning journey! You\'ve taken your first steps.',
                'icon' => 'star',
                'color' => '#CD7F32',
                'tier' => 'bronze',
                'points_threshold' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Active Learner',
                'slug' => 'active-learner',
                'description' => 'You\'re consistently engaging with the platform. Keep it up!',
                'icon' => 'book-open',
                'color' => '#CD7F32',
                'tier' => 'bronze',
                'points_threshold' => 250,
                'is_active' => true,
            ],
            [
                'name' => 'Knowledge Seeker',
                'slug' => 'knowledge-seeker',
                'description' => 'Your curiosity is driving your progress. Well done!',
                'icon' => 'search',
                'color' => '#CD7F32',
                'tier' => 'bronze',
                'points_threshold' => 400,
                'is_active' => true,
            ],
            // Silver Tier (500-1999 points)
            [
                'name' => 'Dedicated Student',
                'slug' => 'dedicated-student',
                'description' => 'Your dedication to learning is truly impressive.',
                'icon' => 'award',
                'color' => '#C0C0C0',
                'tier' => 'silver',
                'points_threshold' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Rising Star',
                'slug' => 'rising-star',
                'description' => 'You\'re shining bright in your educational journey!',
                'icon' => 'trending-up',
                'color' => '#C0C0C0',
                'tier' => 'silver',
                'points_threshold' => 750,
                'is_active' => true,
            ],
            [
                'name' => 'Committed Scholar',
                'slug' => 'committed-scholar',
                'description' => 'Your commitment to education sets you apart.',
                'icon' => 'graduation-cap',
                'color' => '#C0C0C0',
                'tier' => 'silver',
                'points_threshold' => 1000,
                'is_active' => true,
            ],
            [
                'name' => 'Advanced Learner',
                'slug' => 'advanced-learner',
                'description' => 'You\'ve reached an advanced level of engagement.',
                'icon' => 'zap',
                'color' => '#C0C0C0',
                'tier' => 'silver',
                'points_threshold' => 1500,
                'is_active' => true,
            ],
            // Gold Tier (2000-4999 points)
            [
                'name' => 'Expert Student',
                'slug' => 'expert-student',
                'description' => 'You\'ve achieved expert status through consistent effort.',
                'icon' => 'trophy',
                'color' => '#FFD700',
                'tier' => 'gold',
                'points_threshold' => 2000,
                'is_active' => true,
            ],
            [
                'name' => 'Master Achiever',
                'slug' => 'master-achiever',
                'description' => 'Your achievements speak volumes about your dedication.',
                'icon' => 'crown',
                'color' => '#FFD700',
                'tier' => 'gold',
                'points_threshold' => 3000,
                'is_active' => true,
            ],
            [
                'name' => 'Elite Scholar',
                'slug' => 'elite-scholar',
                'description' => 'You belong to the elite group of top learners.',
                'icon' => 'gem',
                'color' => '#FFD700',
                'tier' => 'gold',
                'points_threshold' => 4000,
                'is_active' => true,
            ],
            // Platinum Tier (5000+ points)
            [
                'name' => 'Legendary Learner',
                'slug' => 'legendary-learner',
                'description' => 'You\'ve reached legendary status! An inspiration to all.',
                'icon' => 'crown',
                'color' => '#E5E4E2',
                'tier' => 'platinum',
                'points_threshold' => 5000,
                'is_active' => true,
            ],
            [
                'name' => 'Education Master',
                'slug' => 'education-master',
                'description' => 'The pinnacle of educational achievement. Truly remarkable!',
                'icon' => 'shield',
                'color' => '#E5E4E2',
                'tier' => 'platinum',
                'points_threshold' => 7500,
                'is_active' => true,
            ],
            [
                'name' => 'Ultimate Scholar',
                'slug' => 'ultimate-scholar',
                'description' => 'The ultimate recognition for your educational excellence.',
                'icon' => 'medal',
                'color' => '#E5E4E2',
                'tier' => 'platinum',
                'points_threshold' => 10000,
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
