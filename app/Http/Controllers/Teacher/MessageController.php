<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return view('teacher.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $user = auth()->user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $otherUser = $conversation->getOtherUser($user);
        $messages = $conversation->messages()->with(['sender', 'attachments'])->orderBy('created_at')->get();

        $conversation->markAsReadFor($user);

        return view('teacher.messages.show', compact('conversation', 'otherUser', 'messages'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = auth()->user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

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

        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('teacher.messages.show', $conversation)
            ->with('success', 'Message sent successfully.');
    }
}
