<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $items = MediaFile::query()
            ->when($q, fn ($query) => $query->where('original_name', 'like', '%'.$q.'%'))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.media.index', compact('items', 'q'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required'],
            'files.*' => ['file', 'image', 'max:10240'],
        ]);
        foreach ($request->file('files', []) as $file) {
            $path = $file->store('media', 'public');
            MediaFile::query()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
        ActivityLog::record('media.upload', 'Media library upload');

        return back()->with('success', __('Files uploaded.'));
    }

    public function destroy(MediaFile $mediaFile): RedirectResponse
    {
        Storage::disk('public')->delete($mediaFile->path);
        ActivityLog::record('media.deleted', $mediaFile->original_name, $mediaFile);
        $mediaFile->delete();

        return back()->with('success', __('File deleted.'));
    }
}
