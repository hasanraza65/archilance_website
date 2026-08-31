@extends('layouts.front')

@section('content')


  <!-- ============================== Page header ============================== -->
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true">
      <img class="parallax" data-parallax="6" src="{{ asset('assets/img/hero/hero-2.webp') }}" srcset="{{ asset('assets/img/hero/hero-2-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-2.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high" decoding="async">
    </div>
    <div class="page-hero__veil" aria-hidden="true"></div>

    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">About Us</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>
        {{ $page?->lede }}
      </p>

      <div class="page-hero__foot stat-strip" data-reveal>
        <div class="stat"><b data-count="{{ $teamCount }}">0</b><span>People on the team</span></div>
        <div class="stat"><b data-count="{{ $s->get('years', 6) }}">0</b><span>Years of work</span></div>
        <div class="stat"><b data-count="{{ $s->get('models', 600) }}" data-suffix="+">0</b><span>Revit models built</span></div>
        <div class="stat"><b>5.0</b><span>Upwork rating</span></div>
      </div>
    </div>
  </section>

  <!-- ============================== Story ============================== -->
  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="storyHeading">
    <div class="shell">
      <div class="split">
        <div data-reveal="left">
          <p class="eyebrow">{{ $page->text('story.eyebrow') }}</p>
          <h2 class="h-xl" id="storyHeading">{!! $page->text('story.heading') !!}</h2>
          @foreach($page->text('story') ?? [] as $para)
            <p>{!! $para !!}</p>
          @endforeach
          <p><a class="link-a" href="{{ route('services.index') }}">{{ $page->text('story.cta') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a></p>
        </div>
        <div class="split__media" data-reveal="right">
          <img src="{{ asset('assets/img/brand/founders.webp') }}" width="1000" height="750" alt="Archilance LLC co-founders Asad Kamal Abbasi and Faran Shahbaz Khan" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </section>

  <!-- ============================== Values ============================== -->
  <section class="section section--paper grid-veil" aria-labelledby="valuesHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('values.eyebrow') }}</p>
        <h2 class="h-xl" id="valuesHeading">{!! $page->text('values.heading') !!}</h2>
        <p class="lede">{{ $page->text('values.lede') }}</p>
      </div>

      <div class="steps" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($page->text('values') ?? [] as $i => $v)
          <article class="step">
            <div class="step__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{{ $v['title'] }}</h3>
            <p>{{ $v['text'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== Team / org chart ============================== -->
  <section class="section section--ink grid-veil" id="team" aria-labelledby="teamHeading">
    <div class="shell shell--wide">
      <div class="section-head" data-reveal>
        <p class="eyebrow">{{ $page->text('team.eyebrow') }}</p>
        <h2 class="h-xl" id="teamHeading">{{ $teamCount }} {!! $page->text('team.heading') !!}</h2>
        <p class="lede">
          {{ $page->text('team.lede') }}
        </p>
      </div>
    </div>

    <div class="org" id="org">
      <div class="shell shell--wide">
        <div class="org__toolbar" data-reveal>
          <div class="org__legend" role="group" aria-label="Highlight a team by function">
            @foreach($teamGroups as $key => $label)
              <button class="org-chip org-chip--{{ $key }}" type="button" data-filter="{{ $key }}" aria-pressed="false">
                <i></i>{{ $label }} <span>{{ $groupCounts[$key] ?? 0 }}</span>
              </button>
            @endforeach
          </div>
          <div class="org__tools">
            <label class="org__search">
              <svg class="ico" aria-hidden="true"><use href="#i-expand"></use></svg>
              <input type="search" id="orgSearch" placeholder="Find someone…" aria-label="Find a team member by name or role" autocomplete="off">
            </label>
          </div>
        </div>
      </div>

      <figure class="omap omap--bleed" id="omap" data-reveal>
        <div class="omap__viewport" id="omapViewport" tabindex="0" data-lenis-prevent
             role="group" aria-label="Organisation chart — drag to pan, scroll to zoom">
          <div class="omap__canvas" id="omapCanvas">
            <div class="otree">
                <ul class="otree__root">
                  <li>
                    <div class="oband">
                      @foreach($leadership as $m)
                        @include('partials.org-card', ['m' => $m])
                      @endforeach
                    </div>
                    <ul>
                      @foreach($divisions as $node)
                        @include('partials.org-node', ['node' => $node])
                      @endforeach
                    </ul>
                  </li>
                </ul>
              </div>
          </div>
        </div>

        <div class="omap__ctrls">
          <button class="omap__btn" type="button" id="omapOut" aria-label="Zoom out">&minus;</button>
          <span class="omap__pct" id="omapPct">100%</span>
          <button class="omap__btn" type="button" id="omapIn" aria-label="Zoom in">+</button>
          <button class="omap__btn omap__btn--wide" type="button" id="omapFit">Fit</button>
        </div>

        <figcaption class="omap__hint">
          {{ $page->text('team.hint') }}
        </figcaption>
      </figure>
    </div>
  </section>

  <!-- ============================== Credentials ============================== -->
  <section class="section section--tight section--ink-soft" aria-labelledby="credHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('creds.eyebrow') }}</p>
        <h2 id="credHeading" class="h2">{!! $page->text('creds.heading') !!}</h2>
      </div>
      <div class="cred-grid" data-stagger style="margin-top:clamp(2rem,4vw,3rem)">
        @foreach($page->text('creds.items') ?? [] as $cred)
          <article class="cred">
            @if(! empty($cred['image']))
              <img src="{{ asset($cred['image']) }}" width="220" height="220" alt="{{ $cred['alt'] ?? ($cred['title'] ?? '') }}" loading="lazy" decoding="async">
            @else
              <div class="cred__ico"><svg class="ico" aria-hidden="true"><use href="#{{ ($cred['icon'] ?? '') ?: 'i-globe' }}"></use></svg></div>
            @endif
            <h3>{!! $cred['title'] ?? '' !!}</h3>
            <p>{!! $cred['text'] ?? '' !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== CTA ============================== -->
  <section class="section cta-band" aria-labelledby="ctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center">{{ $page->text('cta.eyebrow') }}</p>
      <h2 id="ctaHeading" data-reveal>{!! $page->text('cta.heading') !!}</h2>
      <p data-reveal>
        {{ $page->text('cta.text') }}
      </p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>{{ $page->text('cta.button1') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}" data-magnetic>{{ $page->text('cta.button2') }}</a>
      </div>
    </div>
  </section>


@endsection

@push('schema')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'About Us', 'item' => route('about')],
    ]],
    ['@type' => 'AboutPage', 'url' => route('about'),
     'name' => $page?->meta_title, 'description' => $page?->meta_description],
    ['@type' => 'Organization', 'name' => $s->get('site_name'), 'url' => route('home'),
     'email' => $s->get('email'), 'telephone' => $s->get('phone'),
     'numberOfEmployees' => ['@type' => 'QuantitativeValue', 'value' => $teamCount],
     'employee' => \App\Models\TeamMember::published()->whereNotNull('photo')->get()
        ->map(fn ($m) => ['@type' => 'Person', 'name' => $m->name, 'jobTitle' => $m->role,
                          'image' => asset($m->photo)])->all()],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('modals')
@include('partials.person-dialog')
@endpush
