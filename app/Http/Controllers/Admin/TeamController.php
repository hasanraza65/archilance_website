<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;

class TeamController extends ResourceController
{
    protected string $model = TeamMember::class;
    protected string $view = 'admin.team';
    protected string $route = 'admin.team';
    protected string $title = 'Team member';
    protected array $searchable = ['name', 'role', 'slug'];
    protected array $jsonFields = ['focus', 'expertise', 'highlights', 'education'];
    protected array $imageFields = ['photo', 'photo_full'];
    protected array $boolFields = ['is_published', 'is_leadership', 'has_profile', 'noindex'];

    protected function rules(?Model $item = null): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:180'],
            'name' => ['required', 'string', 'max:180'],
            'role' => ['nullable', 'string', 'max:180'],
            'headline' => ['nullable', 'string', 'max:255'],
            'team' => ['required', 'string', 'max:20'],
            'blurb' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'quote' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:180'],
            'extra' => ['nullable', 'string'],

            'email' => ['nullable', 'email', 'max:180'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'behance' => ['nullable', 'url', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],

            'parent_id' => ['nullable', 'exists:team_members,id'],
            'sort' => ['nullable', 'integer'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function formData(): array
    {
        return [
            'parents' => TeamMember::orderBy('sort')->get(),
            'teams' => TeamMember::TEAMS,
        ];
    }
}
