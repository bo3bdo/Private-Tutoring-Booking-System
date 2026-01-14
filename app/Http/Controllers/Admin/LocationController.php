<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::latest()->paginate(15);

        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.locations.create');
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        Location::create([
            'name' => $request->name,
            'address' => $request->address,
            'map_url' => $request->map_url,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true),
        ]);

        notify()->success()
            ->title('تم الإنشاء')
            ->message('تم إنشاء الموقع بنجاح')
            ->send();

        return redirect()->route('admin.locations.index');
    }

    public function show(Location $location): View
    {
        $location->load(['bookings', 'teacherProfiles.user']);

        return view('admin.locations.show', compact('location'));
    }

    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $location->update([
            'name' => $request->name,
            'address' => $request->address,
            'map_url' => $request->map_url,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', $location->is_active),
        ]);

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث الموقع بنجاح')
            ->send();

        return redirect()->route('admin.locations.index');
    }

    public function destroy(Location $location): RedirectResponse
    {
        if ($location->bookings()->exists()) {
            notify()->error()
                ->title('خطأ')
                ->message('لا يمكن حذف الموقع الذي لديه حجوزات')
                ->send();

            return back();
        }

        $location->delete();

        notify()->success()
            ->title('تم الحذف')
            ->message('تم حذف الموقع بنجاح')
            ->send();

        return redirect()->route('admin.locations.index');
    }
}
