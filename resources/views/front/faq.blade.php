@extends('layouts.front')

@section('content')


  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">FAQs</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>
        {{ $total }} straight answers. {{ $page?->lede }}
      </p>

      <div class="faq-search" data-reveal>
        <label class="faq-search__box">
          <svg class="ico" aria-hidden="true"><use href="#i-expand"></use></svg>
          <input type="search" id="faqSearch" placeholder="{{ $page->text('search.placeholder') }}" aria-label="Search frequently asked questions" autocomplete="off">
        </label>
        <p class="faq-search__count" id="faqCount" role="status" aria-live="polite"></p>
      </div>
    </div>
  </section>

  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="faqHeading">
    <div class="shell">
      <h2 class="visually-hidden" id="faqHeading">Frequently asked questions</h2>

      <div class="faq-nav" id="faqNav" role="group" aria-label="Filter questions by topic" data-reveal>
        <button class="faq-nav__btn" type="button" data-cat="all" aria-pressed="true">{{ $page->text('search.all_label') }} <span>{{ $total }}</span></button>
        @foreach($categories as $cat)
          <button class="faq-nav__btn" type="button" data-cat="{{ $cat->slug }}" aria-pressed="false">{{ $cat->name }} <span>{{ $cat->publishedFaqs->count() }}</span></button>
        @endforeach
      </div>

      <div class="faq-groups" id="faqGroups">
        @foreach($categories as $cat)
          <section class="faq-group" data-cat="{{ $cat->slug }}" data-reveal>
            <h2 class="faq-group__title">{{ $cat->name }} <span>{{ $cat->publishedFaqs->count() }}</span></h2>
            <div class="faq">
              @foreach($cat->publishedFaqs as $faq)
                <div class="faq__item" data-q="{{ \Illuminate\Support\Str::lower($faq->question . ' ' . strip_tags($faq->answer)) }}">
                  <h3 style="margin:0"><button class="faq__q" type="button" aria-expanded="false" aria-controls="fq{{ $faq->id }}">{{ $faq->question }}<span class="faq__sign" aria-hidden="true"></span></button></h3>
                  <div class="faq__a" id="fq{{ $faq->id }}"><div>{!! $faq->answer !!}</div></div>
                </div>
              @endforeach
            </div>
          </section>
        @endforeach

      </div>

      <p class="faq-empty" id="faqEmpty" hidden>
        {!! $page->text('search.empty') !!} <a class="link-a" href="{{ route('contact') }}">Ask us directly</a> and we will answer within 24 hours.
      </p>
    </div>
  </section>

  <section class="section section--tight section--paper grid-veil" aria-labelledby="stillHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('cta.eyebrow') }}</p>
        <h2 class="h-xl" id="stillHeading">{!! $page->text('cta.heading') !!}</h2>
        <p class="lede">{{ $page->text('cta.text') }}</p>
      </div>
      <div class="cta-band__actions" data-reveal style="margin-top:2rem">
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>{{ $page->text('cta.button1') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost-dark" href="{{ route('pricing') }}" data-magnetic>{{ $page->text('cta.button2') }}</a>
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
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'FAQs', 'item' => route('faq')],
    ]],
    ['@type' => 'FAQPage', 'url' => route('faq'),
     'mainEntity' => $categories->flatMap(fn ($c) => $c->publishedFaqs->map(fn ($f) => [
        '@type' => 'Question', 'name' => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)],
     ]))->values()->all()],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
