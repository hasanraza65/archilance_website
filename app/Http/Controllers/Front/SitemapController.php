<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;

class SitemapController extends Controller
{
    /** Generated from the database, so a new page is in the sitemap immediately. */
    public function index()
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'weekly'],
            ['loc' => route('services.index'), 'priority' => '0.9', 'freq' => 'monthly'],
            ['loc' => route('projects.index'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => route('pricing'), 'priority' => '0.9', 'freq' => 'monthly'],
            ['loc' => route('about'), 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => route('faq'), 'priority' => '0.7', 'freq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => route('blog.index'), 'priority' => '0.7', 'freq' => 'weekly'],
        ];

        foreach (Service::published()->ordered()->get() as $svc) {
            $urls[] = ['loc' => route('services.show', $svc), 'priority' => '0.8',
                       'freq' => 'monthly', 'lastmod' => $svc->updated_at];
        }
        foreach (Project::published()->ordered()->get() as $project) {
            if ($project->noindex) {
                continue;
            }
            $urls[] = ['loc' => route('projects.show', $project), 'priority' => '0.7',
                       'freq' => 'monthly', 'lastmod' => $project->updated_at];
        }
        foreach (TeamMember::withProfile()->orderBy('sort')->get() as $member) {
            if ($member->noindex) {
                continue;
            }
            $urls[] = ['loc' => route('team.show', $member), 'priority' => '0.5',
                       'freq' => 'monthly', 'lastmod' => $member->updated_at];
        }
        foreach (Post::live()->get() as $post) {
            $urls[] = ['loc' => route('blog.show', $post), 'priority' => '0.6',
                       'freq' => 'monthly', 'lastmod' => $post->updated_at];
        }
        foreach (Page::where('is_published', true)
                     ->whereNotIn('slug', ['home', 'about', 'faq', 'contact', 'services', 'projects', 'blog', 'pricing'])
                     ->get() as $page) {
            $urls[] = ['loc' => route('page', $page->slug), 'priority' => '0.5',
                       'freq' => 'yearly', 'lastmod' => $page->updated_at];
        }

        $images = Project::published()->ordered()->get();

        return response()
            ->view('front.sitemap', compact('urls', 'images'))
            ->header('Content-Type', 'application/xml');
    }
}
