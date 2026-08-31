<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Model;

class PostCategoryController extends ResourceController
{
    protected string $model = PostCategory::class;
    protected string $view = 'admin.post-categories';
    protected string $route = 'admin.post-categories';
    protected string $title = 'Category';
    protected array $searchable = ['name', 'slug'];
    protected array $jsonFields = [];
    protected array $imageFields = [];
    protected array $boolFields = [];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'sort' => ['nullable', 'integer'],
        ];
    }
}
