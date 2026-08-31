<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Services', 'value' => Service::count(), 'route' => 'admin.services.index', 'icon' => 'layers'],
                ['label' => 'Projects', 'value' => Project::count(), 'route' => 'admin.projects.index', 'icon' => 'image'],
                ['label' => 'Blog posts', 'value' => Post::count(), 'route' => 'admin.posts.index', 'icon' => 'edit'],
                ['label' => 'Team members', 'value' => TeamMember::count(), 'route' => 'admin.team.index', 'icon' => 'users'],
                ['label' => 'FAQs', 'value' => Faq::count(), 'route' => 'admin.faqs.index', 'icon' => 'help'],
                ['label' => 'Testimonials', 'value' => Testimonial::count(), 'route' => 'admin.testimonials.index', 'icon' => 'star'],
                ['label' => 'Pages', 'value' => Page::count(), 'route' => 'admin.pages.index', 'icon' => 'file'],
                ['label' => 'Media', 'value' => Media::count(), 'route' => 'admin.media.index', 'icon' => 'folder'],
            ],
            'unread' => Enquiry::where('is_read', false)->count(),
            'enquiries' => Enquiry::latest()->limit(6)->get(),
            'drafts' => Post::where('is_published', false)->latest()->limit(5)->get(),
            'seoGaps' => [
                'services' => Service::where(fn ($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''))->count(),
                'projects' => Project::where(fn ($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''))->count(),
                'posts' => Post::where(fn ($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''))->count(),
            ],
        ]);
    }
}
