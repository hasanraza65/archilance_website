<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostController extends ResourceController
{
    protected string $model = Post::class;
    protected string $view = 'admin.posts';
    protected string $route = 'admin.posts';
    protected string $title = 'Post';
    protected array $searchable = ['title', 'slug'];
    protected array $jsonFields = [];
    protected array $imageFields = ['cover_image'];
    protected string $orderBy = 'id';
    protected bool $sortable = false;

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:200'],
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'body' => ['nullable', 'string'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'read_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function formData(): array
    {
        return ['categories' => \App\Models\PostCategory::orderBy('sort')->get()];
    }
}
