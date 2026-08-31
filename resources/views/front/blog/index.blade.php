@extends('layouts.front')

@section('content')

  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">Blog</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow ?? 'Journal' }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>{{ $page?->lede }}</p>

      <div class="faq-search" data-reveal>
        <form class="faq-search__box" method="GET" action="{{ route('blog.index') }}">
          <svg class="ico" aria-hidden="true"><use href="#i-expand"></use></svg>
          <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $page->text('list.search_placeholder') }}" aria-label="Search the blog">
        </form>
      </div>
    </div>
  </section>

  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="blogHeading">
    <div class="shell shell--wide">
      <h2 class="visually-hidden" id="blogHeading">Articles</h2>

      <div class="faq-nav" data-reveal>
        <a class="faq-nav__btn" href="{{ route('blog.index') }}" @if(! $active) aria-pressed="true" @endif>All <span>{{ $posts->total() }}</span></a>
        @foreach($categories as $cat)
          <a class="faq-nav__btn" href="{{ route('blog.index') }}?category={{ $cat->slug }}"
             @if($active === $cat->slug) aria-pressed="true" @endif>{{ $cat->name }} <span>{{ $cat->posts_count }}</span></a>
        @endforeach
      </div>

      @if($posts->isEmpty())
        <div class="works-empty">
          <h3>{{ $page->text('list.empty_title') }}</h3>
          <p>{{ $page->text('list.empty_text') }}</p>
          <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Ask a question<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        </div>
      @else
        <div class="svc-cards">
          @foreach($posts as $i => $post)
            <article class="svc-card" data-reveal>
              <a class="svc-card__media" href="{{ route('blog.show', $post) }}" tabindex="-1" aria-hidden="true">
                @if($post->cover_image)
                  <img src="{{ asset($post->cover_image) }}" width="880" height="660"
                       alt="" loading="{{ $i < 3 ? 'eager' : 'lazy' }}" decoding="async">
                @endif
                <span class="svc-card__no">{{ $post->published_at?->format('M Y') }}</span>
              </a>
              <div class="svc-card__body">
                <div class="svc-card__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-permit"></use></svg></div>
                <h3><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
                <p>{{ $post->excerpt }}</p>
                <span class="svc__more">
                  {{ $post->category?->name }} · {{ $post->read_minutes }} min read
                  <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
                </span>
              </div>
            </article>
          @endforeach
        </div>

        <div class="works-more">{{ $posts->links() }}</div>
      @endif
    </div>
  </section>

@endsection
