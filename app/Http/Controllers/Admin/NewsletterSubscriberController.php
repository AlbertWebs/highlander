<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $subscribers = NewsletterSubscriber::query()
            ->when($q, fn ($query) => $query->where('email', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.newsletter.index', compact('subscribers', 'q'));
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        ActivityLog::record('newsletter.removed', $newsletterSubscriber->email, $newsletterSubscriber);
        $newsletterSubscriber->delete();

        return redirect()->route('admin.newsletter-subscribers.index')->with('success', __('Subscriber removed.'));
    }
}
