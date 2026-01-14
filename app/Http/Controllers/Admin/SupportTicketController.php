<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportTicket::with(['user', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->latest()->paginate(20);

        return view('admin.support-tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $supportTicket): View
    {
        $supportTicket->load(['user', 'assignedTo', 'replies.user']);

        return view('admin.support-tickets.show', compact('supportTicket'));
    }

    public function assign(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $supportTicket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'Ticket assigned successfully.');
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
        ]);

        $supportTicket->update(['status' => $request->status]);

        if ($request->status === 'resolved') {
            $supportTicket->markAsResolved();
        } elseif ($request->status === 'closed') {
            $supportTicket->markAsClosed();
        }

        return back()->with('success', 'Ticket status updated successfully.');
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $request->boolean('is_internal', false),
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}
