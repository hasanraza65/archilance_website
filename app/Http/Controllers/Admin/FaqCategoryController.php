<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Model;

class FaqCategoryController extends ResourceController
{
    protected string $model = FaqCategory::class;
    protected string $view = 'admin.faq-categories';
    protected string $route = 'admin.faq-categories';
    protected string $title = 'FAQ category';
    protected array $searchable = ['name', 'slug'];
    protected array $jsonFields = [];
    protected array $imageFields = [];
    protected array $boolFields = [];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'sort' => ['nullable', 'integer'],
        ];
    }
}
