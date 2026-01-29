<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(Request $request): View
    {
        $query = Discount::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $discounts = $query->latest('created_at')->paginate(15);

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create(): View
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        Discount::create($validated);

        notify()->success()
            ->title(__('common.Created'))
            ->message(__('common.Discount created successfully'))
            ->send();

        return redirect()->route('admin.discounts.index');
    }

    public function edit(Discount $discount): View
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,'.$discount->id.'|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $discount->update($validated);

        notify()->success()
            ->title(__('common.Updated'))
            ->message(__('common.Discount updated successfully'))
            ->send();

        return redirect()->route('admin.discounts.index');
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        notify()->success()
            ->title(__('common.Deleted'))
            ->message(__('common.Discount deleted successfully'))
            ->send();

        return redirect()->route('admin.discounts.index');
    }
}
