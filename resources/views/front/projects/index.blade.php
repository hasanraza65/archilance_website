@extends('layouts.front')

@section('content')


  <!-- ============================== Page header ============================== -->
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true">
      <img class="parallax" data-parallax="6" src="{{ asset('assets/img/hero/hero-3.webp') }}" srcset="{{ asset('assets/img/hero/hero-3-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-3.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high" decoding="async">
    </div>
    <div class="page-hero__veil" aria-hidden="true"></div>

    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">Projects</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>
        {{ $page?->lede }}
      </p>

      <div class="page-hero__foot stat-strip" data-reveal>
        <div class="stat"><b data-count="{{ $projects->count() }}">0</b><span>Projects shown</span></div>
        <div class="stat"><b data-count="600" data-suffix="+">0</b><span>Revit models built</span></div>
        <div class="stat"><b data-count="6">0</b><span>Years of work</span></div>
        <div class="stat"><b>5.0</b><span>Upwork rating</span></div>
      </div>
    </div>
  </section>

  <!-- ============================== Project grid ============================== -->
  <section class="section section--after-hero section--ink grid-veil" id="work" aria-labelledby="workHeading">
    <div class="shell shell--wide">
      <div class="section-head" data-reveal style="margin-bottom:clamp(1.5rem,3vw,2.25rem)">
        <h2 id="workHeading" class="h2">{!! $page->text('grid.heading') !!}</h2>
        <p class="lede">
          {{ $page->text('grid.lede') }}
        </p>
      </div>

      <div class="filter-bar">
        <div class="filter-bar__row">
          <div class="filters" id="projectFilters" role="group" aria-label="Filter projects by discipline">
            <button class="filter" type="button" data-filter="all" aria-pressed="true">All work <span data-tally>{{ $projects->count() }}</span></button>
            @foreach($filters as $key => $label)
              <button class="filter" type="button" data-filter="{{ $key }}" aria-pressed="false">{{ $label }} <span data-tally>{{ $counts[$key] ?? 0 }}</span></button>
            @endforeach
          </div>
          <p class="filter-bar__status" id="projectsStatus" role="status" aria-live="polite">Showing 9 of 18 projects</p>
        </div>
      </div>

      <div class="works works--archive" id="projectsGrid" data-step="9">
        @foreach($projects as $i => $project)
          <a class="work {{ $project->span === 'wide' ? 'work--wide' : ($project->span === 'tall' ? 'work--tall' : '') }}"
             href="{{ route('projects.show', $project) }}"
             data-cat="{{ $project->categories }}"
             data-full="{{ asset($project->full_image) }}"
             data-title="{{ $project->title }}"
             data-desc="{{ $project->description }}">
            <img src="{{ asset($project->card_image) }}" width="{{ $project->card_width }}" height="{{ $project->card_height }}"
                 alt="{{ $project->image_alt }}" @if($i > 3) loading="lazy" @endif decoding="async">
            <span class="work__veil"></span>
            <span class="work__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="work__go"><svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
            <span class="work__body">
              <span class="work__cat">{{ $project->category_label }}</span>
              <span class="work__title">{{ $project->title }}</span>
              <span class="work__desc">{{ $project->description }}</span>
              @if($project->tags)
                <span class="work__tags">
                  @foreach($project->tags as $tag)<span class="work__tag">{{ $tag }}</span>@endforeach
                </span>
              @endif
            </span>
          </a>
        @endforeach

      </div>

      <div class="works-empty" id="projectsEmpty" hidden>
        <h3>Nothing in this discipline yet</h3>
        <p>We have not published work under this filter. Tell us what you are building and we will send relevant samples from the private archive.</p>
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Request samples<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
      </div>

      <div class="works-more">
        <button class="btn-a btn-a--ghost" type="button" id="projectsMore" data-magnetic>
          <span id="projectsMoreLabel">{{ $page->text('grid.more') }}</span>
          <svg class="ico" aria-hidden="true"><use href="#i-chevron-down"></use></svg>
        </button>
        <p class="works-more__hint">{!! $page->text('grid.hint') !!} <a class="link-a" href="{{ route('contact') }}">Send us your scope</a></p>
      </div>
    </div>
  </section>

  <!-- ============================== Toolset ============================== -->
  <section class="section section--tight section--ink-soft" aria-labelledby="toolsHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('tools.eyebrow') }}</p>
        <h2 id="toolsHeading" class="h2">{!! $page->text('tools.heading') !!}</h2>
        <p class="lede">
          {{ $page->text('tools.lede') }}
        </p>
      </div>

      <div class="tool-strip" data-reveal style="margin-top:clamp(2rem,4vw,3rem)">
        @foreach($page->text('tools.list') ?? [] as $tool)
          <span class="tool-chip"><i class="dot"></i>{{ $tool }}</span>
        @endforeach
      </div>

      <div class="stat-strip" data-reveal style="justify-content:center;margin-top:clamp(2.5rem,5vw,4rem)">
        <div class="stat"><b data-count="40" data-suffix="+">0</b><span>Firms served</span></div>
        <div class="stat"><b data-count="19">0</b><span>Five-star reviews</span></div>
        <div class="stat"><b data-count="24" data-suffix="h">0</b><span>Reply time</span></div>
        <div class="stat"><b>ISO 9001</b><span>Certified process</span></div>
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
        <a class="btn-a btn-a--ghost" href="{{ route('home') }}#pricing" data-magnetic>{{ $page->text('cta.button2') }}</a>
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
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Projects', 'item' => route('projects.index')],
    ]],
    ['@type' => 'CollectionPage', 'url' => route('projects.index'),
     'name' => $page?->meta_title, 'description' => $page?->meta_description],
    ['@type' => 'ItemList', 'numberOfItems' => $projects->count(),
     'itemListElement' => $projects->values()->map(fn ($p, $i) => [
        '@type' => 'ListItem', 'position' => $i + 1,
        'item' => ['@type' => 'CreativeWork', 'name' => $p->title,
                   'description' => $p->description, 'image' => asset($p->full_image)],
     ])->all()],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('modals')
@include('partials.lightbox')
@endpush
