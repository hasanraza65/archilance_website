<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function show(TeamMember $member)
    {
        abort_unless($member->hasPublicProfile(), 404);

        $member->load(['parent', 'children' => fn ($q) => $q->where('is_published', true)]);

        // A profile reads best when the description is a real sentence about
        // the person, so fall back through what the editor actually filled in.
        $description = $member->meta_description
            ?: $member->headline
            ?: $member->blurb;

        return view('front.team.show', [
            'member' => $member,
            'reports' => $member->children,
            'peers' => $member->peers(),
            'seo' => [
                'title' => $member->meta_title ?: trim($member->name . ' — ' . $member->role),
                'description' => $description ? \Illuminate\Support\Str::limit(strip_tags($description), 158) : null,
                'keywords' => $member->meta_keywords,
                'image' => $member->og_image ?: $member->portrait(),
                'imageAlt' => $member->photoAlt(),
                'canonical' => $member->canonical ?: url()->current(),
                'noindex' => (bool) $member->noindex,
                'type' => 'profile',
            ],
        ]);
    }
}
