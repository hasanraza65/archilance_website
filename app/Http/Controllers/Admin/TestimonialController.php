<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialController extends ResourceController
{
    protected string $model = Testimonial::class;
    protected string $view = 'admin.testimonials';
    protected string $route = 'admin.testimonials';
    protected string $title = 'Testimonial';
    protected array $searchable = ['name', 'company'];
    protected array $jsonFields = [];
    protected array $imageFields = ['avatar'];

    protected function rules(?Model $item = null): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'company' => ['nullable', 'string', 'max:180'],
            'platform' => ['nullable', 'string', 'max:60'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string'],
            'sort' => ['nullable', 'integer'],
        ];
    }
}
