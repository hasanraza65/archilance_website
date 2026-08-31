@extends('layouts.front')

@section('content')

  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell shell--narrow">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('blog.index') }}">Blog</a></li>
          <li><span aria-current="page">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $post->category?->name }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $post->title }}</h1>
      <p class="page-hero__lede" data-reveal>{{ $post->excerpt }}</p>

      <div class="page-hero__foot" data-reveal>
        <span class="post-meta">
          {{ $post->author?->name ?? $s->get('site_name') }}
          · <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j F Y') }}</time>
          · {{ $post->read_minutes }} min read
        </span>
      </div>
    </div>
  </section>

  <article class="section section--after-hero section--ink grid-veil">
    <div class="shell shell--narrow">
      @if($post->cover_image)
        <div class="split__media" data-reveal style="margin-bottom:clamp(2rem,4vw,3rem)">
          <img src="{{ asset($post->cover_image) }}" width="1600" height="900"
               alt="{{ $post->image_alt ?? $post->title }}" loading="eager" decoding="async">
        </div>
      @endif

      <div class="prose" data-reveal>
        {!! $post->body !!}
      </div>

      <div class="works-more">
        <a class="btn-a btn-a--ghost" href="{{ route('blog.index') }}" data-magnetic>
          <svg class="ico" aria-hidden="true"><use href="#i-arrow-left"></use></svg> All articles
        </a>
      </div>
    </div>
  </article>

  @if($related->isNotEmpty())
    <section class="section section--tight section--ink-soft" aria-labelledby="relHeading">
      <div class="shell">
        <div class="section-head section-head--center" data-reveal>
          <p class="eyebrow">Keep reading</p>
          <h2 id="relHeading" class="h2">Related articles</h2>
        </div>
        <div class="rel-grid" data-stagger style="margin-top:clamp(2rem,4vw,3rem)">
          @foreach($related as $r)
            <a class="rel-card" href="{{ route('blog.show', $r) }}">
              <div class="rel-card__ico"><svg class="ico" aria-hidden="true"><use href="#i-svc-permit"></use></svg></div>
              <h3>{{ $r->title }}</h3>
              <p>{{ \Illuminate\Support\Str::limit($r->excerpt, 110) }}</p>
              <span class="svc__more">Read article <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></span>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="section cta-band" aria-labelledby="ctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center">Work with us</p>
      <h2 id="ctaHeading" data-reveal>Need this done on a real project?</h2>
      <p data-reveal>Three days free on a live task, and you talk to the architects doing the work.</p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a" href="{{ route('contact') }}" data-magnetic>Start your free trial<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}" data-magnetic>See our work</a>
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
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.show', $post)],
    ]],
    array_filter([
      '@type' => 'BlogPosting', 'headline' => $post->title, 'description' => $post->excerpt,
      'image' => $post->cover_image ? asset($post->cover_image) : null,
      'datePublished' => $post->published_at?->toIso8601String(),
      'dateModified' => $post->updated_at?->toIso8601String(),
      'author' => ['@type' => 'Organization', 'name' => $s->get('site_name')],
      'publisher' => ['@type' => 'Organization', 'name' => $s->get('site_name')],
      'mainEntityOfPage' => route('blog.show', $post),
    ]),
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
