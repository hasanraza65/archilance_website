<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;

class FaqController extends ResourceController
{
    protected string $model = Faq::class;
    protected string $view = 'admin.faqs';
    protected string $route = 'admin.faqs';
    protected string $title = 'FAQ';
    protected array $searchable = ['question'];
    protected array $jsonFields = [];
    protected array $imageFields = [];

    protected function rules(?Model $item = null): array
    {
        return [
            'faq_category_id' => ['nullable', 'exists:faq_categories,id'],
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'sort' => ['nullable', 'integer'],
        ];
    }

    protected function formData(): array
    {
        return ['categories' => \App\Models\FaqCategory::orderBy('sort')->get()];
    }
}
