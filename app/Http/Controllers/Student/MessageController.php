<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('student.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $user = auth()->user();

        // Verify user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $otherUser = $conversation->getOtherUser($user);
        $messages = $conversation->messages()->with(['sender', 'attachments'])->latest('created_at')->get();

        // Mark messages as read
        $conversation->markAsReadFor($user);

        return view('student.messages.show', compact('conversation', 'otherUser', 'messages'));
    }

    public function create(Request $request): View
    {
        $booking = null;
        $otherUser = null;

        if ($request->has('booking_id')) {
            $booking = Booking::findOrFail($request->booking_id);
            $this->authorize('view', $booking);
            $otherUser = $booking->teacher->user;
        }

        return view('student.messages.create', compact('booking', 'otherUser'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = auth()->user();

        // Verify user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                $message->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        notify()->success()
            ->title('تم الإرسال')
            ->message('تم إرسال الرسالة بنجاح')
            ->send();

        return redirect()->route('student.messages.show', $conversation);
    }

    public function startConversation(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
        ]);

        $otherUser = \App\Models\User::findOrFail($request->user_id);
        $booking = $request->booking_id ? Booking::findOrFail($request->booking_id) : null;

        if ($booking) {
            $this->authorize('view', $booking);
        }

        $conversation = Conversation::getOrCreateBetween(auth()->user(), $otherUser, $booking);

        return redirect()->route('student.messages.show', $conversation);
    }
}
