<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SafariRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SafariRequestController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $sort = $request->string('sort')->toString() ?: 'created_at';
        $dir = strtolower($request->string('dir')->toString()) === 'asc' ? 'asc' : 'desc';

        $allowedSort = ['id', 'full_name', 'email', 'arrival_date', 'status', 'created_at', 'budget_range'];
        if (! in_array($sort, $allowedSort, true)) {
            $sort = 'created_at';
        }

        $requests = SafariRequest::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('full_name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%')
                        ->orWhere('phone', 'like', '%'.$q.'%');
                });
            })
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('admin.safari-requests.index', compact('requests', 'q', 'sort', 'dir'));
    }

    public function show(SafariRequest $safariRequest): View
    {
        return view('admin.safari-requests.show', [
            'req' => $safariRequest,
            'statusOptions' => SafariRequest::statusOptions(),
        ]);
    }

    public function update(Request $request, SafariRequest $safariRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(SafariRequest::statusOptions()))],
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $safariRequest->update($data);

        ActivityLog::record('safari_request.updated', $safariRequest->email, $safariRequest);

        return back()->with('success', __('Request updated.'));
    }

    public function destroy(SafariRequest $safariRequest): RedirectResponse
    {
        ActivityLog::record('safari_request.deleted', $safariRequest->email, $safariRequest);
        $safariRequest->delete();

        return redirect()->route('admin.safari-requests.index')->with('success', __('Request deleted.'));
    }
}
