<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'services')->first();

        return view('front.services.index', [
            'page' => $page,
            'seo' => [
                'title' => $page?->meta_title ?: 'Architecture Outsourcing Services',
                'description' => $page?->meta_description,
                'image' => $page?->og_image ?: $page?->hero_image,
                'canonical' => url()->current(),
            ],
            'services' => Service::published()->ordered()->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_published, 404);

        return view('front.services.show', [
            'service' => $service,
            'seo' => [
                'title' => $service->meta_title ?: $service->name,
                'description' => $service->meta_description,
                'keywords' => $service->meta_keywords,
                'image' => $service->og_image ?: $service->hero_image,
                'imageAlt' => $service->image_alt,
                'canonical' => $service->canonical ?: url()->current(),
                'noindex' => (bool) $service->noindex,
            ],
            'related' => $service->relatedServices(),
            'projects' => $service->projects()->get(),
        ]);
    }
}
