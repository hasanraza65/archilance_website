<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /** Everything that carries SEO fields, in one auditable table. */
    protected function sources(): array
    {
        return [
            'services' => ['model' => Service::class, 'label' => 'Services', 'route' => 'admin.services.edit', 'name' => 'name'],
            'projects' => ['model' => Project::class, 'label' => 'Projects', 'route' => 'admin.projects.edit', 'name' => 'title'],
            'posts' => ['model' => Post::class, 'label' => 'Blog posts', 'route' => 'admin.posts.edit', 'name' => 'title'],
            'pages' => ['model' => Page::class, 'label' => 'Pages', 'route' => 'admin.pages.edit', 'name' => 'title'],
            // only the members that actually resolve to a public URL
            'team' => ['model' => TeamMember::class, 'label' => 'Team profiles', 'route' => 'admin.team.edit', 'name' => 'name',
                       'query' => fn ($q) => $q->withProfile()],
        ];
    }

    public function index(Request $request)
    {
        $rows = [];
        foreach ($this->sources() as $key => $src) {
            $query = $src['model']::query();
            if (isset($src['query'])) {
                $query = $src['query']($query);
            }

            foreach ($query->get() as $item) {
                $title = (string) ($item->meta_title ?: '');
                $desc = (string) ($item->meta_description ?: '');

                $issues = [];
                if ($title === '') $issues[] = 'Missing title';
                elseif (mb_strlen($title) > 65) $issues[] = 'Title too long (' . mb_strlen($title) . ')';
                elseif (mb_strlen($title) < 25) $issues[] = 'Title very short';

                if ($desc === '') $issues[] = 'Missing description';
                elseif (mb_strlen($desc) > 165) $issues[] = 'Description too long (' . mb_strlen($desc) . ')';
                elseif (mb_strlen($desc) < 70) $issues[] = 'Description very short';

                if ($item->noindex) $issues[] = 'noindex';

                $rows[] = [
                    'type' => $src['label'],
                    'name' => $item->{$src['name']},
                    'title' => $title,
                    'titleLen' => mb_strlen($title),
                    'desc' => $desc,
                    'descLen' => mb_strlen($desc),
                    'issues' => $issues,
                    'edit' => route($src['route'], $item),
                ];
            }
        }

        usort($rows, fn ($a, $b) => count($b['issues']) <=> count($a['issues']));

        return view('admin.seo.index', [
            'rows' => $rows,
            'clean' => count(array_filter($rows, fn ($r) => ! $r['issues'])),
            'total' => count($rows),
        ]);
    }
}
