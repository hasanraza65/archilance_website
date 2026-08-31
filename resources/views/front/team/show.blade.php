@extends('layouts.front')

@php
    use Illuminate\Support\Str;

    $first = Str::before($member->name, ' ') ?: $member->name;
    $portrait = $member->portrait();

    // Education was originally a single free-text note; keep honouring it so a
    // member edited before the profile fields existed still reads correctly.
    // educationNote() returns null when that note is not a qualification at
    // all — several records use the field for a discipline or second title.
    $education = $member->education ?: [];
    $eduNote = $education ? null : $member->educationNote();

    $links = array_filter([
        'email' => $member->email,
        'linkedin' => $member->linkedin,
        'behance' => $member->behance,
    ]);
@endphp

@section('content')

  <!-- ============================== Profile header ============================== -->
  <section class="page-hero page-hero--person tm" style="--accent: var(--g-{{ $member->team }})">
    <div class="tm-hero__glow" aria-hidden="true"></div>

    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('about') }}#team">Team</a></li>
          <li><span aria-current="page">{{ $member->name }}</span></li>
        </ol>
      </nav>

      <div class="tm-hero">
        <div class="tm-hero__copy">
          <p class="eyebrow" data-reveal>
            <span class="tm-dot" aria-hidden="true"></span>{{ $member->teamLabel() }}
          </p>

          <h1 class="page-hero__title tm-hero__name" data-reveal>{{ $member->name }}</h1>
          <p class="tm-hero__role" data-reveal>{{ $member->role }}</p>

          @if($member->headline)
            <p class="page-hero__lede" data-reveal>{{ $member->headline }}</p>
          @elseif($member->blurb)
            <p class="page-hero__lede" data-reveal>{{ $member->blurb }}</p>
          @endif

          @if($member->location)
            <p class="tm-hero__meta" data-reveal>
              <svg class="ico" aria-hidden="true"><use href="#i-pin"></use></svg>{{ $member->location }}
            </p>
          @endif

          <div class="page-hero__cta" data-reveal>
            <a class="btn-a" href="{{ route('contact') }}" data-magnetic>
              Work with {{ $first }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </a>
            @if($links)
              <div class="tm-links">
                @if($member->email)
                  <a class="tm-link" href="mailto:{{ $member->email }}" aria-label="Email {{ $member->name }}">
                    <svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>
                  </a>
                @endif
                @if($member->linkedin)
                  <a class="tm-link" href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer"
                     aria-label="{{ $member->name }} on LinkedIn">
                    <svg class="ico" aria-hidden="true"><use href="#i-linkedin"></use></svg>
                  </a>
                @endif
                @if($member->behance)
                  <a class="tm-link" href="{{ $member->behance }}" target="_blank" rel="noopener noreferrer"
                     aria-label="{{ $member->name }} on Behance">
                    <svg class="ico" aria-hidden="true"><use href="#i-behance"></use></svg>
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>

        <div class="tm-hero__media" data-reveal="right">
          <figure class="tm-photo">
            @if($portrait)
              <img src="{{ asset($portrait) }}" width="560" height="560"
                   alt="{{ $member->photoAlt() }}" fetchpriority="high" decoding="async">
            @else
              <span class="tm-photo__ph" aria-hidden="true">{{ $member->initials() }}</span>
            @endif
          </figure>
        </div>
      </div>

      {{-- a lone tile stretches across the strip and reads as an accident --}}
      @if($member->highlights && count($member->highlights) > 1)
        <div class="stat-strip tm-stats" data-reveal>
          @foreach($member->highlights as $stat)
            @php
                $val = $stat['value'] ?? '';
                // .stat b is sized for figures like "600+"; a word set at that
                // size swamps the page, so text tiles get their own scale.
                $isFigure = is_numeric(trim(strip_tags((string) $val), '+%'));
            @endphp
            <div class="stat @if(! $isFigure) stat--text @endif">
              <b>{!! $val !!}</b>
              <span>{!! $stat['label'] ?? '' !!}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  <!-- ============================== About ============================== -->
  @if($member->bio || $member->blurb || $member->focus || $education || $eduNote)
    <section class="section section--after-hero section--ink grid-veil tm" aria-labelledby="aboutHeading"
             style="--accent: var(--g-{{ $member->team }})">
      <div class="shell">
        <div class="split">
          <div data-reveal="left">
            <p class="eyebrow">Profile</p>
            <h2 class="h-xl" id="aboutHeading">About {{ $first }}</h2>

            @forelse(preg_split('/\R{2,}/', trim((string) ($member->bio ?: $member->blurb))) as $para)
              @if(trim($para) !== '')<p>{{ trim($para) }}</p>@endif
            @empty
            @endforelse

            @if($member->quote)
              <blockquote class="tm-quote">
                <svg class="ico" aria-hidden="true"><use href="#i-quote"></use></svg>
                <p>{{ $member->quote }}</p>
              </blockquote>
            @endif

            @if($education || $eduNote)
              <div class="tm-edu">
                <h3>Education &amp; credentials</h3>
                @if($education)
                  <ul>
                    @foreach($education as $ed)
                      <li>
                        <b>{{ $ed['degree'] ?? '' }}</b>
                        <span>{{ collect([$ed['school'] ?? null, $ed['year'] ?? null])->filter()->implode(' · ') }}</span>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <p class="tm-edu__note">{{ $eduNote }}</p>
                @endif
              </div>
            @endif
          </div>

          @if($member->focus)
            <div data-reveal="right">
              <p class="eyebrow">Responsibilities</p>
              <h2 class="h-xl">What {{ $first }} owns</h2>
              <ul class="tm-focus">
                @foreach($member->focus as $f)
                  <li>
                    <span class="tm-focus__ico" aria-hidden="true">
                      <svg class="ico"><use href="#i-check-circle"></use></svg>
                    </span>
                    <span class="tm-focus__text">
                      <b>{{ $f['title'] ?? '' }}</b>
                      @if(! empty($f['text']))<small>{{ $f['text'] }}</small>@endif
                    </span>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  <!-- ============================== Expertise ============================== -->
  @if($member->expertise)
    <section class="section section--tight section--ink-soft" aria-labelledby="skillsHeading">
      <div class="shell">
        <div class="section-head section-head--center" data-reveal>
          <p class="eyebrow" style="justify-content:center">Toolset</p>
          <h2 class="h2" id="skillsHeading">Software &amp; expertise</h2>
        </div>
        <div class="tool-strip" data-reveal style="margin-top:clamp(1.5rem,3vw,2.25rem)">
          @foreach($member->expertise as $tool)
            <span class="tool-chip"><i class="dot"></i>{{ is_array($tool) ? ($tool['name'] ?? '') : $tool }}</span>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <!-- ============================== Org position ============================== -->
  @if($member->parent || $reports->isNotEmpty())
    <section class="section section--paper grid-veil tm" aria-labelledby="orgHeading"
             style="--accent: var(--g-{{ $member->team }})">
      <div class="shell">
        <div class="section-head" data-reveal>
          <p class="eyebrow">Where {{ $first }} sits</p>
          <h2 class="h-xl" id="orgHeading">In the org chart</h2>
          <p class="lede">
            Every project runs through a named person — no shared inbox, no account manager in between.
          </p>
        </div>

        <div class="tm-org" data-stagger style="margin-top:clamp(1.75rem,3vw,2.5rem)">
          @if($member->parent)
            <div class="tm-org__col">
              <p class="tm-org__label">Reports to</p>
              @include('partials.tm-person-card', ['p' => $member->parent, 'light' => true])
            </div>
          @endif

          @if($reports->isNotEmpty())
            <div class="tm-org__col tm-org__col--wide">
              <p class="tm-org__label">
                {{ $reports->count() }} direct {{ Str::plural('report', $reports->count()) }}
              </p>
              <div class="tm-people">
                @foreach($reports as $r)
                  @include('partials.tm-person-card', ['p' => $r, 'light' => true])
                @endforeach
              </div>
            </div>
          @endif
        </div>

        <p style="margin-top:clamp(1.5rem,3vw,2rem)" data-reveal>
          <a class="link-a" href="{{ route('about') }}#team">
            See the full org chart<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
          </a>
        </p>
      </div>
    </section>
  @endif

  <!-- ============================== More of the team ============================== -->
  @if($peers->isNotEmpty())
    <section class="section section--ink grid-veil tm" aria-labelledby="peersHeading"
             style="--accent: var(--g-{{ $member->team }})">
      <div class="shell">
        <div class="section-head" data-reveal>
          <p class="eyebrow">More of the team</p>
          <h2 class="h-xl" id="peersHeading">Who {{ $first }} works alongside</h2>
        </div>
        <div class="tm-people tm-people--grid" data-stagger style="margin-top:clamp(1.75rem,3vw,2.5rem)">
          @foreach($peers as $p)
            @include('partials.tm-person-card', ['p' => $p])
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <!-- ============================== CTA ============================== -->
  <section class="section cta-band" aria-labelledby="ctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center">Start today</p>
      <h2 id="ctaHeading" data-reveal>Put {{ $first }} and the team on your next set.</h2>
      <p data-reveal>
        Send one live task and see the output before you commit. Three days, no card,
        and an architect confirms the scope within 24 hours.
      </p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>
          Start your free trial<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <a class="btn-a btn-a--ghost" href="{{ route('home') }}#pricing" data-magnetic>See pricing</a>
      </div>
    </div>
  </section>

@endsection

@push('schema')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@graph' => array_values(array_filter([
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Team', 'item' => route('about') . '#team'],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $member->name, 'item' => route('team.show', $member)],
    ]],
    array_filter([
      '@type' => 'Person',
      'name' => $member->name,
      'jobTitle' => $member->role,
      'description' => $member->headline ?: $member->blurb,
      'url' => route('team.show', $member),
      'image' => $portrait ? asset($portrait) : null,
      'email' => $member->email,
      'sameAs' => array_values(array_filter([$member->linkedin, $member->behance])) ?: null,
      'worksFor' => [
        '@type' => 'Organization',
        'name' => $s->get('site_name'),
        'url' => route('home'),
      ],
    ]),
  ])),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
