<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('hp_website')) {
            return back()->with('success', __('Thank you—we will be in touch shortly.'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'tour_id' => ['nullable', 'exists:tours,id'],
            'start_date' => ['nullable', 'date'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:50'],
            'booking_intent' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['booking_intent'])) {
            Booking::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'tour_id' => $data['tour_id'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'guests' => $data['guests'] ?? 1,
                'message' => $data['message'],
                'status' => 'pending',
            ]);
        } else {
            ContactMessage::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'] ?? 'Contact form',
                'message' => $data['message'],
            ]);
        }

        return back()->with('success', __('Thank you—we will be in touch shortly.'));
    }
}
