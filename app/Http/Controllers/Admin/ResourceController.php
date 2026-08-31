<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Shared CRUD for every content type. Subclasses declare the model, the
 * validation rules and which fields are JSON/boolean/image; everything else
 * (index, create, store, edit, update, destroy, duplicate, reorder, publish
 * toggle) is handled once here.
 */
abstract class ResourceController extends Controller
{
    protected string $model;
    protected string $view;          // admin.services
    protected string $route;         // admin.services
    protected string $title;
    protected array $searchable = ['name'];
    protected array $jsonFields = [];
    protected array $boolFields = ['is_published'];
    protected array $imageFields = [];
    protected string $orderBy = 'sort';
    protected bool $sortable = true;

    abstract protected function rules(?Model $item = null): array;

    /** Extra data every form needs (select options, related lists). */
    protected function formData(): array
    {
        return [];
    }

    protected function query()
    {
        return $this->model::query();
    }

    public function index(Request $request)
    {
        $q = $this->query();

        if ($term = $request->query('q')) {
            $q->where(function ($w) use ($term) {
                foreach ($this->searchable as $col) {
                    $w->orWhere($col, 'like', "%{$term}%");
                }
            });
        }

        $items = $q->orderBy($this->orderBy)->orderBy('id')->paginate(25)->withQueryString();

        return view("{$this->view}.index", [
            'items' => $items,
            'title' => $this->title,
            'route' => $this->route,
            'sortable' => $this->sortable,
            'term' => $request->query('q'),
        ]);
    }

    public function create()
    {
        $item = new $this->model;

        return view("{$this->view}.form", array_merge([
            'item' => $item, 'title' => $this->title, 'route' => $this->route, 'mode' => 'create',
        ], $this->formData()));
    }

    public function store(Request $request)
    {
        $item = $this->model::create($this->payload($request));

        return redirect()->route("{$this->route}.edit", $item)
            ->with('status', "{$this->title} created.");
    }

    /**
     * Route-model binding cannot resolve the abstract Model type-hint the base
     * class uses, so every subclass shares this explicit lookup instead.
     */
    protected function find(int|string $key): Model
    {
        // Accept either the numeric id or the slug, because both are natural
        // things to type into the URL bar.
        if (is_numeric($key)) {
            return $this->model::findOrFail($key);
        }

        $model = new $this->model;

        abort_unless(in_array('slug', $model->getFillable(), true), 404);

        return $this->model::where('slug', $key)->firstOrFail();
    }

    public function edit(int|string $key)
    {
        $item = $this->find($key);

        return view("{$this->view}.form", array_merge([
            'item' => $item, 'title' => $this->title, 'route' => $this->route, 'mode' => 'edit',
        ], $this->formData()));
    }

    public function update(Request $request, int|string $key)
    {
        $item = $this->find($key);
        $item->update($this->payload($request, $item));

        return redirect()->route("{$this->route}.edit", $item)
            ->with('status', "{$this->title} updated.");
    }

    public function destroy(int|string $key)
    {
        $this->find($key)->delete();

        return redirect()->route("{$this->route}.index")
            ->with('status', "{$this->title} deleted.");
    }

    /** Copy a record, including its JSON blocks, as an unpublished draft. */
    public function duplicate(int|string $key)
    {
        $item = $this->find($key);
        $copy = $item->replicate();

        if (isset($copy->slug)) {
            $copy->slug = Str::slug($item->slug . '-copy-' . Str::random(4));
        }
        foreach (['name', 'title', 'question'] as $label) {
            if (isset($copy->{$label})) {
                $copy->{$label} = $item->{$label} . ' (copy)';
                break;
            }
        }
        if (isset($copy->is_published)) {
            $copy->is_published = false;
        }
        $copy->save();

        return redirect()->route("{$this->route}.edit", $copy)
            ->with('status', 'Duplicated — this copy is unpublished.');
    }

    public function toggle(int|string $key)
    {
        $item = $this->find($key);
        $item->update(['is_published' => ! $item->is_published]);

        return back()->with('status', $item->is_published ? 'Published.' : 'Unpublished.');
    }

    /** Drag-and-drop ordering from the index table. */
    public function reorder(Request $request)
    {
        foreach ($request->input('order', []) as $position => $id) {
            $this->model::where('id', $id)->update(['sort' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /** Normalise the request into a column => value array the model can take. */
    protected function payload(Request $request, ?Model $item = null): array
    {
        $data = $request->validate($this->rules($item));

        foreach ($this->boolFields as $f) {
            $data[$f] = $request->boolean($f);
        }

        foreach ($this->jsonFields as $f) {
            // The forms post JSON strings; store real arrays so casts work.
            if ($request->has($f)) {
                $raw = $request->input($f);
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $data[$f] = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                } else {
                    $data[$f] = $raw;
                }
            }
        }

        foreach ($this->imageFields as $f) {
            if ($request->hasFile($f)) {
                $data[$f] = $request->file($f)->store('uploads', 'public');
            } elseif ($request->filled($f . '_path')) {
                $data[$f] = $request->input($f . '_path');
            } else {
                unset($data[$f]);
            }
        }

        if (isset($data['slug'])) {
            $source = $data['slug'] ?: ($data['name'] ?? $data['title'] ?? Str::random(8));
            $data['slug'] = Str::slug($source);
        }

        return $data;
    }
}
