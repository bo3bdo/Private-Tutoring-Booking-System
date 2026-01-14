<?php

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->student = User::factory()->create();
    $this->student->assignRole('student');
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('allows user to create support ticket', function () {
    $this->actingAs($this->student)
        ->post(route('student.support-tickets.store'), [
            'subject' => 'Need Help',
            'description' => 'I need assistance',
            'category' => 'technical',
            'priority' => 'high',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('support_tickets', [
        'user_id' => $this->student->id,
        'subject' => 'Need Help',
        'status' => 'open',
        'priority' => 'high',
    ]);
});

it('generates unique ticket number', function () {
    $ticket1 = SupportTicket::create([
        'user_id' => $this->student->id,
        'subject' => 'Ticket 1',
        'description' => 'Description',
    ]);

    $ticket2 = SupportTicket::create([
        'user_id' => $this->student->id,
        'subject' => 'Ticket 2',
        'description' => 'Description',
    ]);

    expect($ticket1->ticket_number)->not->toBe($ticket2->ticket_number);
    expect($ticket1->ticket_number)->toStartWith('TKT-');
});

it('allows user to reply to their ticket', function () {
    $ticket = SupportTicket::factory()->create([
        'user_id' => $this->student->id,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.support-tickets.reply', $ticket), [
            'message' => 'This is my reply',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('support_ticket_replies', [
        'ticket_id' => $ticket->id,
        'user_id' => $this->student->id,
        'message' => 'This is my reply',
        'is_internal' => false,
    ]);
});

it('allows admin to assign ticket', function () {
    $ticket = SupportTicket::factory()->create([
        'user_id' => $this->student->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.support-tickets.assign', $ticket), [
            'assigned_to' => $this->admin->id,
        ])
        ->assertRedirect();

    expect($ticket->fresh()->assigned_to)->toBe($this->admin->id);
    expect($ticket->fresh()->status)->toBe('in_progress');
});

it('allows admin to update ticket status', function () {
    $ticket = SupportTicket::factory()->create([
        'user_id' => $this->student->id,
        'status' => 'open',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.support-tickets.update-status', $ticket), [
            'status' => 'resolved',
        ])
        ->assertRedirect();

    expect($ticket->fresh()->status)->toBe('resolved');
    expect($ticket->fresh()->resolved_at)->not->toBeNull();
});

it('allows admin to send internal reply', function () {
    $ticket = SupportTicket::factory()->create([
        'user_id' => $this->student->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.support-tickets.reply', $ticket), [
            'message' => 'Internal note',
            'is_internal' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('support_ticket_replies', [
        'ticket_id' => $ticket->id,
        'user_id' => $this->admin->id,
        'is_internal' => true,
    ]);
});

it('prevents user from viewing other users tickets', function () {
    $otherStudent = User::factory()->create();
    $otherStudent->assignRole('student');

    $ticket = SupportTicket::factory()->create([
        'user_id' => $this->student->id,
    ]);

    $this->actingAs($otherStudent)
        ->get(route('student.support-tickets.show', $ticket))
        ->assertForbidden();
});
