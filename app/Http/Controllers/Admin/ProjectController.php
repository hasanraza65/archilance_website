<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class ProjectController extends ResourceController
{
    protected string $model = Project::class;
    protected string $view = 'admin.projects';
    protected string $route = 'admin.projects';
    protected string $title = 'Project';
    protected array $searchable = ['title', 'slug'];
    protected array $jsonFields = ['tags', 'facts', 'gallery', 'deliverables'];
    protected array $imageFields = ['card_image', 'full_image'];
    protected array $boolFields = ['is_published', 'featured'];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:180'],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'category_label' => ['nullable', 'string', 'max:120'],
            'categories' => ['nullable', 'string', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'card_width' => ['nullable', 'integer'],
            'card_height' => ['nullable', 'integer'],
            'span' => ['nullable', 'in:wide,tall'],
            'sort' => ['nullable', 'integer'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
        ];
    }
}
