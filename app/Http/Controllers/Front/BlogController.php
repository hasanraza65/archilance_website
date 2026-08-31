<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $page = Page::where('slug', 'blog')->first();

        $posts = Post::live()->with(['category', 'author'])
            ->when($request->query('category'), fn ($q, $slug) =>
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->query('q'), fn ($q, $term) =>
                $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")
                                       ->orWhere('excerpt', 'like', "%{$term}%")))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('front.blog.index', [
            'page' => $page,
            'seo' => [
                'title' => $page?->meta_title ?: 'Blog',
                'description' => $page?->meta_description,
                'canonical' => url()->current(),
            ],
            'posts' => $posts,
            'categories' => PostCategory::orderBy('sort')->withCount(['posts' => fn ($q) => $q->where('is_published', true)])->get(),
            'active' => $request->query('category'),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published && (! $post->published_at || $post->published_at->isPast()), 404);

        return view('front.blog.show', [
            'post' => $post->load(['category', 'author']),
            'seo' => [
                'title' => $post->meta_title ?: $post->title,
                'description' => $post->meta_description ?: $post->excerpt,
                'keywords' => $post->meta_keywords,
                'image' => $post->og_image ?: $post->cover_image,
                'imageAlt' => $post->image_alt,
                'canonical' => $post->canonical ?: url()->current(),
                'noindex' => (bool) $post->noindex,
                'type' => 'article',
            ],
            'related' => Post::live()->where('id', '!=', $post->id)
                ->when($post->post_category_id, fn ($q) => $q->where('post_category_id', $post->post_category_id))
                ->latest('published_at')->limit(3)->get(),
        ]);
    }
}
