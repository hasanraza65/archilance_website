@extends('layouts.front')

@section('content')

  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell shell--narrow">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">{{ $page->title }}</span></li>
        </ol>
      </nav>
      @if($page->eyebrow)<p class="eyebrow" data-reveal>{{ $page->eyebrow }}</p>@endif
      <h1 class="page-hero__title" data-reveal>
        {{ $page->h1_lead ?: $page->title }} @if($page->h1_gold)<em>{{ $page->h1_gold }}</em>@endif
      </h1>
      @if($page->lede)<p class="page-hero__lede" data-reveal>{{ $page->lede }}</p>@endif
    </div>
  </section>

  <section class="section section--after-hero section--ink grid-veil">
    <div class="shell shell--narrow">
      <div class="prose" data-reveal>
        {!! $page->block('body', '') !!}
      </div>
    </div>
  </section>

@endsection
