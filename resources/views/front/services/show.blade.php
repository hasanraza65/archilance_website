@extends('layouts.front')

@section('content')


  <!-- ============================== Page header ============================== -->
  <section class="page-hero page-hero--service">
    <div class="page-hero__bg" aria-hidden="true">
      <img class="parallax" data-parallax="6" src="{{ asset('assets/img/services/revit-drafting-hero.webp') }}" srcset="{{ asset('assets/img/services/revit-drafting-hero-sm.webp') }} 900w, {{ asset('assets/img/services/revit-drafting-hero.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high" decoding="async">
    </div>
    <div class="page-hero__veil" aria-hidden="true"></div>

    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('home') }}">Services</a></li>
          <li><span aria-current="page">{{ $service->short_name }}</span></li>
        </ol>
      </nav>

      <div class="page-hero__grid">
        <div>
          <p class="eyebrow" data-reveal><svg class="ico" aria-hidden="true"><use href="#{{ $service->icon }}"></use></svg>{{ $service->short_name }}</p>
          <h1 class="page-hero__title" data-reveal>{{ $service->h1_lead }} <em>{{ $service->h1_gold }}</em></h1>
          <p class="page-hero__lede" data-reveal>{{ $service->lede }}</p>
          <div class="page-hero__cta" data-reveal>
            <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Start a free trial<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
            <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}?c=bim" data-magnetic>See related work</a>
          </div>
        </div>

        <div class="page-hero__foot stat-strip" data-reveal>
          @foreach($service->stats ?? [] as $stat)
            <div class="stat"><b>{!! $stat['value'] !!}</b><span>{!! $stat['label'] !!}</span></div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- ============================== Overview ============================== -->
  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="overviewHeading">
    <div class="shell">
      <div class="split">
        <div data-reveal="left">
          <p class="eyebrow">Overview</p>
          <h2 class="h-xl" id="overviewHeading">What you actually get</h2>
          @foreach($service->intro ?? [] as $para)
            <p>{!! $para !!}</p>
          @endforeach
          <p><a class="link-a" href="{{ route('home') }}#pricing">See how the subscription works<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a></p>
        </div>
        <div class="split__media" data-reveal="right">
          <img src="{{ asset($service->card_image) }}" width="880" height="660" alt="{{ $service->image_alt }}" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="deliver-grid" data-stagger style="margin-top:clamp(2.5rem,5vw,4.5rem)">
        @foreach($service->deliverables ?? [] as $item)
          <article class="deliver">
            <div class="deliver__ico"><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg></div>
            <h3>{!! $item['title'] !!}</h3>
            <p>{!! $item['text'] !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== Process ============================== -->
  <section class="section section--paper grid-veil" aria-labelledby="processHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">How we work</p>
        <h2 class="h-xl" id="processHeading">Four steps, no surprises</h2>
        <p class="lede">Same process on every job, whether it is a single drawing or a full package — so you always know what happens next.</p>
      </div>

      <div class="steps" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($service->process ?? [] as $i => $step)
          <article class="step">
            <div class="step__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{!! $step['title'] !!}</h3>
            <p>{!! $step['text'] !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== Toolset ============================== -->
  <section class="section section--tight section--ink-soft" aria-labelledby="toolsHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">Software</p>
        <h2 id="toolsHeading" class="h2">Delivered in native, editable files</h2>
        <p class="lede">Your team keeps working in the same tools afterwards — nothing arrives flattened or locked.</p>
      </div>
      <div class="tool-strip" data-reveal style="margin-top:clamp(2rem,4vw,3rem)">
        @foreach($service->tools ?? [] as $tool)
          <span class="tool-chip"><i class="dot"></i>{{ $tool }}</span>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== Related work ============================== -->
  <section class="section section--ink grid-veil" aria-labelledby="workHeading">
    <div class="shell shell--wide">
      <div class="section-head" data-reveal>
        <p class="eyebrow">Selected work</p>
        <h2 class="h-xl" id="workHeading">{{ $service->short_name }} projects</h2>
        <p class="lede">A few recent examples. The full archive is filterable by discipline.</p>
      </div>

      <div class="shots" data-stagger style="margin-top:clamp(1.75rem,3vw,2.5rem)">
        @foreach($projects as $p)
          <a class="shot" href="{{ route('projects.index') }}?c={{ $service->portfolio_filter }}">
            <img src="{{ asset($p->card_image) }}" width="{{ $p->card_width }}" height="{{ $p->card_height }}"
                 alt="{{ $p->title }}" loading="lazy" decoding="async">
            <span class="shot__label">{{ $p->title }}</span>
          </a>
        @endforeach
      </div>

      <div class="works-more">
        <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}?c=bim" data-magnetic>
          Browse all {{ strtolower($service->short_name) }} work<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ============================== FAQs ============================== -->
  <section class="section section--paper-deep grid-veil" aria-labelledby="faqHeading">
    <div class="shell">
      <div class="faq-grid">
        <div data-reveal="left">
          <p class="eyebrow">FAQs</p>
          <h2 class="h-xl" id="faqHeading">{{ $service->short_name }} questions</h2>
          <p class="lede">Anything not covered here, ask us directly — you will get a straight answer from an architect, not a sales script.</p>

          <div class="faq-cta mt-4">
            <h3>Talk to an architect</h3>
            <p>Fifteen minutes, no obligation. We will tell you honestly whether we are the right fit for your project.</p>
            <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Book a call<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
          </div>
        </div>

        <div class="faq" data-reveal="right">
          @foreach($service->faqs ?? [] as $i => $faq)
            <div class="faq__item">
              <h3 style="margin:0"><button class="faq__q" type="button" aria-expanded="false" aria-controls="svcfaq{{ $i }}">{!! $faq['q'] !!}<span class="faq__sign" aria-hidden="true"></span></button></h3>
              <div class="faq__a" id="svcfaq{{ $i }}"><div>{!! $faq['a'] !!}</div></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- ============================== Related services ============================== -->
  <section class="section section--tight section--ink" aria-labelledby="relHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">Also available</p>
        <h2 id="relHeading" class="h2">Everything under one subscription</h2>
        <p class="lede">Move hours between disciplines as the project needs them — no new contract, no new rate.</p>
      </div>
      <div class="rel-grid" data-stagger style="margin-top:clamp(2rem,4vw,3rem)">
        @foreach($related as $rel)
          <a class="rel-card" href="{{ route('services.show', $rel) }}">
            <div class="rel-card__ico"><svg class="ico" aria-hidden="true"><use href="#{{ $rel->icon }}"></use></svg></div>
            <h3>{{ $rel->name }}</h3>
            <p>{{ \Illuminate\Support\Str::limit($rel->lede, 110) }}</p>
            <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================== CTA ============================== -->
  <section class="section cta-band" aria-labelledby="ctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center">Start today</p>
      <h2 id="ctaHeading" data-reveal>Try it on a real task, free for three days.</h2>
      <p data-reveal>
        Send one live piece of {{ strtolower($service->short_name) }} work. If the output is not what you wanted,
        you pay nothing — and we keep going until it is.
      </p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Start your free trial<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost" href="{{ route('home') }}#pricing" data-magnetic>See pricing</a>
      </div>
    </div>
  </section>


@endsection

@push('preload')
<link rel="preload" as="image" href="{{ asset($service->hero_image) }}"
      imagesrcset="{{ asset($service->hero_image_sm) }} 900w, {{ asset($service->hero_image) }} 1600w"
      imagesizes="100vw" fetchpriority="high">
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode(array_filter([
  '@context' => 'https://schema.org',
  '@graph' => array_values(array_filter([
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => route('services.index')],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $service->short_name, 'item' => route('services.show', $service)],
    ]],
    ['@type' => 'Service', 'name' => $service->name, 'serviceType' => $service->short_name,
     'description' => $service->meta_description, 'url' => route('services.show', $service),
     'provider' => ['@type' => 'Organization', 'name' => $s->get('site_name'), 'url' => route('home')]],
    $service->faqs ? ['@type' => 'FAQPage', 'mainEntity' => collect($service->faqs)->map(fn ($f) => [
        '@type' => 'Question', 'name' => strip_tags($f['q']),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])],
    ])->all()] : null,
  ])),
])) !!}
</script>
@endpush
