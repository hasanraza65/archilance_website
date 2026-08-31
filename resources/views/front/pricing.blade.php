@extends('layouts.front')

@section('content')

  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">Pricing</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>{{ $page?->lede }}</p>

      <ul class="hero__trust" data-stagger style="margin-top:1.6rem" aria-label="What every plan includes">
        <li class="hero__trust-item">
          <svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>
          <span>{!! $page->text('bar.item1') !!}</span>
        </li>
        <li class="hero__trust-item">
          <svg class="ico" aria-hidden="true"><use href="#i-refresh"></use></svg>
          <span>{!! $page->text('bar.item2') !!}</span>
        </li>
        <li class="hero__trust-item">
          <svg class="ico" aria-hidden="true"><use href="#i-shield"></use></svg>
          <span>{!! $page->text('bar.item3') !!}</span>
        </li>
      </ul>
    </div>
  </section>

  {{-- ============================ The plans ============================= --}}
  <section class="section section--after-hero section--paper grid-veil" id="plans" aria-labelledby="plansHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <h2 class="h-xl" id="plansHeading">{!! $page->text('plans.heading') !!}</h2>
        <p class="lede">{{ $page->text('plans.lede') }}</p>
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
              <a class="btn-a btn-a--ghost-dark btn-a--block" href="{{ route('home') }}#quoteInline">{{ $page->text('plans.quote_link') }}</a>
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
    </div>
  </section>

  {{-- ========================= Which one fits ========================== --}}
  <section class="section section--ink grid-veil" aria-labelledby="fitHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('fit.eyebrow') }}</p>
        <h2 class="h-xl" id="fitHeading">{!! $page->text('fit.heading') !!}</h2>
      </div>

      <div class="pick-grid" data-stagger>
        @foreach($page->text('fit.cards') ?? [] as $card)
          <article class="pick">
            <p class="pick__when">{{ $card['when'] ?? '' }}</p>
            <h3 class="pick__plan">{{ $card['plan'] ?? '' }}</h3>
            <p class="pick__why">{{ $card['why'] ?? '' }}</p>
            <a class="pick__go" href="#plans">
              {{ $card['cta'] ?? 'See the plan' }}
              <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ======================== What you can send ======================== --}}
  <section class="section section--paper grid-veil" aria-labelledby="scopeHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('scope.eyebrow') }}</p>
        <h2 class="h-xl" id="scopeHeading">{!! $page->text('scope.heading') !!}</h2>
        <p class="lede">{{ $page->text('scope.lede') }}</p>
      </div>

      <div class="scope-grid" data-stagger>
        @foreach($services as $svc)
          <a class="scope-cell" href="{{ route('services.show', $svc) }}">
            <span class="scope-cell__ico"><svg class="ico" aria-hidden="true"><use href="#{{ $svc->icon ?: 'i-svc-design' }}"></use></svg></span>
            <span class="scope-cell__name">{{ $svc->name }}</span>
            <svg class="ico scope-cell__go" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- =========================== Comparison ============================ --}}
  <section class="section section--ink-soft" aria-labelledby="cmpHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('cmp.eyebrow') }}</p>
        <h2 class="h-xl" id="cmpHeading">{!! $page->text('cmp.heading') !!}</h2>
      </div>

      <div class="cmp" data-reveal>
        <table class="cmp__table">
          <caption class="visually-hidden">Plan comparison</caption>
          <thead>
            <tr>
              <th scope="col">{{ $page->text('cmp.col0') }}</th>
              @foreach($plans as $plan)
                <th scope="col" @class(['is-featured' => $plan->featured])>{{ $plan->name }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($page->text('cmp.rows') ?? [] as $row)
              <tr>
                <th scope="row">{{ $row['label'] ?? '' }}</th>
                @foreach(['a', 'b', 'c'] as $i => $key)
                  @php $v = $row[$key] ?? ''; @endphp
                  <td @class(['is-featured' => ($plans[$i] ?? null)?->featured])>
                    @if($v === 'yes')
                      <svg class="ico cmp__yes" aria-hidden="true"><use href="#i-check-circle"></use></svg>
                      <span class="visually-hidden">Included</span>
                    @elseif($v === 'no')
                      <span class="cmp__no" aria-hidden="true">&ndash;</span>
                      <span class="visually-hidden">Not included</span>
                    @else
                      {{ $v }}
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- ========================== Fixed price ============================ --}}
  <section class="section section--paper" aria-labelledby="fixedHeading">
    <div class="shell">
      <div class="fixed-price" data-reveal>
        <div>
          <h2 class="h-md" id="fixedHeading">{{ $page->text('fixed.title') }}</h2>
          <p>{{ $page->text('fixed.text') }}</p>
        </div>
        <a class="btn-a btn-a--lg" href="{{ route('home') }}#quoteInline" data-magnetic>
          {{ $page->text('fixed.cta') }}
          <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
      </div>
    </div>
  </section>

  {{-- ======================= Testimonials + FAQ ======================== --}}
  @if($testimonials->isNotEmpty())
    <section class="section section--ink" aria-labelledby="proofHeading">
      <div class="shell">
        <div class="head-row" data-reveal>
          <div class="section-head">
            <p class="eyebrow">{{ $page->text('proof.eyebrow') }}</p>
            <h2 class="h-xl" id="proofHeading">{!! $page->text('proof.heading') !!}</h2>
          </div>
          <div class="slider-nav">
            <button class="slider-btn" type="button" data-tprev aria-label="Previous review">
              <svg class="ico" aria-hidden="true"><use href="#i-arrow-left"></use></svg>
            </button>
            <button class="slider-btn" type="button" data-tnext aria-label="Next review">
              <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </button>
          </div>
        </div>

        <div class="swiper" data-testimonials>
          <div class="swiper-wrapper">
            @foreach($testimonials as $t)
              <article class="swiper-slide">
                <div class="quote-card">
                  <div class="quote-card__stars" role="img" aria-label="Rated {{ $t->rating }} out of 5">
                    @for($i = 0; $i < (int) $t->rating; $i++)
                      <svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>
                    @endfor
                  </div>
                  <p class="quote-card__text">{{ $t->quote }}</p>
                  <button class="quote-card__more" type="button">Read more</button>
                  <div class="quote-card__by">
                    @if($t->avatar)
                      <img src="{{ asset($t->avatar) }}" width="200" height="200" alt="" loading="lazy" decoding="async">
                    @else
                      <span class="quote-card__ph" aria-hidden="true">{{ Str::substr($t->name, 0, 1) }}</span>
                    @endif
                    <span><b>{{ $t->name }}</b><span>{{ $t->company }}</span></span>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>
  @endif

  @if($faqs->isNotEmpty())
    <section class="section section--ink-soft" id="questions" aria-labelledby="pfaqHeading">
      <div class="shell shell--narrow">
        <div class="section-head section-head--center" data-reveal>
          <p class="eyebrow">{{ $page->text('faq.eyebrow') }}</p>
          <h2 class="h-xl" id="pfaqHeading">{!! $page->text('faq.heading') !!}</h2>
        </div>

        <div class="faq" data-reveal style="margin-top:clamp(2rem,4vw,3rem)">
          @foreach($faqs as $faq)
            <div class="faq__item">
              <h3 style="margin:0">
                <button class="faq__q" type="button" aria-expanded="false" aria-controls="pfaq{{ $faq->id }}">
                  {{ $faq->question }}<span class="faq__sign" aria-hidden="true"></span>
                </button>
              </h3>
              <div class="faq__a" id="pfaq{{ $faq->id }}"><div>{!! $faq->answer !!}</div></div>
            </div>
          @endforeach
        </div>

        <p class="text-center" style="margin-top:2rem">
          <a class="link-a" href="{{ route('faq') }}">
            {{ $page->text('faq.more') }}
            <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
          </a>
        </p>
      </div>
    </section>
  @endif

  <section class="cta-band" aria-labelledby="pctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center;display:inline-flex">{{ $page->text('cta.eyebrow') }}</p>
      <h2 class="h-xl" id="pctaHeading" data-reveal>{!! $page->text('cta.heading') !!}</h2>
      <p data-reveal>{{ $page->text('cta.text') }}</p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a btn-a--lg" href="{{ route('home') }}#quoteInline" data-magnetic>
          {{ $page->text('cta.button1') }}
          <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <a class="btn-a btn-a--ghost btn-a--lg" href="{{ route('contact') }}" data-magnetic>{{ $page->text('cta.button2') }}</a>
      </div>
    </div>
  </section>

@endsection

{{-- The reviews carousel needs the slider library; the layout only ships it
     to pages that ask for it. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/swiper.min.js') }}" defer></script>
@endpush
