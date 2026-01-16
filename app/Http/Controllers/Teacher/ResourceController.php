<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceRequest;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $query = auth()->user()->resources()->with('resourceable');

        if ($request->has('booking_id')) {
            $query->where('resourceable_type', Booking::class)
                ->where('resourceable_id', $request->booking_id);
        }

        if ($request->has('course_id')) {
            $query->where('resourceable_type', Course::class)
                ->where('resourceable_id', $request->course_id);
        }

        $resources = $query->latest()->paginate(20);

        return view('teacher.resources.index', compact('resources'));
    }

    public function create(Request $request): View
    {
        $resourceable = null;
        $teacher = auth()->user()->teacherProfile;

        if ($request->has('booking_id')) {
            $resourceable = Booking::findOrFail($request->booking_id);
            $this->authorize('view', $resourceable);
        } elseif ($request->has('course_id')) {
            $resourceable = Course::findOrFail($request->course_id);
            $this->authorize('view', $resourceable);
        }

        // Get bookings and courses for the teacher
        $bookings = $teacher->bookings()
            ->with(['student', 'subject'])
            ->orderBy('start_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'subject_name' => $booking->subject->name,
                    'student_name' => $booking->student->name,
                    'start_at' => $booking->start_at->format('Y-m-d H:i'),
                    'formatted_date' => $booking->start_at->format('M j, Y g:i A'),
                ];
            });

        $courses = auth()->user()->courses()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                ];
            });

        return view('teacher.resources.create', compact('resourceable', 'bookings', 'courses'));
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $resourceable = match ($request->resourceable_type) {
            'App\Models\Booking' => Booking::findOrFail($request->resourceable_id),
            'App\Models\Course' => Course::findOrFail($request->resourceable_id),
            default => null,
        };

        if (! $resourceable) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Invalid resource item'))
                ->send();

            return back();
        }

        $file = $request->file('file');
        $path = $file->store('resources', 'public');

        $resource = Resource::create([
            'user_id' => auth()->id(),
            'resourceable_type' => $request->resourceable_type,
            'resourceable_id' => $request->resourceable_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_public' => $request->boolean('is_public', false),
        ]);

        notify()->success()
            ->title(__('common.Uploaded'))
            ->message(__('common.Resource uploaded successfully'))
            ->send();

        return back();
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $this->authorize('delete', $resource);

        if (Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        notify()->success()
            ->title(__('common.Deleted'))
            ->message(__('common.Resource deleted successfully'))
            ->send();

        return back();
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
