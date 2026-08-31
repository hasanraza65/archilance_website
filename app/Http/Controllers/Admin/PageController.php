<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Support\PageBlueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PageController extends ResourceController
{
    protected string $model = Page::class;
    protected string $view = 'admin.pages';
    protected string $route = 'admin.pages';
    protected string $title = 'Page';
    protected array $searchable = ['title', 'slug'];
    protected array $jsonFields = ['content'];
    protected array $imageFields = ['hero_image'];
    protected string $orderBy = 'id';
    protected bool $sortable = false;

    /**
     * Pages whose slug is baked into a route. Renaming one would orphan the
     * template, so the editor shows the slug read-only.
     */
    protected array $locked = ['home', 'about-us', 'faqs', 'contact', 'services', 'projects', 'blog'];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:180'],
            'title' => ['required', 'string', 'max:200'],
            'eyebrow' => ['nullable', 'string', 'max:180'],
            'h1_lead' => ['nullable', 'string', 'max:255'],
            'h1_gold' => ['nullable', 'string', 'max:255'],
            'lede' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function create()
    {
        return view('admin.pages.form', $this->formVars(new Page, 'create'));
    }

    public function edit(int|string $key)
    {
        return view('admin.pages.form', $this->formVars($this->find($key), 'edit'));
    }

    /** The blueprint drives the whole form, so it has to travel with the item. */
    protected function formVars(Model $item, string $mode): array
    {
        return [
            'item' => $item,
            'title' => $this->title,
            'route' => $this->route,
            'mode' => $mode,
            'blueprint' => PageBlueprint::for((string) $item->slug),
            'locked' => $this->locked,
            'preview' => $item->exists ? $this->previewUrl((string) $item->slug) : null,
        ];
    }

    /** Named routes win where one exists; anything else uses the catch-all. */
    protected function previewUrl(string $slug): ?string
    {
        $named = [
            'home' => 'home',
            'about-us' => 'about',
            'faqs' => 'faq',
            'contact' => 'contact',
            'services' => 'services.index',
            'projects' => 'projects.index',
            'blog' => 'blog.index',
        ];

        if (isset($named[$slug])) {
            return route($named[$slug]);
        }

        return $slug ? route('page', $slug) : null;
    }

    protected function payload(Request $request, ?Model $item = null): array
    {
        $data = parent::payload($request, $item);

        $posted = $request->input('content');
        if (! is_array($posted)) {
            return $data;
        }

        // The editor posts one input per blueprint key, flat: content[hero.title].
        // Repeat and list fields arrive as JSON strings, so decode those back.
        $slug = $item?->slug ?? ($data['slug'] ?? '');
        $types = [];
        foreach (PageBlueprint::for((string) $slug) as $section) {
            foreach ($section['fields'] as $key => $field) {
                $types[$key] = $field['type'];
            }
        }

        $content = [];
        foreach ($posted as $key => $value) {
            if (in_array($types[$key] ?? 'text', ['repeat', 'list'], true) && is_string($value)) {
                $decoded = json_decode($value, true);
                $value = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
            }

            // A blank field means "use the original wording", so store nothing.
            if ($value === '' || $value === null || $value === []) {
                continue;
            }

            $content[$key] = $value;
        }

        $data['content'] = $content;

        return $data;
    }
}
