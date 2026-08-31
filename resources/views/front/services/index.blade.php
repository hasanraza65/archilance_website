@extends('layouts.front')

@section('content')


  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true">
      <img class="parallax" data-parallax="6" src="{{ asset('assets/img/services/services-hub-hero.webp') }}" srcset="{{ asset('assets/img/services/services-hub-hero-sm.webp') }} 900w, {{ asset('assets/img/services/services-hub-hero.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high" decoding="async">
    </div>
    <div class="page-hero__veil" aria-hidden="true"></div>

    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">Services</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>What we do</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>
        {{ $page?->lede }}
      </p>

      <div class="page-hero__foot stat-strip" data-reveal>
        <div class="stat"><b data-count="{{ $services->count() }}">0</b><span>Services covered</span></div>
        <div class="stat"><b data-count="600" data-suffix="+">0</b><span>Revit models built</span></div>
        <div class="stat"><b>$11.84</b><span>Per hour from</span></div>
        <div class="stat"><b>5.0</b><span>Upwork rating</span></div>
      </div>
    </div>
  </section>

  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="svcHeading">
    <div class="shell shell--wide">
      <div class="section-head" data-reveal style="margin-bottom:clamp(2rem,4vw,3rem)">
        <h2 class="h-xl" id="svcHeading">{!! $page->text('list.heading') !!}</h2>
        <p class="lede">
          {{ $page->text('list.lede') }}
        </p>
      </div>

      <div class="svc-cards">
        @foreach($services as $i => $service)
          <article class="svc-card" data-reveal>
            <a class="svc-card__media" href="{{ route('services.show', $service) }}" tabindex="-1" aria-hidden="true">
              <img src="{{ asset($service->card_image) }}" width="880" height="660" alt=""
                   loading="{{ $i < 3 ? 'eager' : 'lazy' }}" decoding="async">
              <span class="svc-card__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            </a>
            <div class="svc-card__body">
              <div class="svc-card__ico"><svg class="ico" aria-hidden="true"><use href="#{{ $service->icon }}"></use></svg></div>
              <h3><a href="{{ route('services.show', $service) }}">{{ $service->name }}</a></h3>
              <p>{{ $service->lede }}</p>
              <ul class="svc-card__list">
                @foreach(array_slice($service->deliverables ?? [], 0, 3) as $d)
                  <li><svg class="ico" aria-hidden="true"><use href="#i-check"></use></svg>{!! $d['title'] !!}</li>
                @endforeach
              </ul>
              <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
            </div>
          </article>
        @endforeach

      </div>
    </div>
  </section>

  <section class="section section--paper grid-veil" aria-labelledby="whyHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('why.eyebrow') }}</p>
        <h2 class="h-xl" id="whyHeading">{!! $page->text('why.heading') !!}</h2>
        <p class="lede">{{ $page->text('why.lede') }}</p>
      </div>

      <div class="steps" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($page->text('why.steps') ?? [] as $step)
          <article class="step">
            <div class="step__no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{!! $step['title'] ?? '' !!}</h3>
            <p>{!! $step['text'] ?? '' !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

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
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => route('services.index')],
    ]],
    ['@type' => 'ItemList', 'numberOfItems' => $services->count(),
     'itemListElement' => $services->values()->map(fn ($x, $i) => [
        '@type' => 'ListItem', 'position' => $i + 1,
        'item' => ['@type' => 'Service', 'name' => $x->name, 'description' => $x->meta_description,
                   'url' => route('services.show', $x)],
     ])->all()],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
