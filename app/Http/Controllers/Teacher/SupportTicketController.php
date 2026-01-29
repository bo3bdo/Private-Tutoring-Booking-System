<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Http\Requests\StoreSupportTicketRequest;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->supportTickets()->with('assignedTo');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest('created_at')->paginate(15);

        return view('teacher.support-tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('teacher.support-tickets.create');
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority ?? 'medium',
            'status' => 'open',
        ]);

        notify()->success()
            ->title(__('common.Support ticket created'))
            ->message(__('common.Support ticket created successfully. Ticket number: :number', ['number' => $ticket->ticket_number]))
            ->send();

        return redirect()->route('teacher.support-tickets.show', $ticket);
    }

    public function show(SupportTicket $supportTicket): View
    {
        $this->authorize('view', $supportTicket);

        $supportTicket->load(['replies.user', 'assignedTo', 'user']);

        return view('teacher.support-tickets.show', compact('supportTicket'));
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        $this->authorize('view', $supportTicket);

        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        // Update ticket status if it was closed/resolved
        if ($supportTicket->isClosed() || $supportTicket->isResolved()) {
            $supportTicket->update(['status' => 'open']);
        }

        notify()->success()
            ->title(__('common.Sent'))
            ->message(__('common.Reply sent successfully'))
            ->send();

        return back();
    }
}
