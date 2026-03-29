<?php

namespace App\Http\Controllers;

use App\Mail\SafariRequestSubmitted;
use App\Models\SafariRequest;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SafariPlanController extends Controller
{
    public function index(Request $request): View
    {
        $prefill = [];
        $prefillTour = null;
        $tourSlug = $request->query('tour');
        if (is_string($tourSlug) && $tourSlug !== '') {
            $prefillTour = Tour::query()->active()->where('slug', $tourSlug)->first();
            if ($prefillTour !== null) {
                $prefill = $prefillTour->planPrefillSuggestions();
            }
        }

        return view('pages.plan-my-safari', array_merge(SeoMeta::metaFor('plan-my-safari'), [
            'privacyUrl' => route('privacy'),
            'prefill' => $prefill,
            'prefillTour' => $prefillTour,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('hp_website')) {
            return back()->with('safari_success', true);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:120'],
            'contact_method' => ['nullable', 'in:phone,email,whatsapp'],
            'arrival_date' => ['required', 'date'],
            'departure_date' => ['required', 'date', 'after:arrival_date'],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['nullable', 'integer', 'min:0', 'max:30'],
            'children_ages' => ['nullable', 'string', 'max:500'],
            'group_type' => ['nullable', 'in:solo,couple,family,friends,corporate'],
            'destinations' => ['nullable', 'array'],
            'destinations.*' => ['string', 'max:80'],
            'experience_types' => ['nullable', 'array'],
            'experience_types.*' => ['string', 'max:80'],
            'accommodation_type' => ['nullable', 'string', 'max:80'],
            'room_type' => ['nullable', 'string', 'max:80'],
            'transport_type' => ['nullable', 'string', 'max:80'],
            'budget_range' => ['nullable', 'in:under_1000,1000_2500,2500_5000,5000_plus,not_sure'],
            'activities' => ['nullable', 'array'],
            'activities.*' => ['string', 'max:80'],
            'other_destination' => ['nullable', 'string', 'max:255'],
            'special_requests' => ['nullable', 'string', 'max:5000'],
            'consent_privacy' => ['accepted'],
            'wizard_step' => ['nullable', 'integer', 'min:1', 'max:6'],
        ]);

        $validated['flexible_dates'] = $request->boolean('flexible_dates');
        $validated['airport_pickup'] = $request->boolean('airport_pickup');

        $arrival = Carbon::parse($validated['arrival_date'])->startOfDay();
        $departure = Carbon::parse($validated['departure_date'])->startOfDay();
        $tripDuration = (int) $arrival->diffInDays($departure);

        $destinations = $validated['destinations'] ?? [];
        if (! empty($validated['other_destination'] ?? null)) {
            $destinations[] = trim($validated['other_destination']);
        }
        $destinations = array_values(array_unique(array_filter($destinations)));

        $safariRequest = SafariRequest::query()->create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'] ?? null,
            'contact_method' => $validated['contact_method'] ?? null,
            'arrival_date' => $arrival->toDateString(),
            'departure_date' => $departure->toDateString(),
            'trip_duration' => $tripDuration,
            'flexible_dates' => $validated['flexible_dates'],
            'adults' => (int) $validated['adults'],
            'children' => (int) ($validated['children'] ?? 0),
            'children_ages' => $validated['children_ages'] ?? null,
            'group_type' => $validated['group_type'] ?? null,
            'destinations' => $destinations,
            'experience_types' => $validated['experience_types'] ?? [],
            'accommodation_type' => $validated['accommodation_type'] ?? null,
            'room_type' => $validated['room_type'] ?? null,
            'transport_type' => $validated['transport_type'] ?? null,
            'airport_pickup' => $validated['airport_pickup'],
            'budget_range' => $validated['budget_range'] ?? null,
            'activities' => $validated['activities'] ?? [],
            'special_requests' => $validated['special_requests'] ?? null,
            'consent_privacy' => true,
            'status' => 'new',
        ]);

        $adminEmail = SiteSetting::getValue('contact_email', '');
        if ($adminEmail !== '' && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($adminEmail)->send(new SafariRequestSubmitted($safariRequest));
            } catch (\Throwable) {
                // Avoid breaking UX if mail is misconfigured
            }
        }

        return redirect()
            ->route('plan-my-safari')
            ->with('safari_success', true)
            ->with('safari_request_id', $safariRequest->id);
    }
}
