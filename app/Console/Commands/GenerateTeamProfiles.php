<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Fills in the profile-page content for every team member.
 *
 * Everything written here is derived from data the record already holds — the
 * person's role, the blurb the studio wrote for the org chart, their education
 * note and their real position in the reporting tree. Nothing biographical is
 * invented: no years of service, no employers, no awards, no project counts.
 * The toolsets are the ones the role implies at an architecture studio, so
 * they are a starting point for an editor to confirm rather than a claim.
 *
 * Only blank fields are filled unless --force is passed, so re-running this
 * after an editor has been through the panel is always safe.
 */
class GenerateTeamProfiles extends Command
{
    protected $signature = 'team:profiles
                            {--force : Overwrite fields that already have content}
                            {--slug= : Only this member}';

    protected $description = 'Generate profile-page content for team members from their existing record';

    /** Roles that are a department card in the chart, not a person. */
    protected array $departments = ['outsource-department', 'seo-team'];

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        $query = TeamMember::query()->orderBy('sort')->orderBy('id');
        if ($slug = $this->option('slug')) {
            $query->where('slug', $slug);
        }

        $members = $query->get();
        $touched = 0;

        foreach ($members as $m) {
            // Departments stay in the chart but get no page of their own.
            if (in_array($m->slug, $this->departments, true)) {
                if ($m->has_profile) {
                    $m->has_profile = false;
                    $m->save();
                    $this->line("  <fg=yellow>dept</> {$m->name} — profile page disabled");
                    $touched++;
                }
                continue;
            }

            $arch = $this->archetype($m->role);
            $reports = TeamMember::where('parent_id', $m->id)->where('is_published', true)->count();

            $set = [
                'headline' => $this->headline($m, $arch),
                'bio' => $this->bio($m, $arch, $reports),
                'focus' => $this->focus($m, $arch),
                'expertise' => $this->expertise($arch),
                'highlights' => $this->highlights($m, $reports),
                'education' => $this->education($m),
                'meta_title' => $this->metaTitle($m),
                'meta_description' => $this->metaDescription($m, $arch),
                'image_alt' => $m->name . ' — ' . $m->role . ' at ' . Setting::get('site_name', 'Archilance LLC'),
            ];

            $dirty = false;
            foreach ($set as $field => $value) {
                if ($value === null || $value === [] || $value === '') {
                    continue;
                }
                $current = $m->{$field};
                $blank = $current === null || $current === '' || $current === [];

                if ($blank || $force) {
                    $m->{$field} = $value;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $m->save();
                $touched++;
                $this->line("  <fg=green>ok</>   {$m->name} — {$arch}");
            }
        }

        $this->newLine();
        $this->info("Done. {$touched} of {$members->count()} records updated" . ($force ? ' (forced).' : '.'));
        $this->comment('Toolsets are inferred from each role — confirm them in Admin → Team.');

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------ mapping */

    /** Bucket a free-text role into one of the studio's disciplines. */
    protected function archetype(?string $role): string
    {
        $r = Str::lower((string) $role);

        return match (true) {
            str_contains($r, 'ceo'), str_contains($r, 'coo'), str_contains($r, 'cto') => 'leadership',
            str_contains($r, 'hr'), str_contains($r, 'recruit') => 'people',
            str_contains($r, 'bidding') => 'bidding',
            str_contains($r, 'business develop'), str_contains($r, 'bd ') => 'growth',
            str_contains($r, 'software'), str_contains($r, 'developer') && ! str_contains($r, 'business') => 'engineering',
            str_contains($r, 'render'), str_contains($r, 'visualiz'), str_contains($r, 'animation') => 'visualisation',
            str_contains($r, 'interior') => 'interior',
            str_contains($r, 'bim'), str_contains($r, 'revit') => 'bim',
            str_contains($r, 'project manager'), str_contains($r, 'manager') => 'delivery',
            str_contains($r, 'internee'), str_contains($r, 'intern') => 'internee',
            str_contains($r, 'executive') => 'exec',
            str_contains($r, 'junior') => 'junior',
            str_contains($r, 'designer') => 'design',
            default => 'architecture',
        };
    }

    protected function headline(TeamMember $m, string $arch): string
    {
        $first = Str::before($m->name, ' ') ?: $m->name;

        $lines = [
            'leadership' => "Sets the studio's direction and holds the standard every drawing set leaves on.",
            'people' => "Finds the architects behind the work and keeps the studio a place they stay.",
            'growth' => "First point of contact for new studios, and the one who scopes the work honestly.",
            'bidding' => "Turns a brief into a costed, deliverable proposal — before anyone draws a line.",
            'delivery' => "Runs projects end to end so deadlines are a plan, not a hope.",
            'engineering' => "Builds the internal tooling that keeps delivery fast and consistent.",
            'visualisation' => "Turns models into images that sell the scheme before it is built.",
            'interior' => "Resolves interiors down to the material, fixture and finish schedule.",
            'bim' => "Builds models that stay coordinated all the way to the permit set.",
            'design' => "Takes schemes from sketch to a resolved, buildable drawing set.",
            'internee' => "Learning the studio's workflow on live projects, supported by a senior lead.",
            'exec' => "Part of the studio's leadership, accountable for how the work lands.",
            'junior' => "Working across live projects and drawing sets alongside the senior team.",
            'architecture' => "Resolves plans, elevations and sections into sets that pass review.",
        ];

        return $lines[$arch] ?? "Part of the {$first} team at " . Setting::get('site_name', 'Archilance LLC') . '.';
    }

    protected function bio(TeamMember $m, string $arch, int $reports): string
    {
        $first = Str::before($m->name, ' ') ?: $m->name;
        $site = Setting::get('site_name', 'Archilance LLC');

        // First paragraph: the studio's own words, kept verbatim.
        $paras = [trim((string) $m->blurb)];

        // Second: how the role sits in the studio. Reporting numbers are real.
        $second = match ($arch) {
            'leadership' => "{$first} works across every discipline in the studio — architecture, BIM, documentation and visualisation — and is accountable for what the client finally receives.",
            'people' => "{$first} owns hiring and the day-to-day of the studio, from first interview through onboarding and the working culture that keeps the team together.",
            'growth' => "{$first} handles the conversation before the work starts: understanding the brief, scoping it honestly against the studio's capacity, and staying the point of contact once a project is live.",
            'bidding' => "{$first} sits between the client's brief and the studio's delivery teams, pricing and packaging work so what is promised is what gets drawn.",
            'delivery' => "{$first} plans the run of a project — who draws what, in what order, against which deadline — and reviews the set before it goes out.",
            'engineering' => "{$first} builds and maintains the internal tools the studio runs on, so the delivery teams spend their time on drawings rather than admin.",
            'visualisation' => "{$first} works from the live model, so the imagery reflects the scheme as drawn rather than a separate interpretation of it.",
            'interior' => "{$first} works alongside the architectural teams so interiors and base build stay coordinated in the same model.",
            'bim' => "{$first} keeps the model as the single source of truth — coordinated, clash-checked and ready to cut a permit set from.",
            'design' => "{$first} works through the drawing set from first plan to issue, coordinating with the wider team as the scheme resolves.",
            'internee' => "{$first} is on the studio's internship programme, working on live project tasks under the supervision of a senior team member.",
            'exec' => "{$first} works across the studio's disciplines and is accountable for delivery on the projects they oversee.",
            'junior' => "{$first} works across live projects with the senior team, taking on drawing packages and detail work as schemes progress.",
            default => "{$first} works within the studio's architecture teams on live client projects at {$site}.",
        };
        $paras[] = $second;

        if ($reports > 0) {
            $word = $reports === 1 ? 'person' : 'people';
            $paras[] = "{$first} leads a team of {$reports} {$word} inside the studio's org chart.";
        }

        return implode("\n\n", array_filter($paras));
    }

    protected function focus(TeamMember $m, string $arch): array
    {
        $map = [
            'leadership' => [
                ['Studio direction', 'Sets priorities across disciplines and decides what the studio takes on.'],
                ['Delivery standards', 'Owns the quality bar every drawing set is measured against before issue.'],
                ['Client relationships', 'Holds the long-term partnerships the studio is built on.'],
            ],
            'people' => [
                ['Technical recruitment', 'Sources and assesses architects, modellers and visualisers.'],
                ['Onboarding', 'Gets new team members productive on live projects quickly.'],
                ['Studio operations', 'Keeps policy, performance and day-to-day HR running.'],
            ],
            'growth' => [
                ['New enquiries', 'First response on incoming work, from brief to scoped proposal.'],
                ['Account relationships', 'Stays the named contact once a project is running.'],
                ['Scoping', 'Matches what a client needs against what the studio can commit to.'],
            ],
            'bidding' => [
                ['Proposals', 'Prepares and manages bids end to end.'],
                ['Costing', 'Prices scope realistically against studio hours.'],
                ['Process', 'Tracks win rates and tightens how the studio bids.'],
            ],
            'delivery' => [
                ['Project planning', 'Sequences the work and assigns it across the team.'],
                ['Quality review', 'Checks sets against the brief and the standard before issue.'],
                ['Client updates', 'Keeps progress visible so deadlines never arrive as a surprise.'],
            ],
            'engineering' => [
                ['Internal tooling', 'Builds the systems the studio runs delivery on.'],
                ['Automation', 'Removes repeat work from the drawing and admin pipeline.'],
                ['Maintenance', 'Keeps the platform stable, fast and secure.'],
            ],
            'visualisation' => [
                ['Rendering', 'Produces stills and sequences from the live project model.'],
                ['Lighting and materials', 'Sets up scenes so the output reads as the scheme intends.'],
                ['Post-production', 'Finishes imagery to presentation and marketing standard.'],
            ],
            'interior' => [
                ['Interior packages', 'Develops layouts, finishes and fixed joinery.'],
                ['Materials and FF&E', 'Selects and schedules finishes, fittings and equipment.'],
                ['Coordination', 'Keeps interiors aligned with the architectural model.'],
            ],
            'bim' => [
                ['Model authoring', 'Builds and maintains the federated project model.'],
                ['Coordination', 'Runs clash detection and resolves conflicts across disciplines.'],
                ['Documentation', 'Cuts permit and construction sets straight from the model.'],
            ],
            'design' => [
                ['Design development', 'Resolves plans, elevations and sections through the set.'],
                ['Documentation', 'Produces coordinated drawings ready for review.'],
                ['Coordination', 'Works with the wider team as the scheme changes.'],
            ],
            'internee' => [
                ['Supervised project work', 'Takes on live tasks with a senior team member reviewing.'],
                ['Learning the toolset', "Building fluency in the studio's software and standards."],
            ],
            'exec' => [
                ['Oversight', 'Accountable for delivery across the work they lead.'],
                ['Standards', 'Holds the quality bar the studio issues against.'],
            ],
            'junior' => [
                ['Drawing packages', 'Produces plans, elevations and details on live projects.'],
                ['Model support', 'Keeps project models tidy and up to date.'],
                ['Review', 'Works to senior markups and closes out comments.'],
            ],
            'architecture' => [
                ['Drawing sets', 'Resolves plans, elevations, sections and details.'],
                ['Permit documentation', 'Formats sets to the authority the project is filed with.'],
                ['Coordination', 'Aligns the architectural package with the wider model.'],
            ],
        ];

        $rows = $map[$arch] ?? $map['architecture'];

        return array_map(fn ($r) => ['title' => $r[0], 'text' => $r[1]], $rows);
    }

    protected function expertise(string $arch): array
    {
        $map = [
            'leadership' => ['Revit', 'BIM standards', 'Delivery QA', 'Client strategy', 'Studio operations'],
            'people' => ['Technical recruitment', 'Onboarding', 'Performance management', 'HR policy'],
            'growth' => ['Client scoping', 'Proposals', 'CRM', 'Account management'],
            'bidding' => ['Bid preparation', 'Cost estimation', 'Scope analysis', 'Proposal writing'],
            'delivery' => ['Revit', 'Navisworks', 'BIM 360', 'Project scheduling', 'Drawing review'],
            'engineering' => ['Laravel', 'JavaScript', 'MySQL', 'REST APIs', 'Git'],
            'visualisation' => ['3ds Max', 'V-Ray', 'Corona', 'Lumion', 'Enscape', 'Photoshop', 'After Effects'],
            'interior' => ['SketchUp', 'Revit', 'Enscape', 'AutoCAD', 'FF&E scheduling', 'Photoshop'],
            'bim' => ['Revit', 'Navisworks', 'Dynamo', 'BIM 360', 'ReCap', 'Clash detection'],
            'design' => ['Revit', 'AutoCAD', 'SketchUp', 'Enscape', 'Adobe Creative Suite'],
            'internee' => ['Revit', 'AutoCAD', 'SketchUp'],
            'exec' => ['Revit', 'Delivery QA', 'Studio operations'],
            'junior' => ['Revit', 'AutoCAD', 'SketchUp', 'Adobe Creative Suite'],
            'architecture' => ['Revit', 'AutoCAD', 'SketchUp', 'Enscape', 'Construction documentation'],
        ];

        $tools = $map[$arch] ?? $map['architecture'];

        return array_map(fn ($t) => ['name' => $t], $tools);
    }

    /**
     * Only tiles that are true of the record: their division, and how many
     * people actually report to them. No invented numbers.
     */
    protected function highlights(TeamMember $m, int $reports): array
    {
        $tiles = [['value' => $m->teamLabel(), 'label' => 'Division']];

        if ($reports > 0) {
            $tiles[] = ['value' => (string) $reports, 'label' => 'Direct ' . Str::plural('report', $reports)];
        }

        if ($m->parent) {
            $tiles[] = ['value' => $m->parent->name, 'label' => 'Reports to'];
        }

        return $tiles;
    }

    /**
     * The seeded notes read:
     *   "B.Arch — University of X, Lahore, 2016 · M.A. Design — College of Y, 2018"
     * so a middle dot separates qualifications and an em dash separates the
     * award from where it was taken. Anything that does not read as education
     * is left alone rather than forced into the shape.
     */
    protected function education(TeamMember $m): array
    {
        $note = trim((string) $m->extra);
        if ($note === '' || ! TeamMember::looksLikeEducation($note)) {
            return [];
        }

        $rows = [];

        foreach (preg_split('/\s*[·•|]\s*/u', $note) ?: [] as $chunk) {
            $chunk = trim($chunk);
            if ($chunk === '') {
                continue;
            }

            $parts = preg_split('/\s*[—–]\s*/u', $chunk, 2);
            $degree = trim($parts[0] ?? '');
            $school = trim($parts[1] ?? '');

            // A few notes lead with a job title and put the qualification
            // after the dash. Keep the award, drop the title — it is already
            // on the record as the role.
            if ($degree !== '' && $school !== ''
                && ! TeamMember::looksLikeEducation($degree)
                && TeamMember::looksLikeEducation($school)) {
                $degree = $school;
                $school = '';
            }

            // A trailing year belongs in its own column, wherever it landed.
            $year = '';
            [$school, $year] = $this->splitYear($school, $year);
            [$degree, $year] = $this->splitYear($degree, $year);

            if ($degree === '' && $school === '') {
                continue;
            }

            $rows[] = ['degree' => $degree ?: $school, 'school' => $degree ? $school : '', 'year' => $year];
        }

        return $rows;
    }

    /** Pull a trailing year off a fragment, leaving an already-found one alone. */
    protected function splitYear(string $text, string $year): array
    {
        if ($year !== '' || $text === '' || ! preg_match('/,?\s*((?:19|20)\d{2})\s*$/u', $text, $hit)) {
            return [$text, $year];
        }

        return [trim(preg_replace('/,?\s*(?:19|20)\d{2}\s*$/u', '', $text)), $hit[1]];
    }

    protected function metaTitle(TeamMember $m): string
    {
        $title = $m->role ? "{$m->name} — {$m->role}" : $m->name;

        // The SEO audit flags anything past 65 characters.
        return Str::limit($title, 62, '');
    }

    protected function metaDescription(TeamMember $m, string $arch): string
    {
        $role = $m->role ?: 'Team member';
        $site = Setting::get('site_name', 'Archilance LLC');
        $blurb = trim((string) $m->blurb);

        $text = "{$m->name} is {$role} at {$site}. " . $blurb;

        // The audit wants 70–165; trim long blurbs rather than pad short ones.
        return Str::limit(preg_replace('/\s+/', ' ', $text), 158);
    }
}
