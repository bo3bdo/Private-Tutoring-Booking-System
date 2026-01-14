<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->seed(\Database\Seeders\DemoUserSeeder::class);
});

it('allows quick login as admin in local environment', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    $response = $this->get(route('dev.quick-login.admin'));

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'admin@example.com')->first());
});

it('allows quick login as teacher in local environment', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    $response = $this->get(route('dev.quick-login.teacher'));

    $response->assertRedirect(route('teacher.dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'teacher@example.com')->first());
});

it('allows quick login as student in local environment', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    $response = $this->get(route('dev.quick-login.student'));

    $response->assertRedirect(route('student.dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'student@example.com')->first());
});

it('returns 404 if admin user does not exist', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    User::where('email', 'admin@example.com')->delete();

    $response = $this->get(route('dev.quick-login.admin'));

    $response->assertNotFound();
});

it('returns 404 if teacher user does not exist', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    User::where('email', 'teacher@example.com')->delete();

    $response = $this->get(route('dev.quick-login.teacher'));

    $response->assertNotFound();
});

it('returns 404 if student user does not exist', function () {
    config(['app.env' => 'local']);
    config(['app.debug' => true]);

    User::where('email', 'student@example.com')->delete();

    $response = $this->get(route('dev.quick-login.student'));

    $response->assertNotFound();
});

it('returns 404 in production environment', function () {
    // Mock environment to production
    $this->app->detectEnvironment(fn () => 'production');
    config(['app.debug' => false]);

    $response = $this->get(route('dev.quick-login.teacher'));

    $response->assertNotFound();
});
