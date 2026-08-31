<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Project;

class ProjectController extends Controller
{
    /** Filter keys shown as pills, with a live count for each. */
    public const FILTERS = [
        'design' => 'Architectural Design',
        'interior' => 'Interior Design',
        'landscape' => 'Landscape',
        'bim' => 'Revit, BIM & Drafting',
        'render' => '3D Rendering',
        'modeling' => '3D Modeling',
    ];

    public function show(Project $project)
    {
        abort_unless($project->is_published, 404);

        // Neighbours for the prev/next pager, in the same order as the archive.
        $all = Project::published()->ordered()->get();
        $i = $all->search(fn ($p) => $p->id === $project->id);

        return view('front.projects.show', [
            'project' => $project,
            'prev' => $i > 0 ? $all[$i - 1] : $all->last(),
            'next' => $i < $all->count() - 1 ? $all[$i + 1] : $all->first(),
            // Same discipline first, so the suggestions are actually related.
            'related' => $all->filter(fn ($p) => $p->id !== $project->id
                    && array_intersect($p->categoryList(), $project->categoryList()))
                ->take(3)->values(),
            'seo' => [
                'title' => $project->meta_title ?: $project->title . ' — ' . $project->category_label,
                'description' => $project->meta_description
                    ?: \Illuminate\Support\Str::limit(strip_tags($project->summary ?: $project->description), 158),
                'keywords' => $project->meta_keywords,
                'image' => $project->og_image ?: $project->full_image,
                'imageAlt' => $project->image_alt,
                'canonical' => $project->canonical ?: url()->current(),
                'noindex' => (bool) $project->noindex,
                'type' => 'article',
            ],
        ]);
    }

    public function index()
    {
        $page = Page::where('slug', 'projects')->first();
        $projects = Project::published()->ordered()->get();

        $counts = [];
        foreach (array_keys(self::FILTERS) as $key) {
            $counts[$key] = $projects->filter(fn ($p) => in_array($key, $p->categoryList(), true))->count();
        }

        return view('front.projects.index', [
            'page' => $page,
            'seo' => [
                'title' => $page?->meta_title ?: 'Architecture Projects Portfolio',
                'description' => $page?->meta_description,
                'image' => $page?->og_image ?: $projects->first()?->full_image,
                'canonical' => url()->current(),
            ],
            'projects' => $projects,
            'filters' => self::FILTERS,
            'counts' => $counts,
        ]);
    }
}
