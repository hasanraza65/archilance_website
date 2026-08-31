<?php

namespace App\Support;

/**
 * Declares every editable string on each page, grouped into sections.
 *
 * The admin form is rendered from this, and the Blade templates read the same
 * keys via Page::text(). Adding a field here makes it editable everywhere.
 * Every default below was taken from the template it renders into, so a page
 * with nothing saved looks exactly as designed.
 *
 * type: text | textarea | html | list | repeat
 *   html    rich text editor, rendered unescaped
 *   list    repeatable simple values
 *   repeat  repeatable rows, shaped by 'schema'
 */
class PageBlueprint
{
    public static function for(string $slug): array
    {
        return self::all()[$slug] ?? [];
    }

    /** Flat key => default map, used by Page::text() for fallbacks. */
    public static function defaults(string $slug): array
    {
        // text() calls this once per string on the page, so build it once.
        static $cache = [];

        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $out = [];
        foreach (self::for($slug) as $section) {
            foreach ($section['fields'] as $key => $field) {
                $out[$key] = $field['default'] ?? '';
            }
        }

        return $cache[$slug] = $out;
    }

    public static function all(): array
    {
        return [
            // ====================================================== home
            'home' => [
                'hero' => ['label' => 'Hero', 'fields' => [
                    'hero.badge' => ['label' => 'Badge', 'type' => 'html', 'default' => '<b>3-day free trial</b> · no card, no commitment'],
                    'hero.title1' => ['label' => 'Headline line 1', 'type' => 'html', 'default' => 'Unlock a full team of'],
                    'hero.title2' => ['label' => 'Headline line 2', 'type' => 'html', 'default' => '<em>architects</em> for the'],
                    'hero.title3' => ['label' => 'Headline line 3', 'type' => 'html', 'default' => 'price of a single hire.'],
                    'hero.sub' => ['label' => 'Intro paragraph', 'type' => 'html', 'default' => 'Revit drafting, BIM, permit sets, rendering and animation &mdash; from <b>$11.84 an hour</b>. Unlimited requests, one flat rate.'],
                    'hero.cta1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Start your free trial'],
                    'hero.cta2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'View our work'],
                    'hero.stat1_v' => ['label' => 'Stat 1 number', 'type' => 'text', 'default' => '6'],
                    'hero.stat1_l' => ['label' => 'Stat 1 label', 'type' => 'text', 'default' => 'Years of work'],
                    'hero.stat2_v' => ['label' => 'Stat 2 number', 'type' => 'text', 'default' => '600'],
                    'hero.stat2_l' => ['label' => 'Stat 2 label', 'type' => 'text', 'default' => 'Revit models'],
                    'hero.stat3_v' => ['label' => 'Stat 3 number', 'type' => 'text', 'default' => '5'],
                    'hero.stat3_l' => ['label' => 'Stat 3 label', 'type' => 'text', 'default' => 'Upwork rating'],
                    'hero.stat4_v' => ['label' => 'Stat 4 number', 'type' => 'text', 'default' => '24'],
                    'hero.stat4_l' => ['label' => 'Stat 4 label', 'type' => 'text', 'default' => 'Reply time'],
                ]],
                // The hero's right column is the inline quote form; these keys
                // fill its header, its opening step and the links beneath it.
                'trust' => ['label' => 'Hero trust signals', 'fields' => [
                    'trust.rating' => ['label' => 'Rating', 'type' => 'text', 'default' => '5.0'],
                    'trust.rating_note' => ['label' => 'Text after the rating', 'type' => 'text', 'default' => 'on Upwork across 19 reviews'],
                    'trust.clients' => ['label' => 'Client volume', 'type' => 'html', 'default' => '<b>40+</b> studios, builders &amp; developers served'],
                    'trust.governance' => ['label' => 'Governance', 'type' => 'html', 'default' => 'ISO 9001 certified &middot; NDA on request'],
                ]],
                'offer' => ['label' => 'Hero quote form', 'fields' => [
                    'offer.badge' => ['label' => 'Badge', 'type' => 'text', 'default' => 'Start today'],
                    'offer.title' => ['label' => 'Title', 'type' => 'html', 'default' => 'Join <span class="text-gold">Archilance LLC</span>'],
                    'offer.sub' => ['label' => 'Subtitle', 'type' => 'text', 'default' => 'One subscription to rule them all.'],
                    'offer.price_label' => ['label' => 'Word above the price', 'type' => 'text', 'default' => 'Only'],
                    'offer.price' => ['label' => 'Headline price', 'type' => 'text', 'default' => '11.84'],
                    'offer.price_unit' => ['label' => 'Price unit', 'type' => 'text', 'default' => '/hr'],
                    'offer.price_note' => ['label' => 'Price note', 'type' => 'text', 'default' => 'Billed $1,895/month for 160 hours · limited time offer'],
                    'offer.feat1' => ['label' => 'Promise 1 (shown on step 1)', 'type' => 'html', 'default' => 'Unlimited design requests &amp; revisions'],
                    'offer.feat2' => ['label' => 'Promise 2 (shown on step 1)', 'type' => 'html', 'default' => 'Pause or cancel any time'],
                    'offer.feat3' => ['label' => 'Promise 3 (shown on step 1)', 'type' => 'html', 'default' => 'Talk to architects, not account managers'],
                    'offer.call_title' => ['label' => 'Call row title', 'type' => 'text', 'default' => 'Book a 15-min intro call'],
                    'offer.call_sub' => ['label' => 'Call row subtitle', 'type' => 'text', 'default' => 'Schedule now'],
                    'offer.cta' => ['label' => 'Final button (sends the quote)', 'type' => 'text', 'default' => 'Get my quote'],
                    'offer.cta_alt' => ['label' => 'Link below the form', 'type' => 'text', 'default' => 'or see the plans'],
                ]],
                'why' => ['label' => 'Why Archilance', 'fields' => [
                    'why.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Why Archilance'],
                    'why.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Studio-grade output without the studio overhead'],
                    'why.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'One in-house drafter costs more than this entire team, and you brief it once: your standards, your templates, your deadlines.'],
                    'why.cells' => ['label' => 'Value cards', 'type' => 'repeat', 'schema' => [
                        ['key' => 'icon', 'label' => 'Sprite icon id', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea'],
                    ], 'default' => [
                        [
                            'icon' => 'i-wallet',
                            'title' => 'From $11.84 an hour',
                            'text' => 'Flat monthly pricing with no recruitment fees, benefits, software seats or idle payroll between projects.',
                        ],
                        [
                            'icon' => 'i-users',
                            'title' => 'A team, not a freelancer',
                            'text' => 'Architects, Revit technicians, 3D artists and a project manager working from one shared brief and one queue.',
                        ],
                        [
                            'icon' => 'i-refresh',
                            'title' => 'Pause, resume, scale',
                            'text' => 'Workload dropped this month? Pause your subscription and bank the remaining weeks for when it picks up again.',
                        ],
                        [
                            'icon' => 'i-shield',
                            'title' => 'ISO 9001 &amp; NDA-ready',
                            'text' => 'Documented quality process, signed NDAs before files move, and your CAD/BIM standards followed to the letter.',
                        ],
                    ]],
                ]],
                'services' => ['label' => 'Services section', 'fields' => [
                    'services.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Our architecture outsourcing services'],
                    'services.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Everything your practice outsources, under one roof'],
                    'services.cta' => ['label' => 'Button', 'type' => 'text', 'default' => 'Discuss your scope'],
                ]],
                'process' => ['label' => 'How it works', 'fields' => [
                    'process.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'How it works'],
                    'process.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'From brief to delivery in four moves'],
                    'process.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'No procurement cycle, no recruiting. Most clients have work moving within 48 hours of the first call.'],
                    'process.steps' => ['label' => 'Steps', 'type' => 'repeat', 'schema' => [
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea'],
                    ], 'default' => [
                        [
                            'title' => 'Book a 15-minute call',
                            'text' => 'Tell us the project type, the software you work in and the deadline. We tell you honestly whether we are the right fit.',
                        ],
                        [
                            'title' => 'Run a free trial',
                            'text' => 'Three free days on a single project so you can judge the output before any money moves. Not satisfied? We keep going free.',
                        ],
                        [
                            'title' => 'Send unlimited requests',
                            'text' => 'Drop tasks into your queue. We work them in priority order, tracked in ClickUp, with a weekly meeting to keep everyone aligned.',
                        ],
                        [
                            'title' => 'Scale, pause or cancel',
                            'text' => 'Upgrade and pay only the difference. Pause when the pipeline is quiet. Cancel any time — no notice period, no penalty.',
                        ],
                    ]],
                ]],
                'portfolio' => ['label' => 'Portfolio section', 'fields' => [
                    'portfolio.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Portfolio'],
                    'portfolio.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Projects that show the standard'],
                    'portfolio.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Filter by discipline, then open any project to see what was delivered.'],
                    'portfolio.cta' => ['label' => 'Button beside the heading', 'type' => 'text', 'default' => 'Start a project'],
                    'portfolio.more' => ['label' => 'Browse-all button', 'type' => 'text', 'default' => 'Browse the full portfolio'],
                    'portfolio.more_hint' => ['label' => 'Note under the button', 'type' => 'text', 'default' => '18 projects, filterable by discipline'],
                ]],
                'pricing' => ['label' => 'Pricing section', 'fields' => [
                    'pricing.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Flexible plans'],
                    'pricing.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Budget-friendly rates, studio-grade output'],
                    'pricing.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'One rate covers every discipline. No recruitment fees, no software seats, no idle payroll between jobs.'],
                    'pricing.faq_link' => ['label' => 'Link under each plan', 'type' => 'text', 'default' => 'How our subscription works?'],
                    'pricing.fixed_title' => ['label' => 'Fixed-price title', 'type' => 'text', 'default' => 'Looking for a fixed price?'],
                    'pricing.fixed_text' => ['label' => 'Fixed-price text', 'type' => 'textarea', 'default' => 'Send us the scope and we will come back with a fixed quote and a delivery date — usually within 24 hours.'],
                    'pricing.fixed_cta' => ['label' => 'Fixed-price button', 'type' => 'text', 'default' => 'Get a quote now'],
                ]],
                'about' => ['label' => 'About section', 'fields' => [
                    'about.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'About Archilance LLC'],
                    'about.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Founded by architects, run like a studio'],
                    'about.lede' => ['label' => 'Lead paragraph', 'type' => 'textarea', 'default' => 'Two architects who kept hitting the same wall: good practices turning down good work because the drawing capacity was not there.'],
                    'about.body' => ['label' => 'Second paragraph', 'type' => 'textarea', 'default' => 'So they built the team they wanted to hire. Over 600 Revit models for a single client since, every one checked by an architect before it reaches your inbox.'],
                    'about.founder1_name' => ['label' => 'Founder 1 name', 'type' => 'text', 'default' => 'Asad Kamal Abbasi'],
                    'about.founder1_role' => ['label' => 'Founder 1 role', 'type' => 'html', 'default' => 'Co-Founder · Architecture &amp; Documentation'],
                    'about.founder2_name' => ['label' => 'Founder 2 name', 'type' => 'text', 'default' => 'Faran Shahbaz Khan'],
                    'about.founder2_role' => ['label' => 'Founder 2 role', 'type' => 'html', 'default' => 'Co-Founder · BIM &amp; Visualisation'],
                    'about.badges_text' => ['label' => 'Text beside the badges', 'type' => 'text', 'default' => 'Top-rated on Upwork and working to an ISO 9001 quality management process.'],
                    'about.cta1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Get a free consultation'],
                    'about.cta2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See the work'],
                    'about.stat1_v' => ['label' => 'Stat 1 number', 'type' => 'text', 'default' => '6'],
                    'about.stat1_l' => ['label' => 'Stat 1 label', 'type' => 'text', 'default' => 'Years of work'],
                    'about.stat2_v' => ['label' => 'Stat 2 number', 'type' => 'text', 'default' => '600'],
                    'about.stat2_l' => ['label' => 'Stat 2 label', 'type' => 'text', 'default' => 'Revit models'],
                    'about.stat3_v' => ['label' => 'Stat 3 number', 'type' => 'text', 'default' => '19'],
                    'about.stat3_l' => ['label' => 'Stat 3 label', 'type' => 'text', 'default' => '5-star reviews'],
                    'about.stat4_v' => ['label' => 'Stat 4 number', 'type' => 'text', 'default' => '24'],
                    'about.stat4_l' => ['label' => 'Stat 4 label', 'type' => 'text', 'default' => 'Reply time'],
                ]],
                'testimonials' => ['label' => 'Testimonials section', 'fields' => [
                    'testimonials.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Client testimonials'],
                    'testimonials.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'What architects and builders say'],
                ]],
                'faq' => ['label' => 'FAQ section', 'fields' => [
                    'faq.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'FAQs'],
                    'faq.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Answers before you ask'],
                    'faq.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Still unsure? Fifteen minutes on a call and you will know either way.'],
                    'faq.cta_title' => ['label' => 'Call-out title', 'type' => 'text', 'default' => 'Talk to an architect'],
                    'faq.cta_text' => ['label' => 'Call-out text', 'type' => 'textarea', 'default' => 'No sales script, no account manager — a direct conversation with the people who will draw your project.'],
                    'faq.cta_button' => ['label' => 'Call-out button', 'type' => 'text', 'default' => 'Book a call'],
                ]],
                'coverage' => ['label' => 'Coverage section', 'fields' => [
                    'coverage.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Areas we serve'],
                    'coverage.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Your timezone, covered'],
                    'coverage.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Dover, Delaware and Lahore, Pakistan — so your working day overlaps ours, wherever you are.'],
                    'coverage.cta' => ['label' => 'Button', 'type' => 'text', 'default' => 'Let\'s talk'],
                    'coverage.regions' => ['label' => 'Regions', 'type' => 'repeat', 'schema' => [
                        ['key' => 'name', 'label' => 'Region', 'type' => 'text'],
                        ['key' => 'note', 'label' => 'Note', 'type' => 'text'],
                    ], 'default' => [
                        [
                            'name' => 'United States',
                            'note' => 'All 50 states · permit sets',
                        ],
                        [
                            'name' => 'Canada',
                            'note' => 'Residential &amp; commercial',
                        ],
                        [
                            'name' => 'United Kingdom',
                            'note' => 'Planning &amp; building control',
                        ],
                        [
                            'name' => 'Europe',
                            'note' => 'EU-wide project support',
                        ],
                        [
                            'name' => 'Australia',
                            'note' => 'Documentation &amp; BIM',
                        ],
                        [
                            'name' => 'New Zealand',
                            'note' => 'Consent documentation',
                        ],
                        [
                            'name' => 'Middle East',
                            'note' => 'UAE, KSA, Qatar',
                        ],
                        [
                            'name' => 'Africa & Asia',
                            'note' => 'Rwanda, Pakistan &amp; more',
                        ],
                    ]],
                ]],
                'contact' => ['label' => 'Contact section', 'fields' => [
                    'contact.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Contact us'],
                    'contact.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Drop us a line and <span class="text-gold">start the conversation today</span>'],
                    'contact.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Scope, software, deadline. You will hear back inside 24 hours, from an architect rather than an account manager.'],
                ]],
                'cta' => ['label' => 'Closing call to action', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Ready when you are'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Your next deadline does not have to hurt'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Start with three free days on one live task. If the work is not right, we keep going until it is.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Start your free trial'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'Compare plans'],
                ]],
            ],

            // ================================================== projects
            'projects' => [
                'intro' => ['label' => 'Section intro', 'fields' => [
                    'grid.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'The portfolio'],
                    'grid.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Filter by discipline, or open any project to see the scope, the software and what was delivered.'],
                    'grid.more' => ['label' => 'Load-more button', 'type' => 'text', 'default' => 'Load 9 more'],
                    'grid.hint' => ['label' => 'Hint under the grid', 'type' => 'html', 'default' => 'Working on something similar?'],
                ]],
                'tools' => ['label' => 'Software strip', 'fields' => [
                    'tools.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'How the work gets made'],
                    'tools.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Built in the software your team already uses'],
                    'tools.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'All of it built in industry-standard tools, so files arrive native and open on your machine.'],
                    'tools.list' => ['label' => 'Software names', 'type' => 'list', 'default' => [
                        'Autodesk Revit',
                        'AutoCAD',
                        'SketchUp',
                        'D5 Render',
                        'Lumion',
                        'Enscape',
                        '3ds Max',
                        'Twinmotion',
                        'Navisworks',
                        'Photoshop',
                    ]],
                ]],
                'cta' => ['label' => 'Closing CTA', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Start your project'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Your project could be the next one on this page.'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Send a sketch, a site plan or a Revit file. You get a scope, a timeline and three days free.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Start your free trial'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See pricing'],
                ]],
            ],

            // ================================================== services
            'services' => [
                'intro' => ['label' => 'Section intro', 'fields' => [
                    'list.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'The full service list'],
                    'list.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Every discipline is staffed by people who do this work daily, and every one is included in the same rate.'],
                ]],
                'why' => ['label' => 'Why outsource', 'fields' => [
                    'why.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Why outsource to us'],
                    'why.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'A studio you can scale up and down'],
                    'why.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Hiring one architect costs more than a subscription and gives you one skill set. This gives you eight.'],
                    'why.steps' => ['label' => 'Points', 'type' => 'repeat', 'schema' => [
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea'],
                    ], 'default' => [
                        [
                            'title' => 'One flat rate',
                            'text' => '$1,895 a month for 160 hours — $11.84 an hour — across any mix of the eight services. Hourly engagements are $28.',
                        ],
                        [
                            'title' => 'Unlimited requests',
                            'text' => 'Queue as many design requests and revisions as you like. We work them in priority order and you reprioritise any time.',
                        ],
                        [
                            'title' => 'Pause when quiet',
                            'text' => 'Only need a week this month? Pause the subscription and keep the remaining time for when the next project lands.',
                        ],
                        [
                            'title' => 'Architects, not agents',
                            'text' => 'You talk directly to the people drawing your project. No account managers, no relay, no lost context.',
                        ],
                    ]],
                ]],
                'cta' => ['label' => 'Closing CTA', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Start today'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Not sure which service you need?'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Describe it in a sentence. You will get the discipline, a rough timeline and a price.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Get a free consultation'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See pricing'],
                ]],
            ],

            // =================================================== pricing
            'pricing' => [
                'bar' => ['label' => 'Hero promise bar', 'fields' => [
                    'bar.item1' => ['label' => 'Promise 1', 'type' => 'html', 'default' => 'Unlimited requests and revisions on every plan'],
                    'bar.item2' => ['label' => 'Promise 2', 'type' => 'html', 'default' => 'Pause or cancel any time &mdash; no notice period'],
                    'bar.item3' => ['label' => 'Promise 3', 'type' => 'html', 'default' => 'NDA on request &middot; ISO 9001 process'],
                ]],
                'plans' => ['label' => 'Plans section', 'fields' => [
                    'plans.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Pick the pace, not the paperwork'],
                    'plans.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Every plan covers all eight disciplines. The only difference is how much drawing time you need each week.'],
                    'plans.quote_link' => ['label' => 'Secondary link on each card', 'type' => 'text', 'default' => 'Price my project'],
                ]],
                'fit' => ['label' => 'Which plan fits', 'fields' => [
                    'fit.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Not sure which one'],
                    'fit.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Start from where you actually are'],
                    'fit.cards' => ['label' => 'Situations', 'type' => 'repeat', 'schema' => [
                        ['key' => 'when', 'label' => 'When this is you', 'type' => 'text'],
                        ['key' => 'plan', 'label' => 'Plan', 'type' => 'text'],
                        ['key' => 'why', 'label' => 'Why', 'type' => 'textarea'],
                        ['key' => 'cta', 'label' => 'Link text', 'type' => 'text'],
                    ], 'default' => [
                        [
                            'when' => 'One project, scope still moving',
                            'plan' => 'Hourly',
                            'why' => 'Pay for the hours you use while the brief settles, then move onto a plan once the workload is steady.',
                            'cta' => 'See hourly',
                        ],
                        [
                            'when' => 'A steady drawing queue',
                            'plan' => 'Basic',
                            'why' => 'Forty hours a week and one active task at a time. The usual fit for a practice with a consistent pipeline.',
                            'cta' => 'See Basic',
                        ],
                        [
                            'when' => 'Several jobs running at once',
                            'plan' => 'Standard',
                            'why' => 'Sixty hours a week, tasks running in parallel and a project manager keeping them apart.',
                            'cta' => 'See Standard',
                        ],
                    ]],
                ]],
                'scope' => ['label' => 'What is covered', 'fields' => [
                    'scope.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Included in every plan'],
                    'scope.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'All eight disciplines, one rate'],
                    'scope.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Mix them, or switch between them mid-month. None of it costs extra.'],
                ]],
                'cmp' => ['label' => 'Comparison table', 'fields' => [
                    'cmp.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Side by side'],
                    'cmp.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'What actually changes between plans'],
                    'cmp.col0' => ['label' => 'First column heading', 'type' => 'text', 'default' => ''],
                    'cmp.rows' => ['label' => 'Rows', 'type' => 'repeat', 'schema' => [
                        ['key' => 'label', 'label' => 'Row', 'type' => 'text'],
                        ['key' => 'a', 'label' => 'Plan 1 (yes / no / text)', 'type' => 'text'],
                        ['key' => 'b', 'label' => 'Plan 2 (yes / no / text)', 'type' => 'text'],
                        ['key' => 'c', 'label' => 'Plan 3 (yes / no / text)', 'type' => 'text'],
                    ], 'default' => [
                        ['label' => 'Drawing time', 'a' => '40 hrs / week', 'b' => '60 hrs / week', 'c' => 'As needed'],
                        ['label' => 'Effective rate', 'a' => '$11.84 / hr', 'b' => '$10.42 / hr', 'c' => '$28 / hr'],
                        ['label' => 'Unlimited requests', 'a' => 'yes', 'b' => 'yes', 'c' => 'yes'],
                        ['label' => 'Unlimited revisions', 'a' => 'yes', 'b' => 'yes', 'c' => 'yes'],
                        ['label' => 'Tasks in parallel', 'a' => 'One at a time', 'b' => 'Multiple', 'c' => 'Multiple'],
                        ['label' => 'Dedicated project manager', 'a' => 'no', 'b' => 'yes', 'c' => 'yes'],
                        ['label' => 'Time tracking', 'a' => 'no', 'b' => 'yes', 'c' => 'yes'],
                        ['label' => 'People on your account', 'a' => '1', 'b' => '2', 'c' => 'Scales with the work'],
                        ['label' => 'Weekly meeting', 'a' => 'yes', 'b' => 'yes', 'c' => 'yes'],
                        ['label' => 'Pause or cancel any time', 'a' => 'yes', 'b' => 'yes', 'c' => 'yes'],
                    ]],
                ]],
                'fixed' => ['label' => 'Fixed price band', 'fields' => [
                    'fixed.title' => ['label' => 'Title', 'type' => 'text', 'default' => 'Would you rather have one number?'],
                    'fixed.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Send the scope and you get a fixed quote and a delivery date, usually inside 24 hours.'],
                    'fixed.cta' => ['label' => 'Button', 'type' => 'text', 'default' => 'Price my project'],
                ]],
                'proof' => ['label' => 'Testimonials', 'fields' => [
                    'proof.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'In their words'],
                    'proof.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'What it is like on the other side of the invoice'],
                ]],
                'faq' => ['label' => 'Pricing questions', 'fields' => [
                    'faq.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Before you commit'],
                    'faq.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'The questions that actually decide it'],
                    'faq.more' => ['label' => 'Link to the FAQ hub', 'type' => 'text', 'default' => 'Read every question'],
                ]],
                'cta' => ['label' => 'Closing call to action', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Three days free'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Try it on a real drawing first'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Send one live task. If the output is not right we keep working until it is, before any money moves.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Price my project'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'Talk to an architect'],
                ]],
            ],

            // ===================================================== about
            'about' => [
                'story' => ['label' => 'Our story', 'fields' => [
                    'story.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Our story'],
                    'story.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Two architects, one shared frustration'],
                    'story' => ['label' => 'Paragraphs', 'type' => 'list', 'rich' => true, 'default' => [
                        'Archilance LLC was co-founded by <b>Asad Kamal Abbasi</b> and <b>Faran Shahbaz Khan</b>,
            who met the same problem from opposite ends of the industry: good practices turning down
            good work because the drawing capacity was not there, and good architects sitting idle
            between projects.',
                        'They built the studio they wanted to hire — a full architecture team on a flat monthly
            subscription, merging their top-tier expertise from a 5-star rated Upwork agency into a
            company built for global expansion and client-focused delivery.',
                        'Today that team is 48 architects, drafters, BIM specialists and visualisers across two
            offices, working inside our clients\' own templates and standards so the output arrives
            looking like they drew it themselves.',
                    ]],
                    'story.cta' => ['label' => 'Link text', 'type' => 'text', 'default' => 'See what the team delivers'],
                ]],
                'values' => ['label' => 'Values', 'fields' => [
                    'values.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'How we work'],
                    'values.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Four things we refuse to compromise on'],
                    'values.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'These are not slogans — they are the rules the studio is actually run by.'],
                    'values' => ['label' => 'The four values', 'type' => 'repeat', 'schema' => [
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea'],
                    ], 'default' => [
                        [
                            'title' => 'Your standards, not ours',
                            'text' => 'We work inside your template, title block and naming conventions. Nothing arrives that your team has to rebuild before they can use it.',
                        ],
                        [
                            'title' => 'Architects on the call',
                            'text' => 'You talk to the people drawing your project. No account managers, no relay, no context lost between the brief and the model.',
                        ],
                        [
                            'title' => 'Honest scope',
                            'text' => 'We confirm scope, LOD and deadline in writing before work starts, and we tell you when we are not the right fit for a job.',
                        ],
                        [
                            'title' => 'ISO 9001 discipline',
                            'text' => 'A certified quality process behind every deliverable — checked by a second pair of eyes before it reaches you.',
                        ],
                    ]],
                ]],
                'team' => ['label' => 'Team chart', 'fields' => [
                    'team.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Our team'],
                    'team.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'people, and you can see exactly who does what'],
                    'team.lede' => ['label' => 'Intro', 'type' => 'textarea', 'default' => 'Every reporting line, colour-coded by function. Hover anyone to see what they own.'],
                    'team.hint' => ['label' => 'Chart hint', 'type' => 'text', 'default' => 'Scroll to zoom · drag to move · hover anyone to see what they do · select a card for their full profile'],
                ]],
                'creds' => ['label' => 'Credentials', 'fields' => [
                    'creds.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Credentials'],
                    'creds.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Verified, certified and reviewed in public'],
                    'creds.items' => ['label' => 'Credential cards', 'type' => 'repeat', 'schema' => [
                        ['key' => 'image', 'label' => 'Badge image (blank uses an icon)', 'type' => 'text'],
                        ['key' => 'alt', 'label' => 'Image alt text', 'type' => 'text'],
                        ['key' => 'icon', 'label' => 'Sprite icon id', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Description', 'type' => 'textarea'],
                    ], 'default' => [
                        [
                            'image' => 'assets/img/brand/iso-9001.webp',
                            'icon' => '',
                            'title' => 'ISO 9001 certified',
                            'text' => 'An audited quality-management process governing how every drawing set is produced and checked.',
                            'alt' => 'ISO 9001 certified company',
                        ],
                        [
                            'image' => 'assets/img/brand/upwork-badge.webp',
                            'icon' => '',
                            'title' => '5.0 on Upwork',
                            'text' => 'Nineteen five-star reviews from architecture firms, builders and developers — all publicly verifiable.',
                            'alt' => '5-star rated agency on Upwork',
                        ],
                        [
                            'image' => '',
                            'icon' => 'i-globe',
                            'title' => 'Two offices, one team',
                            'text' => 'Dover, Delaware and Lahore, Pakistan — overlapping hours with US, UK, Australia and Gulf clients.',
                            'alt' => '',
                        ],
                    ]],
                ]],
                'cta' => ['label' => 'Closing CTA', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Work with us'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Put this team on your next project.'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Three days free on a live task, working with the architects who will draw your project.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Start your free trial'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See our work'],
                ]],
            ],

            // ======================================================= faq
            'faq' => [
                'search' => ['label' => 'Search bar', 'fields' => [
                    'search.placeholder' => ['label' => 'Search placeholder', 'type' => 'text', 'default' => 'Search the answers…'],
                    'search.all_label' => ['label' => 'All-topics pill', 'type' => 'text', 'default' => 'All'],
                    'search.empty' => ['label' => 'No-results message', 'type' => 'html', 'default' => 'Nothing matches that search.'],
                ]],
                'cta' => ['label' => 'Closing CTA', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Still unsure'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Ask an architect, not a chatbot'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'Fifteen minutes and you will know whether we fit. We will say so if we do not.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'Book a call'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See pricing'],
                ]],
            ],

            // =================================================== contact
            'contact' => [
                'form' => ['label' => 'Form panel', 'fields' => [
                    'form.heading' => ['label' => 'Aside heading', 'type' => 'html', 'default' => 'Start the conversation'],
                    'form.lede' => ['label' => 'Aside intro', 'type' => 'textarea', 'default' => 'Prefer to skip the form? Any of these reaches the same people.'],
                    'form.intro' => ['label' => 'Required-fields note', 'type' => 'html', 'default' => 'Fields marked <span class="req">*</span> are required.'],
                    'form.submit' => ['label' => 'Submit button', 'type' => 'text', 'default' => 'Send my enquiry'],
                ]],
                'promise' => ['label' => 'What happens next', 'fields' => [
                    'promise.title' => ['label' => 'Title', 'type' => 'text', 'default' => 'What happens next'],
                    'promise.steps' => ['label' => 'Steps', 'type' => 'repeat', 'schema' => [
                        ['key' => 'title', 'label' => 'Bold lead', 'type' => 'text'],
                        ['key' => 'text', 'label' => 'Rest of the line', 'type' => 'text'],
                    ], 'default' => [
                        [
                            'title' => 'We read it properly.',
                            'text' => 'An architect reads your brief, not a sales rep.',
                        ],
                        [
                            'title' => 'You get a scope and a price.',
                            'text' => 'In writing, within 24 hours, with an honest deadline.',
                        ],
                        [
                            'title' => 'Three days free.',
                            'text' => 'On a real task, before any money moves.',
                        ],
                    ]],
                ]],
                'offices' => ['label' => 'Offices section', 'fields' => [
                    'offices.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Offices'],
                    'offices.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Two locations, one team'],
                    'offices.third_title' => ['label' => 'Third card title', 'type' => 'text', 'default' => 'Coverage'],
                    'offices.third_body' => ['label' => 'Third card body', 'type' => 'html', 'default' => 'United States · Canada · United Kingdom<br>
Australia · New Zealand · United Arab Emirates'],
                ]],
                'cta' => ['label' => 'Closing CTA', 'fields' => [
                    'cta.eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'No commitment'],
                    'cta.heading' => ['label' => 'Heading', 'type' => 'html', 'default' => 'Try us on a live task, free for three days.'],
                    'cta.text' => ['label' => 'Text', 'type' => 'textarea', 'default' => 'If the output is not what you wanted, you pay nothing — and we keep going until it is.'],
                    'cta.button1' => ['label' => 'Primary button', 'type' => 'text', 'default' => 'See pricing'],
                    'cta.button2' => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'See our work'],
                ]],
            ],

            // ====================================================== blog
            'blog' => [
                'list' => ['label' => 'Listing', 'fields' => [
                    'list.search_placeholder' => ['label' => 'Search placeholder', 'type' => 'text', 'default' => 'Search articles…'],
                    'list.empty_title' => ['label' => 'Empty state title', 'type' => 'text', 'default' => 'Nothing published here yet'],
                    'list.empty_text' => ['label' => 'Empty state text', 'type' => 'textarea', 'default' => 'New writing goes up regularly — or ask us directly and we will answer in person.'],
                ]],
            ],
        ];
    }
}
