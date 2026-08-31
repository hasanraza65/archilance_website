<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $fillable = ['slug', 'name', 'sort'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort')->orderBy('id');
    }

    public function publishedFaqs()
    {
        return $this->faqs()->where('is_published', true);
    }
}
