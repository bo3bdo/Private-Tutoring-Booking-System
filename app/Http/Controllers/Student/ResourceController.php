<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $booking = null;
        $course = null;

        $query = Resource::query()
            ->where(function ($q) use ($user) {
                $q->where('is_public', true)
                    ->orWhere('user_id', $user->id);
            })
            ->with('user');

        if ($request->has('booking_id')) {
            $booking = Booking::findOrFail($request->booking_id);

            // Verify student owns this booking
            if ($booking->student_id !== $user->id) {
                abort(403, 'You do not have access to this booking.');
            }

            $query->where('resourceable_type', Booking::class)
                ->where('resourceable_id', $booking->id);
        }

        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);
            $query->where('resourceable_type', Course::class)
                ->where('resourceable_id', $course->id);
        }

        if ($request->has('type')) {
            $query->where('resourceable_type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        $resources = $query->latest('created_at')->paginate(20);

        return view('student.resources.index', compact('resources', 'booking', 'course'));
    }

    public function download(Resource $resource)
    {
        $user = auth()->user();

        // Check if resource is public or user owns it
        if ($resource->is_public || $resource->user_id === $user->id) {
            // Allow download
        } elseif ($resource->resourceable_type === Booking::class) {
            // Check if student owns the booking
            $booking = Booking::find($resource->resourceable_id);
            if (! $booking || $booking->student_id !== $user->id) {
                abort(403, 'You do not have access to this resource.');
            }
        } elseif ($resource->resourceable_type === Course::class) {
            // Check if student is enrolled in the course
            $course = Course::find($resource->resourceable_id);
            if (! $course || ! $course->isEnrolledBy($user)) {
                abort(403, 'You do not have access to this resource.');
            }
        } else {
            abort(403, 'You do not have access to this resource.');
        }

        if (! Storage::disk('public')->exists($resource->file_path)) {
            abort(404, 'Resource file not found.');
        }

        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }

    public function downloadAttachment(\App\Models\MessageAttachment $attachment)
    {
        $message = $attachment->message;
        $conversation = $message->conversation;

        // Verify user is part of this conversation
        if ($conversation->user_one_id !== auth()->id() && $conversation->user_two_id !== auth()->id()) {
            abort(403);
        }

        if (! Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
