<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceController extends ResourceController
{
    protected string $model = Service::class;
    protected string $view = 'admin.services';
    protected string $route = 'admin.services';
    protected string $title = 'Service';
    protected array $searchable = ['name', 'slug', 'short_name'];
    protected array $jsonFields = ['stats', 'intro', 'deliverables', 'process', 'tools', 'faqs', 'related'];
    protected array $imageFields = ['hero_image', 'hero_image_sm', 'card_image'];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:180'],
            'name' => ['required', 'string', 'max:180'],
            'short_name' => ['nullable', 'string', 'max:120'],
            'icon' => ['nullable', 'string', 'max:60'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'h1_lead' => ['nullable', 'string', 'max:255'],
            'h1_gold' => ['nullable', 'string', 'max:255'],
            'lede' => ['nullable', 'string'],
            'portfolio_filter' => ['nullable', 'string', 'max:60'],
            'sort' => ['nullable', 'integer'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function formData(): array
    {
        return ['allServices' => \App\Models\Service::orderBy('sort')->get(['id', 'slug', 'name'])];
    }
}
