<!DOCTYPE html>
<html lang="en" class="admin">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title', 'Dashboard') · {{ $s->get('site_name') }} CMS</title>
<link rel="icon" href="{{ asset('assets/img/brand/favicon-32.png') }}" sizes="32x32" type="image/png">
<link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>

<div class="shell">

  <aside class="side" id="side">
    <a class="side__brand" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/img/brand/logo-mark.webp') }}" alt="">
      <span><b>{{ $s->get('site_name') }}</b><span>Content manager</span></span>
    </a>

    <nav class="side__nav">
      <p class="side__label">Overview</p>
      @php
        $nav = [
          ['admin.dashboard', 'Dashboard', null],
          ['admin.enquiries.index', 'Enquiries', \App\Models\Enquiry::where('is_read', false)->count() ?: null],
          ['admin.quotes.index', 'Quote requests', \App\Models\Quote::where('is_read', false)->count() ?: null],
        ];
      @endphp
      @foreach($nav as [$r, $label, $count])
        <a class="side__link {{ request()->routeIs(str_replace('.index', '', $r) . '*') ? 'is-on' : '' }}" href="{{ route($r) }}">
          <i class="dot"></i>{{ $label }}
          @if($count)<span class="count">{{ $count }}</span>@endif
        </a>
      @endforeach

      <p class="side__label">Content</p>
      @foreach([
        ['admin.pages.index', 'Pages', \App\Models\Page::count()],
        ['admin.services.index', 'Services', \App\Models\Service::count()],
        ['admin.projects.index', 'Projects', \App\Models\Project::count()],
        ['admin.posts.index', 'Blog posts', \App\Models\Post::count()],
        ['admin.post-categories.index', 'Blog categories', \App\Models\PostCategory::count()],
        ['admin.team.index', 'Team', \App\Models\TeamMember::count()],
        ['admin.faqs.index', 'FAQs', \App\Models\Faq::count()],
        ['admin.faq-categories.index', 'FAQ categories', \App\Models\FaqCategory::count()],
        ['admin.testimonials.index', 'Testimonials', \App\Models\Testimonial::count()],
        ['admin.plans.index', 'Pricing plans', \App\Models\Plan::count()],
      ] as [$r, $label, $count])
        <a class="side__link {{ request()->routeIs(str_replace('.index', '', $r) . '.*') ? 'is-on' : '' }}" href="{{ route($r) }}">
          <i class="dot"></i>{{ $label }}<span class="count">{{ $count }}</span>
        </a>
      @endforeach

      <p class="side__label">Site</p>
      @foreach([
        ['admin.media.index', 'Media library'],
        ['admin.seo.index', 'SEO audit'],
        ['admin.redirects.index', 'Redirects'],
        ['admin.settings.index', 'Settings'],
        ['admin.users.index', 'Users'],
      ] as [$r, $label])
        <a class="side__link {{ request()->routeIs(str_replace('.index', '', $r) . '*') ? 'is-on' : '' }}" href="{{ route($r) }}">
          <i class="dot"></i>{{ $label }}
        </a>
      @endforeach
    </nav>

    <div class="side__foot">
      <a href="{{ route('home') }}" target="_blank" rel="noopener">View site &rarr;</a>
    </div>
  </aside>

  <div class="main">
    <header class="top">
      <button class="side__toggle" id="sideToggle" type="button" aria-label="Toggle menu">&#9776;</button>
      <div>
        <h1>@yield('title', 'Dashboard')</h1>
        @hasSection('crumb')<p class="top__crumb">@yield('crumb')</p>@endif
      </div>
      <div class="top__right">
        @yield('actions')
        <span class="who">
          <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
          <b>{{ auth()->user()->name }}</b>
        </span>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button class="btn btn--sm" type="submit">Sign out</button>
        </form>
      </div>
    </header>

    <div class="wrap">
      @if(session('status'))
        <div class="notice notice--ok">{{ session('status') }}</div>
      @endif
      @if(session('error'))
        <div class="notice notice--err">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="notice notice--err">
          {{ $errors->count() }} problem(s): {{ $errors->first() }}
        </div>
      @endif

      @yield('content')
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/admin.js') }}" defer></script>
@stack('scripts')
</body>
</html>
