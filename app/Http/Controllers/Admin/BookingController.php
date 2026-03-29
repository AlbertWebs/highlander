<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $status = $request->string('status')->trim();
        $bookings = Booking::query()
            ->with('tour')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'q', 'status'));
    }

    public function show(Booking $booking): View
    {
        $booking->load('tour');

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $tours = Tour::query()->active()->orderBy('title')->get();

        return view('admin.bookings.edit', compact('booking', 'tours'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,cancelled,completed'],
            'tour_id' => ['nullable', 'exists:tours,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:100'],
            'message' => ['nullable', 'string', 'max:10000'],
        ]);
        $booking->update($data);
        ActivityLog::record('booking.updated', 'Booking #'.$booking->id, $booking);

        return redirect()->route('admin.bookings.index')->with('success', __('Booking updated.'));
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        ActivityLog::record('booking.deleted', 'Booking #'.$booking->id, $booking);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', __('Booking deleted.'));
    }
}
