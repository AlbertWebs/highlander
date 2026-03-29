<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $messages = ContactMessage::query()
            ->when($q, fn ($query) => $query->where('name', 'like', '%'.$q.'%')->orWhere('email', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.contact-messages.index', compact('messages', 'q'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        if (! $contactMessage->read_at) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['read_at' => now()]);

        return back()->with('success', __('Marked as read.'));
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        ActivityLog::record('contact_message.deleted', $contactMessage->email, $contactMessage);
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', __('Message deleted.'));
    }
}
