<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisticForumSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::whereHas('roles', fn ($q) => $q->where('name', 'teacher'))->get();
        $students = User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->get();
        $allUsers = $teachers->merge($students);

        if ($allUsers->isEmpty()) {
            $this->command->warn('No users found. Run DemoUserSeeder and RealisticDataSeeder first.');

            return;
        }

        // فئات واقعية - مناقشات تعليمية
        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'General topics, announcements, and community discussions. مناقشات عامة وإعلانات المجتمع.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Academic Support',
                'slug' => 'academic-support',
                'description' => 'Get help with homework, study tips, and academic questions. مساعدة في الواجبات ونصائح الدراسة.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Mathematics & Physics',
                'slug' => 'mathematics-physics',
                'description' => 'Algebra, calculus, mechanics, and problem-solving. رياضيات وفيزياء وحل المسائل.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Languages & Literature',
                'slug' => 'languages-literature',
                'description' => 'English, Arabic, grammar, and writing. اللغة الإنجليزية والعربية والنحو والكتابة.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Science & Chemistry',
                'slug' => 'science-chemistry',
                'description' => 'Biology, chemistry, and lab questions. علوم وكيمياء وأسئلة المعمل.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Exam Preparation',
                'slug' => 'exam-preparation',
                'description' => 'IGCSE, A-Levels, SAT, and exam strategies. التحضير للامتحانات والاستراتيجيات.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Teacher Tips & Resources',
                'slug' => 'teacher-tips',
                'description' => 'Teaching strategies, lesson plans, and resources. نصائح للمعلمين والموارد.',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Technical Support',
                'slug' => 'technical-support',
                'description' => 'Platform questions, booking issues, and technical help. أسئلة المنصة والدعم الفني.',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            ForumCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $general = ForumCategory::where('slug', 'general-discussion')->first();
        $academic = ForumCategory::where('slug', 'academic-support')->first();
        $math = ForumCategory::where('slug', 'mathematics-physics')->first();
        $lang = ForumCategory::where('slug', 'languages-literature')->first();
        $science = ForumCategory::where('slug', 'science-chemistry')->first();
        $exam = ForumCategory::where('slug', 'exam-preparation')->first();
        $teacherTips = ForumCategory::where('slug', 'teacher-tips')->first();
        $tech = ForumCategory::where('slug', 'technical-support')->first();

        // مواضيع وردود واقعية
        $threadsData = [
            // General
            [
                'category' => $general,
                'title' => 'Welcome to the Forum!',
                'content' => "Hello everyone! Welcome to our tutoring community forum. Here you can ask questions, share study tips, and connect with other students and teachers. Feel free to introduce yourself and tell us what subjects you're studying. We're here to help each other succeed!",
                'user' => $teachers->first(),
                'is_pinned' => true,
                'posts' => [
                    "Great to have this space! I'm looking forward to learning from everyone.",
                    "Thanks for the welcome! I'm a student preparing for IGCSE Maths. Anyone else?",
                    "Welcome everyone! As a teacher I'll be checking in regularly to answer questions.",
                ],
            ],
            [
                'category' => $general,
                'title' => 'Best study environment tips?',
                'content' => "What kind of environment do you find best for studying? Quiet room, library, or background music? I'm trying to improve my focus and would love to hear what works for you.",
                'user' => $students->random(),
                'posts' => [
                    'I need complete silence. Library works best for me.',
                    'I use lo-fi music in the background. Helps me concentrate.',
                    'Quiet room with good lighting. No phone nearby!',
                ],
            ],
            [
                'category' => $general,
                'title' => 'How do you manage time between school and tutoring?',
                'content' => 'I have school in the morning and tutoring sessions 3 times a week. Sometimes I feel overwhelmed. How do you balance everything? Any schedule tips?',
                'user' => $students->random(),
                'posts' => [
                    'I make a weekly planner and block study time. Really helps.',
                    'Same here. I also make sure to take short breaks every hour.',
                    'Try to do homework right after school before tutoring. Fresh mind!',
                ],
            ],
            // Academic Support
            [
                'category' => $academic,
                'title' => 'Stuck on algebra problem - need help',
                'content' => "I'm solving quadratic equations and got stuck on this: x² - 5x + 6 = 0. I know I need to factor but not sure of the steps. Can someone explain?",
                'user' => $students->random(),
                'posts' => [
                    "You're looking for two numbers that multiply to 6 and add to -5. So -2 and -3. So (x-2)(x-3)=0, so x=2 or x=3.",
                    'Exactly! The factors of 6 are 1,6 or 2,3. Which pair adds to -5? -2 and -3. Great explanation above.',
                    'Thanks both! That makes sense now.',
                ],
                'mark_best_answer' => 1,
            ],
            [
                'category' => $academic,
                'title' => 'Recommended resources for IGCSE Biology?',
                'content' => "I'm starting IGCSE Biology next term. Any recommended textbooks, websites, or YouTube channels? Want to get a head start.",
                'user' => $students->random(),
                'posts' => [
                    'Cambridge IGCSE Biology textbook is the standard. Very clear.',
                    'Khan Academy has good biology videos. Free and helpful.',
                    'Past papers from your exam board - best way to prepare!',
                ],
            ],
            [
                'category' => $academic,
                'title' => 'How to take better notes during online lessons?',
                'content' => 'I find it hard to listen and write at the same time in online tutoring. My notes are messy. Any tips for note-taking during video calls?',
                'user' => $students->random(),
                'posts' => [
                    'I record the session (with permission) and take notes after. Then I can pause and write properly.',
                    "Use bullet points only during lesson. Expand them right after when it's fresh.",
                    'Ask your teacher to share their screen with key points. Then you focus on listening.',
                ],
            ],
            // Mathematics & Physics
            [
                'category' => $math,
                'title' => 'Study Tips for Mathematics',
                'content' => 'Share your best study tips and strategies for learning mathematics effectively. What helped you understand difficult concepts?',
                'user' => $teachers->first(),
                'is_pinned' => true,
                'posts' => [
                    'Practice daily - even 30 minutes makes a huge difference. Consistency is key.',
                    "Focus on understanding concepts, not memorizing formulas. Once you get the 'why', the formula makes sense.",
                    "Work through problems step by step. Don't skip steps even if they seem obvious.",
                    "Don't be afraid to ask questions! There are no silly questions in maths.",
                ],
            ],
            [
                'category' => $math,
                'title' => 'Newton\'s laws - real life examples?',
                'content' => 'I understand the three laws in theory but need real-life examples to remember them. Can anyone give everyday examples for each law?',
                'user' => $students->random(),
                'posts' => [
                    'First law: seatbelt. When car stops, you keep moving. Seatbelt applies force to stop you.',
                    'Second law: pushing a shopping cart. Empty cart accelerates more than full one for same push. F=ma!',
                    "Third law: walking. You push ground backward, ground pushes you forward. That's why you move.",
                ],
            ],
            [
                'category' => $math,
                'title' => 'Calculus - when do we use integration vs differentiation?',
                'content' => 'I get confused about when to integrate and when to differentiate. Is there a simple way to remember? Like area under curve = integrate, slope = differentiate?',
                'user' => $students->random(),
                'posts' => [
                    'Yes! Rate of change (velocity from position, acceleration from velocity) = differentiate. Going back (position from velocity, area under curve) = integrate.',
                    'Think of it as reverse operations. Differentiation gives you the rate. Integration sums up small pieces.',
                    "That really helps. So differentiate = 'how fast is it changing?' and integrate = 'how much total?'",
                ],
            ],
            // Languages
            [
                'category' => $lang,
                'title' => 'How to improve English writing for essays?',
                'content' => 'My teacher says my ideas are good but my essay structure and grammar need work. What resources or methods helped you improve academic writing in English?',
                'user' => $students->random(),
                'posts' => [
                    'Learn the PEEL structure: Point, Evidence, Explain, Link. Every paragraph. Game changer for me.',
                    'Read more. Articles, essays. You absorb good structure without realizing.',
                    "Practice one paragraph at a time. Get feedback. Don't try to write full essay perfectly at first.",
                ],
            ],
            [
                'category' => $lang,
                'title' => 'Arabic grammar - الفرق بين كان وأخواتها؟',
                'content' => 'ما الفرق بين كان وأخواتها (أصبح، ظل، بات...)؟ ومتى نستخدم كل واحدة؟ أدرس النحو وأحتاج توضيح.',
                'user' => $students->random(),
                'posts' => [
                    'كان للماضي، أصبح للتحول، ظل للاستمرار. كلها ترفع الاسم وتنصب الخبر.',
                    'بات = أصبح في الليل. ليس = للنفي. إن وأخواتها للتوكيد والاختصاص.',
                    'شكراً! هذا يوضح الصورة.',
                ],
            ],
            // Science
            [
                'category' => $science,
                'title' => 'Chemistry - balancing equations help',
                'content' => 'I always get stuck balancing chemical equations. Is there a trick or systematic method? I lose track of the numbers.',
                'user' => $students->random(),
                'posts' => [
                    'Start with the element that appears in only one compound on each side. Balance it first, then move to others.',
                    "Leave O2 and H2 for last - they're often in many places. Balance metals and other elements first.",
                    'Practice with simple ones first. Combustion of methane, etc. Then it becomes automatic.',
                ],
            ],
            [
                'category' => $science,
                'title' => 'Biology - photosynthesis equation',
                'content' => 'Can someone write the full balanced equation for photosynthesis? And explain what happens in simple terms?',
                'user' => $students->random(),
                'posts' => [
                    '6CO2 + 6H2O + light → C6H12O6 + 6O2. Plant takes carbon dioxide and water, uses light (chlorophyll), makes glucose and releases oxygen.',
                    "The glucose is the plant's food. The oxygen is what we breathe! So plants are essential for life.",
                    'Thanks! So the plant is basically storing sunlight as chemical energy in glucose. Cool.',
                ],
                'mark_best_answer' => 1,
            ],
            // Exam Preparation
            [
                'category' => $exam,
                'title' => 'IGCSE exam timetable - how to plan revision?',
                'content' => 'I have 6 IGCSE exams in May. When should I start serious revision? And how do I split time between subjects?',
                'user' => $students->random(),
                'posts' => [
                    'Start at least 8 weeks before. More for subjects you find harder.',
                    'Do past papers! Best use of time. Do them under exam conditions, then mark and review mistakes.',
                    "Rotate subjects so you don't forget any. 2 subjects per day max for depth.",
                ],
            ],
            [
                'category' => $exam,
                'title' => 'A-Level Maths - what to focus on for Paper 1?',
                'content' => 'First time doing A-Level exams. Paper 1 is pure maths. Which topics come up most? Want to prioritise revision.',
                'user' => $students->random(),
                'posts' => [
                    'Algebra, calculus (differentiation and integration), and trigonometry are usually the biggest. Check your spec for exact weighting.',
                    "Proof and series often have one question each. Don't neglect them - easy marks if you practice.",
                    'Past papers from your board are the best guide. Topics repeat in similar patterns.',
                ],
            ],
            // Teacher Tips
            [
                'category' => $teacherTips,
                'title' => 'Engaging students in online lessons',
                'content' => 'What strategies do you use to keep students engaged during online tutoring? I find some students get distracted easily on video calls.',
                'user' => $teachers->random(),
                'posts' => [
                    'Short activities. Change task every 10-15 mins. Polls, whiteboard, share screen.',
                    'Ask them to do something - type in chat, draw, solve on paper and show. Passive listening loses them.',
                    'I use breakout rooms for pair work when doing group topics. They like the interaction.',
                ],
            ],
            [
                'category' => $teacherTips,
                'title' => 'Recommended tools for virtual whiteboard?',
                'content' => "I'm looking for a good virtual whiteboard to use in online maths and physics lessons. Free or low cost. Any recommendations?",
                'user' => $teachers->random(),
                'posts' => [
                    'I use OneNote - free, infinite canvas, good for maths. Students can follow along.',
                    'Google Jamboard is simple and free. Good for quick diagrams.',
                    'Bitpaper and Explain Everything are popular. Some have free tiers.',
                ],
            ],
            // Technical Support
            [
                'category' => $tech,
                'title' => 'Booking not showing in my calendar',
                'content' => "I booked a lesson yesterday but it's not showing in my dashboard. Payment went through. What should I do?",
                'user' => $students->random(),
                'posts' => [
                    "Check your email for confirmation. Sometimes there's a delay. Refresh the page.",
                    "Contact support if it's been more than an hour. They can check from their side.",
                    'Same happened to me - it appeared after logging out and back in. Try that.',
                ],
            ],
            [
                'category' => $tech,
                'title' => 'Zoom link not working for lesson',
                'content' => "My teacher sent a Zoom link but when I click it 5 minutes before the lesson it says the meeting doesn't exist. Is the link only active at start time?",
                'user' => $students->random(),
                'posts' => [
                    "Some hosts don't start the meeting until the scheduled time. Try again at the exact start time.",
                    "Check you're using the full link. Sometimes it gets cut off in email.",
                    'If still not working, message your teacher through the platform. They can re-send or use backup link.',
                ],
            ],
        ];

        foreach ($threadsData as $index => $data) {
            $category = $data['category'];
            $user = $data['user'];

            if (! $category || ! $user) {
                continue;
            }

            $slug = Str::slug($data['title']).'-'.now()->format('YmdHis').'-'.rand(100, 999);
            $createdAt = now()->subDays(rand(2, 60))->subHours(rand(0, 23));

            $thread = ForumThread::create([
                'category_id' => $category->id,
                'user_id' => $user->id,
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'is_pinned' => $data['is_pinned'] ?? false,
                'is_locked' => ($data['is_locked'] ?? false),
                'views_count' => rand(5, 350),
                'last_post_at' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $postAuthors = $allUsers->random(min(count($data['posts']), $allUsers->count()))->values();
            if ($postAuthors->count() < count($data['posts'])) {
                $postAuthors = $allUsers->random(count($data['posts']))->values();
            }

            $bestAnswerPostId = null;
            foreach ($data['posts'] as $i => $postContent) {
                $postUser = $postAuthors[$i] ?? $allUsers->random();
                $postCreatedAt = $createdAt->copy()->addMinutes(($i + 1) * rand(15, 120));

                $post = ForumPost::create([
                    'thread_id' => $thread->id,
                    'user_id' => $postUser->id,
                    'content' => $postContent,
                    'created_at' => $postCreatedAt,
                    'updated_at' => $postCreatedAt,
                ]);

                if (isset($data['mark_best_answer']) && $data['mark_best_answer'] === $i + 1) {
                    $bestAnswerPostId = $post->id;
                }
            }

            if ($bestAnswerPostId) {
                $thread->update([
                    'best_answer_post_id' => $bestAnswerPostId,
                    'last_post_at' => $postCreatedAt ?? $thread->last_post_at,
                ]);
            }

            // إضافة بعض التصويتات (reactions) لمواضيع عشوائية - مستخدم واحد تصويت واحد لكل موضوع
            if (rand(0, 2) === 0) {
                $voters = $allUsers->random(min(3, $allUsers->count()));
                foreach ($voters as $voter) {
                    if ($voter->id !== $thread->user_id) {
                        ForumReaction::firstOrCreate(
                            [
                                'user_id' => $voter->id,
                                'reactable_id' => $thread->id,
                                'reactable_type' => ForumThread::class,
                                'reaction_type' => 'upvote',
                            ],
                            [
                                'user_id' => $voter->id,
                                'reactable_id' => $thread->id,
                                'reactable_type' => ForumThread::class,
                                'reaction_type' => 'upvote',
                            ]
                        );
                    }
                }
            }
        }

        // تحديث last_post_at لجميع المواضيع بناءً على آخر رد
        ForumThread::with('posts')->chunk(50, function ($threads) {
            foreach ($threads as $thread) {
                $lastPost = $thread->posts()->orderByDesc('created_at')->first();
                if ($lastPost) {
                    $thread->update(['last_post_at' => $lastPost->created_at]);
                }
            }
        });

        $this->command->info('Realistic forum data seeded successfully!');
        $this->command->info('Categories: '.ForumCategory::count());
        $this->command->info('Threads: '.ForumThread::count());
        $this->command->info('Posts: '.ForumPost::count());
        $this->command->info('Reactions: '.ForumReaction::count());
    }
}
