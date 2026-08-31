@extends('layouts.front')

@section('content')

  {{-- The image is the point of a project page, so it leads. --}}
  <section class="pj-hero">
    <div class="pj-hero__media">
      <img src="{{ asset($project->full_image ?: $project->card_image) }}"
           alt="{{ $project->image_alt ?: $project->title }}"
           width="1600" height="1000" fetchpriority="high" decoding="async">
      <div class="pj-hero__veil" aria-hidden="true"></div>
    </div>

    <div class="shell pj-hero__in">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('projects.index') }}">Projects</a></li>
          <li><span aria-current="page">{{ $project->title }}</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $project->category_label }}</p>
      <h1 class="pj-hero__title" data-reveal>{{ $project->title }}</h1>
      <p class="pj-hero__lede" data-reveal>{{ $project->summary ?: $project->description }}</p>

      @if($project->tags)
        <ul class="pj-tags" data-stagger aria-label="Tools and type">
          @foreach($project->tags as $tag)
            <li class="pj-tag">{{ $tag }}</li>
          @endforeach
        </ul>
      @endif

      <div class="pj-hero__cta" data-reveal>
        <a class="btn-a" href="{{ route('home') }}#quoteInline" data-magnetic>
          Price a project like this
          <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <button class="btn-a btn-a--ghost" type="button" data-lightbox-open
                data-full="{{ asset($project->full_image ?: $project->card_image) }}"
                data-title="{{ $project->title }}" data-desc="{{ $project->description }}">
          <svg class="ico" aria-hidden="true"><use href="#i-expand"></use></svg>
          View full size
        </button>
      </div>
    </div>
  </section>

  {{-- ---------------------------------------------------------- facts --}}
  @if($project->facts)
    <section class="section section--tight section--ink" aria-label="Project at a glance">
      <div class="shell">
        <dl class="pj-facts" data-stagger>
          @foreach($project->facts as $fact)
            <div class="pj-fact">
              <dt>{{ $fact['label'] ?? '' }}</dt>
              <dd>{{ $fact['value'] ?? '' }}</dd>
            </div>
          @endforeach
        </dl>
      </div>
    </section>
  @endif

  {{-- ------------------------------------------------ write-up + list --}}
  <section class="section section--after-hero section--paper grid-veil" aria-labelledby="pjBody">
    <div class="shell">
      <div class="split split--wide">
        <div data-reveal="left">
          <p class="eyebrow">The work</p>
          <h2 class="h-xl" id="pjBody">What this one needed</h2>
          <div class="pj-body">
            {!! $project->body ?: '<p>' . e($project->description) . '</p>' !!}
          </div>

          <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn-a" href="{{ route('contact') }}" data-magnetic>
              Start something similar
              <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </a>
            <a class="btn-a btn-a--ghost-dark" href="{{ route('projects.index') }}" data-magnetic>All projects</a>
          </div>
        </div>

        @if($project->deliverables)
          <aside class="pj-deliver" data-reveal="right">
            <h3 class="pj-deliver__title">What was delivered</h3>
            <ul class="pj-deliver__list">
              @foreach($project->deliverables as $d)
                <li><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>{{ $d }}</li>
              @endforeach
            </ul>
          </aside>
        @endif
      </div>
    </div>
  </section>

  {{-- --------------------------------------------------------- gallery --}}
  @if($project->gallery)
    <section class="section section--ink" aria-labelledby="pjGallery">
      <div class="shell shell--wide">
        <div class="section-head" data-reveal>
          <p class="eyebrow">Gallery</p>
          <h2 class="h-lg" id="pjGallery">Closer in</h2>
        </div>
        <div class="pj-gallery" data-stagger>
          @foreach($project->gallery as $shot)
            <button class="pj-shot" type="button" data-lightbox-open
                    data-full="{{ asset($shot['image'] ?? '') }}"
                    data-title="{{ $project->title }}"
                    data-desc="{{ $shot['caption'] ?? $project->description }}">
              <img src="{{ asset($shot['image'] ?? '') }}" alt="{{ $shot['alt'] ?? $project->image_alt }}"
                   width="1200" height="800" loading="lazy" decoding="async">
              <span class="pj-shot__zoom"><svg class="ico" aria-hidden="true"><use href="#i-expand"></use></svg></span>
              @if(!empty($shot['caption']))<span class="pj-shot__cap">{{ $shot['caption'] }}</span>@endif
            </button>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- --------------------------------------------------------- related --}}
  @if($related->isNotEmpty())
    <section class="section section--paper" aria-labelledby="pjRelated">
      <div class="shell shell--wide">
        <div class="head-row" data-reveal>
          <div class="section-head">
            <p class="eyebrow">More work</p>
            <h2 class="h-lg" id="pjRelated">In the same discipline</h2>
          </div>
          <a class="btn-a btn-a--ghost-dark" href="{{ route('projects.index') }}" data-magnetic>
            Browse all
            <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
          </a>
        </div>

        <div class="pj-related" data-stagger>
          @foreach($related as $r)
            <a class="pj-card" href="{{ route('projects.show', $r) }}">
              <span class="pj-card__media">
                <img src="{{ asset($r->card_image) }}" alt="{{ $r->image_alt ?: $r->title }}"
                     width="{{ $r->card_width }}" height="{{ $r->card_height }}" loading="lazy" decoding="async">
              </span>
              <span class="pj-card__cat">{{ $r->category_label }}</span>
              <span class="pj-card__title">{{ $r->title }}</span>
              <span class="pj-card__go">
                View project <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </span>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ------------------------------------------------------- prev/next --}}
  <nav class="pj-pager" aria-label="Project navigation">
    <a class="pj-pager__side pj-pager__side--prev" href="{{ route('projects.show', $prev) }}">
      <svg class="ico" aria-hidden="true"><use href="#i-arrow-left"></use></svg>
      <span><small>Previous</small><b>{{ $prev->title }}</b></span>
    </a>
    <a class="pj-pager__side pj-pager__side--next" href="{{ route('projects.show', $next) }}">
      <span><small>Next</small><b>{{ $next->title }}</b></span>
      <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
    </a>
  </nav>

  <section class="cta-band" aria-labelledby="pjCta">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center;display:inline-flex">Your project next</p>
      <h2 class="h-xl" id="pjCta" data-reveal>Send us the sketch. We will send back the drawings.</h2>
      <p data-reveal>Three days free on a live task, and you talk to the architects doing the work.</p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a btn-a--lg" href="{{ route('home') }}#quoteInline" data-magnetic>
          Price my project
          <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <a class="btn-a btn-a--ghost btn-a--lg" href="{{ route('contact') }}" data-magnetic>Talk to an architect</a>
      </div>
    </div>
  </section>

  @include('partials.lightbox')

@endsection

@push('schema')
  <script type="application/ld+json">
  {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'CreativeWork',
      'name' => $project->title,
      'description' => strip_tags($project->summary ?: $project->description),
      'image' => asset($project->full_image ?: $project->card_image),
      'url' => route('projects.show', $project),
      'genre' => $project->category_label,
      'keywords' => implode(', ', $project->tags ?? []),
      'creator' => ['@type' => 'Organization', 'name' => $s->get('site_name'), 'url' => route('home')],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>
@endpush
