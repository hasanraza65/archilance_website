<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $items = Media::when($request->query('q'), fn ($q, $t) =>
                    $q->where('name', 'like', "%{$t}%")->orWhere('alt', 'like', "%{$t}%"))
                ->latest()->paginate(40)->withQueryString();

        return view('admin.media.index', [
            'items' => $items,
            'term' => $request->query('q'),
            'bundled' => $this->bundledAssets(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif,svg,pdf,mp4'],
        ]);

        $saved = [];
        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads', 'public');
            $abs = Storage::disk('public')->path($path);

            $w = $h = null;
            if (str_starts_with((string) $file->getMimeType(), 'image/')) {
                $size = @getimagesize($abs);
                [$w, $h] = $size ? [$size[0], $size[1]] : [null, null];
            }

            $saved[] = Media::create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $w,
                'height' => $h,
                'user_id' => $request->user()->id,
            ]);
        }

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'media' => $saved])
            : back()->with('status', count($saved) . ' file(s) uploaded.');
    }

    public function update(Request $request, Media $medium)
    {
        $medium->update($request->validate([
            'alt' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with('status', 'Media updated.');
    }

    public function destroy(Media $medium)
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('status', 'Media deleted.');
    }

    /**
     * The design ships with a large set of images already in public/assets.
     * Listing them here means the picker can use them without a re-upload.
     */
    protected function bundledAssets(): array
    {
        $out = [];
        foreach (['img/portfolio', 'img/services', 'img/team', 'img/hero', 'img/brand', 'img/people', 'img/clients'] as $dir) {
            $abs = public_path('assets/' . $dir);
            if (! is_dir($abs)) continue;
            foreach (scandir($abs) as $f) {
                if (in_array($f, ['.', '..'], true)) continue;
                if (! preg_match('/\.(webp|png|jpe?g|svg|gif)$/i', $f)) continue;
                $out[$dir][] = 'assets/' . $dir . '/' . $f;
            }
        }

        return $out;
    }
}
