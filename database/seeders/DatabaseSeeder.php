<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /** Read one of the extracted JSON payloads. */
    protected function data(string $name): array
    {
        $path = database_path("seeders/data/{$name}.json");

        return is_file($path) ? json_decode(file_get_contents($path), true) : [];
    }

    public function run(): void
    {
        $this->users();
        $this->settings();
        $this->pages();
        $this->services();
        $this->projects();
        $this->team();
        $this->faqs();
        $this->testimonials();
        $this->plans();
        $this->blog();

        $this->command->info('Seeded: '
            . Service::count() . ' services, '
            . Project::count() . ' projects, '
            . TeamMember::count() . ' team, '
            . Faq::count() . ' FAQs, '
            . Testimonial::count() . ' testimonials, '
            . Plan::count() . ' plans, '
            . Post::count() . ' posts, '
            . Setting::count() . ' settings.');
    }

    protected function users(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@archilance.net'],
            [
                'name' => 'Archilance Admin',
                'password' => Hash::make('f17@AYDS'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ]
        );
    }

    protected function settings(): void
    {
        foreach ($this->data('settings') as $row) {
            Setting::updateOrCreate(['key' => $row['key']], $row);
        }
    }

    protected function pages(): void
    {
        foreach ($this->data('pages') as $row) {
            Page::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    protected function services(): void
    {
        foreach ($this->data('services') as $row) {
            Service::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    protected function projects(): void
    {
        foreach ($this->data('projects') as $row) {
            Project::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    /**
     * Two passes: every member first, then the parent links. The chart is a
     * self-referencing tree, so a child can appear before its manager.
     */
    protected function team(): void
    {
        $rows = $this->data('team');

        foreach ($rows as $row) {
            $insert = $row;
            unset($insert['parent']);
            TeamMember::updateOrCreate(['slug' => $insert['slug']], $insert);
        }

        $ids = TeamMember::pluck('id', 'slug');
        foreach ($rows as $row) {
            if (! empty($row['parent'])) {
                TeamMember::where('slug', $row['slug'])
                    ->update(['parent_id' => $ids[$row['parent']] ?? null]);
            }
        }
    }

    protected function faqs(): void
    {
        foreach ($this->data('faqs') as $row) {
            $cat = FaqCategory::updateOrCreate(
                ['slug' => $row['category_slug']],
                ['name' => $row['category_name'], 'sort' => $row['category_sort']]
            );

            Faq::updateOrCreate(
                ['question' => $row['question']],
                [
                    'faq_category_id' => $cat->id,
                    'answer' => $row['answer'],
                    'sort' => $row['sort'],
                    'is_published' => true,
                ]
            );
        }
    }

    protected function testimonials(): void
    {
        foreach ($this->data('testimonials') as $row) {
            Testimonial::updateOrCreate(
                ['name' => $row['name'], 'quote' => $row['quote']],
                $row
            );
        }
    }

    protected function plans(): void
    {
        foreach ($this->data('plans') as $row) {
            Plan::updateOrCreate(['name' => $row['name']], $row);
        }
    }

    /** A starter category set plus three posts so the blog is not an empty shell. */
    protected function blog(): void
    {
        $cats = [
            ['slug' => 'revit-bim', 'name' => 'Revit & BIM', 'sort' => 1],
            ['slug' => 'visualisation', 'name' => 'Visualisation', 'sort' => 2],
            ['slug' => 'practice', 'name' => 'Practice', 'sort' => 3],
        ];
        foreach ($cats as $c) {
            PostCategory::updateOrCreate(['slug' => $c['slug']], $c);
        }

        $author = User::where('email', 'admin@archilance.net')->first();

        $posts = [
            [
                'slug' => 'what-lod-actually-means-on-a-real-project',
                'title' => 'What LOD actually means on a real project',
                'category' => 'revit-bim',
                'excerpt' => 'LOD 100 to 400 gets quoted constantly and agreed rarely. Here is how we pin it down per element category before a single wall goes in.',
                'cover' => 'assets/img/portfolio/accessory-dwelling-unit-card.webp',
                'body' => "<p>Level of Development is the single most common source of scope disputes in outsourced BIM work. Everyone nods at LOD 350 in the kick-off call, and three weeks later one party expected fabrication-ready connections while the other modelled generic assemblies.</p><h2>Agree it per category, not per project</h2><p>A project is not one LOD. Walls might be 350 because they drive the permit set, while furniture stays at 100 because nobody is building from it. We write the LOD down per element category before modelling starts and attach it to the scope document.</p><h2>What each level buys you</h2><p><b>LOD 100</b> is massing — volume, orientation and area only. It is enough to test a planning envelope and nothing more.</p><p><b>LOD 200</b> gives generic placeholders with approximate size and location. Good for coordination, not for pricing.</p><p><b>LOD 300</b> is where most permit documentation lives: specific assemblies, accurate dimensions, real quantities.</p><p><b>LOD 350</b> adds the interfaces between systems — the connections and penetrations that clash detection depends on.</p><p><b>LOD 400</b> is fabrication detail. Expensive, and only worth it where something is genuinely being made from the model.</p><h2>The practical rule</h2><p>Model to the level your next decision needs, and no further. Over-modelling is the most expensive habit in BIM because it feels like progress.</p>",
            ],
            [
                'slug' => 'why-your-renders-look-fake-and-how-to-fix-it',
                'title' => 'Why your renders look fake, and how to fix it',
                'category' => 'visualisation',
                'excerpt' => 'Nine times out of ten it is not the render engine. It is camera height, material scale and the fact that nothing in the scene is dirty.',
                'cover' => 'assets/img/portfolio/modern-living-room-card.webp',
                'body' => "<p>When a client says a render looks like CGI, they rarely mean the lighting solver. They mean something in the image contradicts how the eye expects a photograph to behave.</p><h2>Camera height is the first tell</h2><p>Architectural cameras default to somewhere around 1.6m. Renders shot from 3m read as a doll's house instantly, because no human has ever seen a room from there. Match the camera to how the space will actually be experienced.</p><h2>Material scale beats material quality</h2><p>A perfect oak texture tiled at the wrong scale looks worse than a mediocre one at the right scale. Check the plank width against something known — a door leaf, a light switch — before touching the shader.</p><h2>Nothing real is perfectly clean</h2><p>Edge wear, slight reflection variance, a rug that is not perfectly parallel to the wall. Perfect symmetry and spotless surfaces are the fastest route to the uncanny valley.</p><h2>Grade it like a photograph</h2><p>A raw render is a negative, not a final image. Contrast, subtle vignetting and a considered colour temperature do more for believability than another two hours of sampling.</p>",
            ],
            [
                'slug' => 'how-to-brief-an-outsourced-drafting-team',
                'title' => 'How to brief an outsourced drafting team',
                'category' => 'practice',
                'excerpt' => 'The difference between a good outsourcing relationship and a frustrating one is almost always the first 20 minutes of the brief.',
                'cover' => 'assets/img/portfolio/construction-permit-set-card.webp',
                'body' => "<p>Firms who get excellent work out of an outsourced team are not luckier. They brief better, and they front-load the things that are expensive to change later.</p><h2>Send the template before the project</h2><p>Your Revit template, title block, shared parameters and sheet naming convention define what correct looks like. Sending them with the first job saves a full round of rework.</p><h2>Say what the drawing is for</h2><p>A plan for a planning submission, a contractor pricing exercise and a client presentation are three different drawings. Stating the audience changes what gets emphasised.</p><h2>Name the deadline that actually matters</h2><p>Not the internal buffer date — the real one. A good partner will tell you honestly whether it is achievable, and that conversation is far cheaper before work starts.</p><h2>Give feedback on the drawing, not in prose</h2><p>A marked-up PDF resolves in one round what three paragraphs of email resolves in four.</p>",
            ],
        ];

        foreach ($posts as $i => $p) {
            $cat = PostCategory::where('slug', $p['category'])->first();

            Post::updateOrCreate(['slug' => $p['slug']], [
                'title' => $p['title'],
                'excerpt' => $p['excerpt'],
                'body' => $p['body'],
                'cover_image' => $p['cover'],
                'image_alt' => $p['title'],
                'post_category_id' => $cat?->id,
                'user_id' => $author?->id,
                'read_minutes' => max(2, (int) round(str_word_count(strip_tags($p['body'])) / 200)),
                'meta_title' => $p['title'] . ' | Archilance LLC',
                'meta_description' => Str::limit($p['excerpt'], 155),
                'published_at' => now()->subDays(($i + 1) * 6),
                'is_published' => true,
            ]);
        }
    }
}
