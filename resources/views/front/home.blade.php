@extends('layouts.front')

@section('content')

  <span id="top"></span>

  <!-- ================================= Hero ================================= -->
  <section class="hero" aria-label="Architecture outsourcing services by Archilance LLC">
    <div class="hero__stage" aria-hidden="true">
      <div class="hero__slide is-active" data-slide="0">
        <img src="{{ asset('assets/img/hero/hero-1.webp') }}" srcset="{{ asset('assets/img/hero/hero-1-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-1.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high" decoding="async">
      </div>
      <!-- slides 2 and 3 are fetched once the page is idle (see main.js) -->
      <div class="hero__slide" data-slide="1">
        <img data-src="{{ asset('assets/img/hero/hero-2.webp') }}" data-srcset="{{ asset('assets/img/hero/hero-2-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-2.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" decoding="async">
      </div>
      <div class="hero__slide" data-slide="2">
        <img data-src="{{ asset('assets/img/hero/hero-3.webp') }}" data-srcset="{{ asset('assets/img/hero/hero-3-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-3.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" decoding="async">
      </div>
    </div>
    <div class="hero__veil" aria-hidden="true"></div>

    <div class="shell">
      <div class="hero__grid">
        <div class="hero__copy">
          <p class="hero__badge" data-reveal>
            <span class="hero__badge-dot"><svg class="ico" aria-hidden="true"><use href="#i-bolt"></use></svg></span>
            <span>{!! $page->text('hero.badge') !!}</span>
          </p>

          <h1 class="hero__title" id="heroTitle">
            <span class="mask-line"><span>{!! $page->text('hero.title1') !!}</span></span>
            <span class="mask-line"><span>{!! $page->text('hero.title2') !!}</span></span>
            <span class="mask-line"><span>{!! $page->text('hero.title3') !!}</span></span>
          </h1>

          <p class="hero__sub" data-reveal>{!! $page->text('hero.sub') !!}</p>

          <div class="hero__cta" data-reveal>
            <a class="btn-a btn-a--lg" href="#contact" data-magnetic>
              {{ $page->text('hero.cta1') }}
              <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </a>
            <a class="btn-a btn-a--ghost btn-a--lg" href="#portfolio" data-magnetic>
              <svg class="ico" aria-hidden="true"><use href="#i-play"></use></svg>
              {{ $page->text('hero.cta2') }}
            </a>
          </div>

          {{-- Social proof sits above the fold: the rating, the volume and the
               governance a first-time visitor asks for before reading on. --}}
          <ul class="hero__trust" data-stagger aria-label="Why studios trust Archilance">
            <li class="hero__trust-item">
              <span class="hero__stars" role="img" aria-label="Rated {{ $page->text('trust.rating') }} out of 5">
                <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
                <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
                <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
                <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
                <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
              </span>
              <span><b>{{ $page->text('trust.rating') }}</b> {{ $page->text('trust.rating_note') }}</span>
            </li>
            <li class="hero__trust-item">
              <svg class="ico" aria-hidden="true"><use href="#i-users"></use></svg>
              <span>{!! $page->text('trust.clients') !!}</span>
            </li>
            <li class="hero__trust-item">
              <svg class="ico" aria-hidden="true"><use href="#i-shield"></use></svg>
              <span>{!! $page->text('trust.governance') !!}</span>
            </li>
          </ul>

          <dl class="hero__facts" data-stagger>
            <div class="hero__fact"><dt class="visually-hidden">Years of successful work</dt><dd style="margin:0"><b data-count="{{ $page->text('hero.stat1_v') }}" data-suffix="">0</b><span>{{ $page->text('hero.stat1_l') }}</span></dd></div>
            <div class="hero__fact"><dt class="visually-hidden">Revit models delivered</dt><dd style="margin:0"><b data-count="{{ $page->text('hero.stat2_v') }}" data-suffix="+">0</b><span>{{ $page->text('hero.stat2_l') }}</span></dd></div>
            <div class="hero__fact"><dt class="visually-hidden">Average client rating</dt><dd style="margin:0"><b data-count="{{ $page->text('hero.stat3_v') }}" data-decimals="1">0</b><span>{{ $page->text('hero.stat3_l') }}</span></dd></div>
            <div class="hero__fact"><dt class="visually-hidden">Response time</dt><dd style="margin:0"><b data-count="{{ $page->text('hero.stat4_v') }}" data-suffix="h">0</b><span>{{ $page->text('hero.stat4_l') }}</span></dd></div>
          </dl>
        </div>

        @include('partials.quote-inline')
      </div>
    </div>

    <div class="hero__dots" role="tablist" aria-label="Hero images">
      <button class="hero__dot" type="button" role="tab" aria-selected="true" aria-label="Image 1"></button>
      <button class="hero__dot" type="button" role="tab" aria-selected="false" aria-label="Image 2"></button>
      <button class="hero__dot" type="button" role="tab" aria-selected="false" aria-label="Image 3"></button>
    </div>
    <span class="hero__scroll" aria-hidden="true">Scroll</span>
  </section>

  <!-- ================================ Trust ================================= -->
  <section class="trust" aria-label="Firms that work with Archilance">
    <p class="trust__label">Trusted by architects, builders and developers worldwide</p>
    <div class="marquee" data-marquee>
      <ul class="marquee__track">
        <li><img src="{{ asset('assets/img/clients/lr-architects.webp') }}" width="200" height="52" alt="LR Architects" loading="lazy" decoding="async"></li>
        <li><img src="{{ asset('assets/img/clients/mxb-investment.webp') }}" width="200" height="46" alt="MXB Investment" loading="lazy" decoding="async"></li>
        <li><img src="{{ asset('assets/img/clients/easypermitco.webp') }}" width="200" height="52" alt="EasyPermitCo" loading="lazy" decoding="async"></li>
        <li><img src="{{ asset('assets/img/clients/gill-construction.webp') }}" width="200" height="44" alt="Gill Construction" loading="lazy" decoding="async"></li>
        <li><img src="{{ asset('assets/img/clients/glamp-coach.webp') }}" width="200" height="52" alt="Glamp Coach" loading="lazy" decoding="async"></li>
        <li><img src="{{ asset('assets/img/clients/bex-collective.webp') }}" width="200" height="52" alt="The Bex Collective" loading="lazy" decoding="async"></li>
      </ul>
    </div>
  </section>

  <!-- =============================== Why us ================================= -->
  <section class="section section--paper grid-veil" id="why" aria-labelledby="whyTitle">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('why.eyebrow') }}</p>
        <h2 class="h-xl" id="whyTitle">{!! $page->text('why.heading') !!}</h2>
        <p class="lede">{{ $page->text('why.lede') }}</p>
      </div>

      <div class="value-grid" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($page->text('why.cells') ?? [] as $cell)
          <article class="value-cell">
            <div class="value-cell__ico"><svg class="ico" aria-hidden="true"><use href="#{{ $cell['icon'] ?? 'i-wallet' }}"></use></svg></div>
            <h3>{!! $cell['title'] ?? '' !!}</h3>
            <p>{!! $cell['text'] ?? '' !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- =============================== Services =============================== -->
  <section class="section section--ink" id="services" aria-labelledby="servicesTitle">
    <div class="shell">
      <div class="head-row" data-reveal>
        <div class="section-head">
          <p class="eyebrow">{{ $page->text('services.eyebrow') }}</p>
          <h2 class="h-xl" id="servicesTitle">{!! $page->text('services.heading') !!}</h2>
        </div>
        <a class="btn-a btn-a--ghost" href="#contact" data-magnetic>{{ $page->text('services.cta') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
      </div>

      <div class="svc-grid" data-stagger>
        <article class="svc" id="svc-architecture-design">
          <span class="svc__no">01</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-design"></use></svg></div>
          <h3>Architecture Design Services</h3>
          <p>A broad range of architectural design work — concept, schematic, design development — delivered by highly qualified architects.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'architecture-design') }}" aria-label="Architecture Design Services — read more"></a>
        </article>

        <article class="svc" id="svc-landscape">
          <span class="svc__no">02</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-landscape"></use></svg></div>
          <h3>Landscape Architectural Services</h3>
          <p>Landscape architecture crafted for sustainability and function — outdoor spaces that harmonise with nature and the building.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'landscape-architecture') }}" aria-label="Landscape Architectural Services — read more"></a>
        </article>

        <article class="svc" id="svc-revit">
          <span class="svc__no">03</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-revit"></use></svg></div>
          <h3>Revit Drafting Outsourcing</h3>
          <p>Precise, detailed Revit drafting with smart BIM models — seamless collaboration, fewer errors, exceptional quality.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'revit-drafting') }}" aria-label="Revit Drafting and BIM Outsourcing — read more"></a>
        </article>

        <article class="svc" id="svc-permit">
          <span class="svc__no">04</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-permit"></use></svg></div>
          <h3>Construction &amp; Permit Sets</h3>
          <p>Reliable, location-compliant construction and permit sets with streamlined documentation that gets projects approved.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'construction-permit-sets') }}" aria-label="Construction and Permit Sets — read more"></a>
        </article>

        <article class="svc" id="svc-render">
          <span class="svc__no">05</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-render"></use></svg></div>
          <h3>3D Modeling &amp; Rendering</h3>
          <p>Expert 3D models and renders that deliver stunning, realistic visuals — the kind that win the client in the first meeting.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', '3d-modeling-rendering') }}" aria-label="3D Modeling and Rendering — read more"></a>
        </article>

        <article class="svc" id="svc-animation">
          <span class="svc__no">06</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-animation"></use></svg></div>
          <h3>3D Architectural Animation</h3>
          <p>Captivating 3D animations that bring designs to life with dynamic, realistic visual storytelling and cinematic walkthroughs.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', '3d-architectural-animation') }}" aria-label="3D Architectural Animation — read more"></a>
        </article>

        <article class="svc" id="svc-scan-to-bim">
          <span class="svc__no">07</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-scan"></use></svg></div>
          <h3>Point Cloud to BIM</h3>
          <p>Laser scan and point cloud data converted into accurate, LOD-ready Revit models for renovation and as-built documentation.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'point-cloud-to-bim') }}" aria-label="Point Cloud to BIM — read more"></a>
        </article>

        <article class="svc" id="svc-interior">
          <span class="svc__no">08</span>
          <div class="svc__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-interior"></use></svg></div>
          <h3>Interior Design Services</h3>
          <p>Interiors designed for atmosphere and function — material palettes, lighting schemes and layouts resolved room by room.</p>
          <span class="svc__more">View service <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
          <a class="svc__link" href="{{ route('services.show', 'interior-design') }}" aria-label="Interior Design Services — read more"></a>
        </article>
      </div>
    </div>
  </section>

  <!-- ================================ Process =============================== -->
  <section class="section section--paper grid-veil" id="process" aria-labelledby="processTitle">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('process.eyebrow') }}</p>
        <h2 class="h-xl" id="processTitle">{!! $page->text('process.heading') !!}</h2>
        <p class="lede">{{ $page->text('process.lede') }}</p>
      </div>

      <div class="steps" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($page->text('process.steps') ?? [] as $step)
          <article class="step">
            <div class="step__no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
            <h3>{!! $step['title'] ?? '' !!}</h3>
            <p>{!! $step['text'] ?? '' !!}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- =============================== Portfolio ============================== -->
  <section class="section section--ink" id="portfolio" aria-labelledby="portfolioTitle">
    <div class="shell shell--wide">
      <div class="head-row" data-reveal>
        <div class="section-head">
          <p class="eyebrow">{{ $page->text('portfolio.eyebrow') }}</p>
          <h2 class="h-xl" id="portfolioTitle">{!! $page->text('portfolio.heading') !!}</h2>
          <p class="lede">{{ $page->text('portfolio.lede') }}</p>
        </div>
        <a class="btn-a btn-a--ghost" href="#contact" data-magnetic>{{ $page->text('portfolio.cta') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
      </div>

      <div class="filters" role="group" aria-label="Filter projects by discipline" data-reveal>
        <button class="filter" type="button" data-filter="all" aria-pressed="true">All work <span>18</span></button>
        <button class="filter" type="button" data-filter="design" aria-pressed="false">Architectural Design</button>
        <button class="filter" type="button" data-filter="render" aria-pressed="false">3D Rendering</button>
        <button class="filter" type="button" data-filter="modeling" aria-pressed="false">3D Modeling</button>
        <button class="filter" type="button" data-filter="interior" aria-pressed="false">Interior Design</button>
        <button class="filter" type="button" data-filter="landscape" aria-pressed="false">Landscape</button>
        <button class="filter" type="button" data-filter="bim" aria-pressed="false">Revit, BIM &amp; Drafting</button>
      </div>

      <div class="works" id="works">
        @foreach($projects as $i => $project)
          <a class="work {{ $project->span === 'wide' ? 'work--wide' : ($project->span === 'tall' ? 'work--tall' : '') }}"
             href="{{ route('projects.show', $project) }}" data-cat="{{ $project->categories }}"
             data-full="{{ asset($project->full_image) }}"
             data-title="{{ $project->title }}" data-desc="{{ $project->description }}">
            <img src="{{ asset($project->card_image) }}" width="{{ $project->card_width }}" height="{{ $project->card_height }}"
                 alt="{{ $project->image_alt }}" @if($i > 3) loading="lazy" @endif decoding="async">
            <span class="work__veil"></span>
            <span class="work__go"><svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
            <span class="work__body">
              <span class="work__cat">{{ $project->category_label }}</span>
              <span class="work__title">{{ $project->title }}</span>
              <span class="work__desc">{{ $project->description }}</span>
            </span>
          </a>
        @endforeach
      
      </div>

      <p class="text-center text-muted-soft mt-4" id="worksEmpty" hidden>No projects in this category yet — try another filter.</p>

      <div class="works-more">
        <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}" data-magnetic>
          {{ $page->text('portfolio.more') }}
          <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <p class="works-more__hint">{{ $page->text('portfolio.more_hint') }}</p>
      </div>
    </div>
  </section>

  <!-- ================================ Pricing =============================== -->
  <section class="section section--paper grid-veil" id="pricing" aria-labelledby="pricingTitle">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('pricing.eyebrow') }}</p>
        <h2 class="h-xl" id="pricingTitle">{!! $page->text('pricing.heading') !!}</h2>
        <p class="lede">{{ $page->text('pricing.lede') }}</p>
      </div>

      <div class="plans" style="margin-top:clamp(2.5rem,5vw,4rem)" data-stagger>
        @foreach($plans as $plan)
          <article class="plan {{ $plan->featured ? 'plan--featured' : '' }}">
            @if($plan->featured)<span class="plan__flag">Most popular</span>@endif
            <h3 class="plan__name">{{ $plan->name }}</h3>
            <p class="plan__price"><b>${{ $plan->price }}</b><span>{{ $plan->period }}</span></p>
            @if($plan->hours)<p class="plan__meta">{!! $plan->hours !!}</p>@endif
            <div class="plan__cta">
              <a class="btn-a btn-a--block" href="{{ $plan->cta_url ?: route('contact') }}" data-magnetic>{{ $plan->cta_label ?: 'Subscribe' }}</a>
              <a class="btn-a btn-a--ghost-dark btn-a--block" href="{{ route('faq') }}#pricing">{{ $page->text('pricing.faq_link') }}</a>
            </div>
            <p class="plan__feats-title">Features</p>
            <ul class="plan__feats">
              @foreach($plan->features ?? [] as $f)
                <li><svg class="ico" aria-hidden="true"><use href="#i-check"></use></svg>{{ $f }}</li>
              @endforeach
            </ul>
          </article>
        @endforeach

      </div>

      <div class="fixed-price" data-reveal>
        <div>
          <h3 class="h-md">{{ $page->text('pricing.fixed_title') }}</h3>
          <p>{{ $page->text('pricing.fixed_text') }}</p>
        </div>
        <a class="btn-a btn-a--lg" href="#contact" data-magnetic>{{ $page->text('pricing.fixed_cta') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
      </div>
    </div>
  </section>

  <!-- ================================= About ================================ -->
  <section class="section section--ink" id="about" aria-labelledby="aboutTitle">
    <div class="shell">
      <div class="about-grid">
        <div class="about-media" data-reveal="left">
          <span class="about-media__corner about-media__corner--tl" aria-hidden="true"></span>
          <span class="about-media__corner about-media__corner--br" aria-hidden="true"></span>
          <div class="about-media__frame">
            <img class="parallax" data-parallax="8" src="{{ asset('assets/img/brand/founders.webp') }}" width="1200" height="1176" alt="Archilance LLC co-founders Asad Kamal Abbasi and Faran Shahbaz Khan" loading="lazy" decoding="async">
          </div>
          <p class="about-media__years"><b>6</b><span>Years of successful work</span></p>
        </div>

        <div data-reveal="right">
          <p class="eyebrow">{{ $page->text('about.eyebrow') }}</p>
          <h2 class="h-xl" id="aboutTitle">{!! $page->text('about.heading') !!}</h2>
          <p class="lede">{{ $page->text('about.lede') }}</p>
          <p class="text-muted-soft">{{ $page->text('about.body') }}</p>

          <div class="founders">
            <div class="founder"><b>{{ $page->text('about.founder1_name') }}</b><span>{!! $page->text('about.founder1_role') !!}</span></div>
            <div class="founder"><b>{{ $page->text('about.founder2_name') }}</b><span>{!! $page->text('about.founder2_role') !!}</span></div>
          </div>

          <div class="about-badges">
            <img src="{{ asset('assets/img/brand/upwork-badge.webp') }}" width="220" height="220" alt="5-star rated Upwork agency badge" loading="lazy" decoding="async" style="height:66px;width:auto">
            <img src="{{ asset('assets/img/brand/iso-9001.webp') }}" width="220" height="220" alt="ISO 9001 certified company badge" loading="lazy" decoding="async" style="height:66px;width:auto">
            <p class="about-badges__text">{{ $page->text('about.badges_text') }}</p>
          </div>

          <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn-a" href="#contact" data-magnetic>{{ $page->text('about.cta1') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
            <a class="btn-a btn-a--ghost" href="#portfolio" data-magnetic>{{ $page->text('about.cta2') }}</a>
          </div>
        </div>
      </div>

      <div class="stat-strip" style="margin-top:clamp(3rem,6vw,5rem);padding-top:clamp(3rem,6vw,5rem);border-top:1px solid var(--line-dark)" data-stagger>
        <div class="stat"><b data-count="{{ $page->text('about.stat1_v') }}">0</b><span>{{ $page->text('about.stat1_l') }}</span></div>
        <div class="stat"><b data-count="{{ $page->text('about.stat2_v') }}" data-suffix="+">0</b><span>{{ $page->text('about.stat2_l') }}</span></div>
        <div class="stat"><b data-count="{{ $page->text('about.stat3_v') }}" data-suffix="+">0</b><span>{{ $page->text('about.stat3_l') }}</span></div>
        <div class="stat"><b data-count="{{ $page->text('about.stat4_v') }}" data-suffix="h">0</b><span>{{ $page->text('about.stat4_l') }}</span></div>
      </div>
    </div>
  </section>

  <!-- ============================= Testimonials ============================= -->
  <section class="section section--paper" id="testimonials" aria-labelledby="testimonialsTitle">
    <div class="shell">
      <div class="head-row" data-reveal>
        <div class="section-head">
          <p class="eyebrow">{{ $page->text('testimonials.eyebrow') }}</p>
          <h2 class="h-xl" id="testimonialsTitle">{!! $page->text('testimonials.heading') !!}</h2>
        </div>
        <div class="slider-nav">
          <button class="slider-btn" type="button" data-tprev aria-label="Previous testimonial"><svg class="ico" aria-hidden="true"><use href="#i-arrow-left"></use></svg></button>
          <button class="slider-btn" type="button" data-tnext aria-label="Next testimonial"><svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></button>
        </div>
      </div>

      <div class="swiper" id="testimonialSwiper" data-testimonials>
        <div class="swiper-wrapper">

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I originally met Faran through Upwork when seeking a 3D rendering architect for the plans I had designed in the US. As a builder and designer, I am very particular with the details from blueprints to photorealistic renderings, so I was skeptical not being fully aware of the quality Faran could produce. Now working on my 4th project with Faran — new construction home, ADUs, and large remodel/additions — I cannot recommend both him and his team enough. Their level of communication through the process, eagerness to have a satisfied client, and skill level to produce quality blueprints and renderings ranks among the top of any local architect I have worked with.</p>
              <button class="quote-card__more" type="button">Read more</button>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/richard-dyer.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Richard Dyer</b><span>Dyer Craftsmen and Design</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I had the pleasure of working with Faran Shahbaz and his Archilance team for three years, during which we collaborated on creating over 600 models in Revit. Their expertise in Revit and commitment to delivering high-quality, photorealistic renderings were instrumental in bringing our projects to life. Faran managed the BIM team with exceptional proficiency, ensuring that all tasks were completed efficiently and accurately. Their use of ClickUp to track and manage our records was particularly noteworthy, as it kept our projects organised and on schedule. I highly recommend Archilance LLC to anyone seeking top-notch architectural and BIM services.</p>
              <button class="quote-card__more" type="button">Read more</button>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/samuel-williams.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Samuel Williams</b><span>Co-Founder, CustomHome.ai</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I initially met Faran by hiring him on Upwork. It was a fantastic working relationship and I was thrilled to learn that he created Archilance LLC. We continue to work on projects where he and his team support me in building information modeling, drafting, as well as rendering services. Faran and his team at Archilance create a team environment where we work together to solve design problems — they are efficient, skilled, and a pleasure to work with. I cannot say enough and strongly recommend Faran and Archilance for any design and architecture needs!</p>
              <button class="quote-card__more" type="button">Read more</button>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/lauren-staniec.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Lauren Staniec</b><span>re-habitat LLC</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I had the pleasure of working with Faran and his team on a residential remodeling project, where they provided detailed construction drawings and renderings. Their attention to detail, professionalism, and expertise were evident throughout the entire process. Not only did they deliver high-quality work, they were incredibly responsive and a joy to collaborate with — consistently addressing questions and revisions promptly. I would highly recommend Archilance to anyone in need of detailed construction drawings and renderings.</p>
              <button class="quote-card__more" type="button">Read more</button>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/rebecca-hobart.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Rebecca Hobart</b><span>thebexcollective.com</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">Faran and his team have been great to work with. I recommend them especially for point cloud as-built drawings — they have done an amazing job.</p>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/justin-garcia.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>J. Justin Garcia</b><span>AE Leuken Architectural Engineers</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I highly recommend Faran Shahbaz and Archilance for architectural design services. Their expertise in innovative, functional and aesthetically compelling designs ensures high-quality project execution. With a strong focus on precision, creativity and client satisfaction, they deliver exceptional solutions tailored to diverse architectural needs.</p>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/randy-h.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Randy H.</b><span>R Build &amp; Design</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">I highly recommend Faran for his exceptional skills as a CAD designer and 3D rendering expert. We have the pleasure of contracting him for 3D models and renderings, both for our customers and our internal needs. Faran consistently delivers work that is professional, accurate and artistically impressive. What sets him apart is not only his technical expertise but also his responsiveness and prompt communication, which make working with him seamless.</p>
              <button class="quote-card__more" type="button">Read more</button>
              <div class="quote-card__by">
                <span class="quote-card__ph" aria-hidden="true">JC</span>
                <span><b>Jeffery Chan</b><span>via LinkedIn</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">Asad was amazing for our project! He is incredibly responsive and professional. We completely re-designed a space into our new location. All plans were perfect for permits and we are now open for business after having implemented the plans we built together. Highly recommend!</p>
              <div class="quote-card__by">
                <span class="quote-card__ph" aria-hidden="true">B</span>
                <span><b>Brandon</b><span>via Upwork</span></span>
                <span class="quote-card__src">Upwork</span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">It was a pleasure to work with Asad. He followed instructions with strong attention to detail and accepted all my adjustments without a single issue. On top of that, his responsiveness was excellent. A very professional and capable architect that I would not hesitate to recommend to my network.</p>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/ivan.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Ivan</b><span>via Upwork</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">Asad was extremely mindful about fully understanding the scope of the project and willing to go above and beyond even when things took a slight pivot. He is an extremely skilled architect and I plan to work with him again in the future.</p>
              <div class="quote-card__by">
                <img src="{{ asset('assets/img/people/omar.webp') }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                <span><b>Omar</b><span>via Upwork</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">Faran made quick work of a model I needed constructed for a permit document. The roof was challenging, and he was able to present it well. Will use him in the future.</p>
              <div class="quote-card__by">
                <span class="quote-card__ph" aria-hidden="true">EH</span>
                <span><b>Elizabeth Hunt Interiors</b><span>via Upwork</span></span>
              </div>
            </div>
          </article>

          <article class="swiper-slide">
            <div class="quote-card">
              <div class="quote-card__stars" role="img" aria-label="Rated 5 out of 5"><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg><svg class="ico"><use href="#i-star"></use></svg></div>
              <p class="quote-card__text">Asad Abbasi is one of the best designers I have ever worked with. He listened to me every time I asked for modifications, he was polite with every request, and the render quality was exactly as I asked. Truly a pleasure to work with — I will hire him again for another big project.</p>
              <div class="quote-card__by">
                <span class="quote-card__ph" aria-hidden="true">N</span>
                <span><b>Nozti Ltd</b><span>via Upwork</span></span>
              </div>
            </div>
          </article>

        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>

  <!-- =========================== FAQ + coverage ============================= -->
  <section class="section section--ink-soft" id="faq" aria-labelledby="faqTitle">
    <div class="shell">
      <div class="faq-grid">
        <div data-reveal="left">
          <p class="eyebrow">{{ $page->text('faq.eyebrow') }}</p>
          <h2 class="h-xl" id="faqTitle">{!! $page->text('faq.heading') !!}</h2>
          <p class="lede">{{ $page->text('faq.lede') }}</p>

          <div class="faq-cta mt-4">
            <h3>{{ $page->text('faq.cta_title') }}</h3>
            <p>{{ $page->text('faq.cta_text') }}</p>
            <a class="btn-a" href="#contact" data-magnetic>{{ $page->text('faq.cta_button') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
          </div>
        </div>

        <div class="faq" data-reveal="right">
          @foreach($faqs as $faq)
            <div class="faq__item">
              <h3 style="margin:0"><button class="faq__q" type="button" aria-expanded="false" aria-controls="hfaq{{ $faq->id }}">{{ $faq->question }}<span class="faq__sign" aria-hidden="true"></span></button></h3>
              <div class="faq__a" id="hfaq{{ $faq->id }}"><div>{!! $faq->answer !!}</div></div>
            </div>
          @endforeach
        
        </div>
      </div>

      <div class="coverage-grid">
        <div data-reveal="left">
          <p class="eyebrow">{{ $page?->text('coverage.eyebrow') }}</p>
          <h2 class="h-lg">{!! $page?->text('coverage.heading') !!}</h2>
          <p class="lede">{{ $page?->text('coverage.lede') }}</p>
          <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn-a" href="{{ route('contact') }}" data-magnetic>{{ $page->text('coverage.cta') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
          </div>
        </div>

        <div class="regions" data-stagger>
          @foreach($page?->text('coverage.regions') ?? [] as $r)
            <div class="region"><b>{{ $r['name'] ?? '' }}</b><span>{!! $r['note'] ?? '' !!}</span></div>
          @endforeach
        </div>
      </div>

    </div>
  </section>

  <!-- ================================ Contact =============================== -->
  <section class="section section--ink grid-veil" id="contact" aria-labelledby="contactTitle">
    <div class="shell">
      <div class="contact-grid">
        <div data-reveal="left">
          <p class="eyebrow">{{ $page->text('contact.eyebrow') }}</p>
          <h2 class="h-xl" id="contactTitle">{!! $page->text('contact.heading') !!}</h2>
          <p class="lede">{{ $page->text('contact.lede') }}</p>

          <div class="contact-points">
            <div class="contact-point">
              <svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>
              <div><b>Email us at</b><a href="mailto:{{ $s->get('email') }}">{{ $s->get('email') }}</a></div>
            </div>
            <div class="contact-point">
              <svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg>
              <div><b>Call us at</b><a href="tel:{{ $s->get('phone_link') }}">{{ $s->get('phone') }}</a></div>
            </div>
            <div class="contact-point">
              <svg class="ico" aria-hidden="true"><use href="#i-pin"></use></svg>
              <div><b>United States</b><span>611 South DuPont Highway, Suite 102<br>Dover, Delaware</span></div>
            </div>
            <div class="contact-point">
              <svg class="ico" aria-hidden="true"><use href="#i-pin"></use></svg>
              <div><b>Pakistan</b><span>Office 106, 4th Floor, Mega Tower<br>Gulberg 3, Lahore</span></div>
            </div>
          </div>

          <ul class="promises">
            <li class="promise"><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>We respond to every enquiry within 24 hours.</li>
            <li class="promise"><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>NDAs signed before any files change hands.</li>
            <li class="promise"><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>No obligation — the first call is a scoping call, not a pitch.</li>
          </ul>
        </div>

        <div class="form-panel" data-reveal="right">
          <!--
            Wire this form to your backend by setting data-endpoint to a POST URL
            (Formspree, Netlify Forms, your own /api/contact, etc.).
            With no endpoint set, submitting opens the visitor's mail client pre-filled.
          -->
          <form id="leadForm" method="POST" action="{{ route('enquiry.store') }}" novalidate data-endpoint="{{ route('enquiry.store') }}">
            @csrf
            <div class="field-row field-row--2">
              <div class="field">
                <label for="fName">Name <span class="req">*</span></label>
                <input class="ctrl" type="text" id="fName" name="name" autocomplete="name" placeholder="Jane Doe" required>
              </div>
              <div class="field">
                <label for="fEmail">Email <span class="req">*</span></label>
                <input class="ctrl" type="email" id="fEmail" name="email" autocomplete="email" placeholder="jane@studio.com" required>
              </div>
            </div>

            <div class="field-row field-row--2">
              <div class="field">
                <label for="fPhone">Phone <span style="opacity:.6">(optional)</span></label>
                <input class="ctrl" type="tel" id="fPhone" name="phone" autocomplete="tel" placeholder="+1 555 000 0000">
              </div>
              <div class="field">
                <label for="fBudget">Budget size</label>
                <select class="ctrl" id="fBudget" name="budget">
                  <option value="">Select a range</option>
                  <option>Under $1,000</option>
                  <option>$1,000 – $2,500</option>
                  <option>$2,500 – $5,000</option>
                  <option>$5,000 – $10,000</option>
                  <option>$10,000+</option>
                  <option>Monthly subscription</option>
                </select>
              </div>
            </div>

            <div class="field">
              <label for="fQuery">Tell us about the project <span class="req">*</span></label>
              <textarea class="ctrl" id="fQuery" name="query" placeholder="Project type, software, scope and deadline…" required></textarea>
            </div>

            <div class="field-row field-row--2">
              <div class="field">
                <label for="fSource">How did you hear about us?</label>
                <select class="ctrl" id="fSource" name="source">
                  <option value="">Select an option</option>
                  <option>Google search</option>
                  <option>Upwork</option>
                  <option>LinkedIn</option>
                  <option>Behance</option>
                  <option>Referral</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="field">
                <label for="fWhen">Preferred call date &amp; time</label>
                <input class="ctrl" type="datetime-local" id="fWhen" name="when">
              </div>
            </div>

            <label class="check">
              <input type="checkbox" name="nda" value="yes">
              <span>This project requires an NDA — please send one before we share files.</span>
            </label>

            <!-- honeypot: hidden from humans, catches bots -->
            <div style="position:absolute;left:-9999px" aria-hidden="true">
              <label for="fCompanyUrl">Leave this empty</label>
              <input type="text" id="fCompanyUrl" name="company_url" tabindex="-1" autocomplete="off">
            </div>

            <button class="btn-a btn-a--lg btn-a--block" type="submit" data-magnetic>
              Send my enquiry
              <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </button>

            <p class="form-status" id="formStatus" role="status" aria-live="polite" @if(session('status')) data-state="ok" @endif>{{ session('status') }}</p>
            @if($errors->any())<p class="form-status" data-state="err">{{ $errors->first() }}</p>@endif
            <p class="form-note">We reply within 24 hours. Your details are never shared or sold.</p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- =============================== CTA band =============================== -->
  <section class="cta-band" aria-labelledby="ctaTitle">
    <div class="shell">
      <p class="eyebrow" style="justify-content:center;display:inline-flex">{{ $page->text('cta.eyebrow') }}</p>
      <h2 class="h-xl" id="ctaTitle">{!! $page->text('cta.heading') !!}</h2>
      <p>{{ $page->text('cta.text') }}</p>
      <div class="cta-band__actions">
        <a class="btn-a btn-a--lg" href="#contact" data-magnetic>{{ $page->text('cta.button1') }}<svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost btn-a--lg" href="#pricing" data-magnetic>{{ $page->text('cta.button2') }}</a>
      </div>
    </div>
  </section>
@endsection

@push('preload')
<link rel="preload" as="image" href="{{ asset('assets/img/hero/hero-1.webp') }}"
      imagesrcset="{{ asset('assets/img/hero/hero-1-sm.webp') }} 900w, {{ asset('assets/img/hero/hero-1.webp') }} 1600w"
      imagesizes="100vw" fetchpriority="high">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/swiper.min.js') }}" defer></script>
@endpush

@push('modals')
@include('partials.lightbox')
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    ['@type' => 'ProfessionalService', '@id' => route('home') . '#organization',
     'name' => $s->get('site_name'), 'url' => route('home'),
     'description' => $s->get('meta_description'),
     'email' => $s->get('email'), 'telephone' => $s->get('phone'),
     'priceRange' => '$$',
     'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => $s->get('rating', '5'),
                           'bestRating' => '5', 'ratingCount' => $s->get('reviews', '19')],
     'hasOfferCatalog' => ['@type' => 'OfferCatalog', 'name' => 'Architecture outsourcing subscriptions',
        'itemListElement' => $plans->map(fn ($p) => [
            '@type' => 'Offer', 'name' => $p->name,
            'price' => preg_replace('/[^0-9.]/', '', $p->price), 'priceCurrency' => 'USD',
            'description' => $p->rate_note,
        ])->all()]],
    ['@type' => 'WebSite', '@id' => route('home') . '#website',
     'url' => route('home'), 'name' => $s->get('site_name')],
    ['@type' => 'FAQPage', 'mainEntity' => $faqs->map(fn ($f) => [
        '@type' => 'Question', 'name' => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)],
    ])->all()],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
