<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $groups = Setting::orderBy('group')->orderBy('sort')->get()->groupBy('group');
        $active = $request->query('group', $groups->keys()->first());

        return view('admin.settings.index', compact('groups', 'active'));
    }

    public function update(Request $request)
    {
        foreach ($request->input('settings', []) as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (! $setting) continue;

            if ($setting->type === 'bool') {
                $value = $request->boolean("settings.{$key}") ? '1' : '0';
            }
            $setting->update(['value' => $value]);
        }

        // Uploaded images replace their text value with the stored path.
        foreach ($request->allFiles()['files'] ?? [] as $key => $file) {
            if ($setting = Setting::where('key', $key)->first()) {
                $setting->update(['value' => $file->store('uploads', 'public')]);
            }
        }

        Cache::forget('settings.map');

        return back()->with('status', 'Settings saved.');
    }

    /** Add a setting key on the fly, so the panel is not limited to what shipped. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => ['required', 'string', 'max:60'],
            'key' => ['required', 'string', 'max:120', 'unique:settings,key'],
            'label' => ['required', 'string', 'max:180'],
            'type' => ['required', 'in:text,textarea,image,bool,number,json'],
            'value' => ['nullable', 'string'],
        ]);

        Setting::create($data);
        Cache::forget('settings.map');

        return back()->with('status', 'Setting added.');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        Cache::forget('settings.map');

        return back()->with('status', 'Setting removed.');
    }
}
