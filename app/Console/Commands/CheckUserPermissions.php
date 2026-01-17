<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {email : User email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user permissions and roles';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");

            return;
        }

        $this->info('User Information:');
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line('');

        $this->info('Roles:');
        $roles = $user->getRoleNames();
        if ($roles->isEmpty()) {
            $this->warn('No roles assigned');
        } else {
            foreach ($roles as $role) {
                $this->line("  - {$role}");
            }
        }
        $this->line('');

        $this->info('Role Checks:');
        $this->line('Is Admin: '.($user->isAdmin() ? 'Yes' : 'No'));
        $this->line('Is Teacher: '.($user->isTeacher() ? 'Yes' : 'No'));
        $this->line('Is Student: '.($user->isStudent() ? 'Yes' : 'No'));
        $this->line('');

        $this->info('Profiles:');
        $this->line('Has Student Profile: '.($user->studentProfile ? 'Yes' : 'No'));
        $this->line('Has Teacher Profile: '.($user->teacherProfile ? 'Yes' : 'No'));
        $this->line('');

        $this->info('Permissions:');
        $this->line('Can view any bookings: '.($user->can('viewAny', Booking::class) ? 'Yes' : 'No'));
        $this->line('');

        if ($user->isStudent()) {
            $bookingsCount = $user->bookings()->count();
            $this->info("Student Bookings: {$bookingsCount}");
        }
    }
}
