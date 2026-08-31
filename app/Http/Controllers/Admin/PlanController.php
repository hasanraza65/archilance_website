<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanController extends ResourceController
{
    protected string $model = Plan::class;
    protected string $view = 'admin.plans';
    protected string $route = 'admin.plans';
    protected string $title = 'Plan';
    protected array $searchable = ['name'];
    protected array $jsonFields = ['features'];
    protected array $imageFields = [];
    protected array $boolFields = ['is_published', 'featured'];

    protected function rules(?Model $item = null): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'string', 'max:60'],
            'period' => ['nullable', 'string', 'max:60'],
            'hours' => ['nullable', 'string', 'max:120'],
            'rate_note' => ['nullable', 'string', 'max:200'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'integer'],
        ];
    }
}
