<!-- ================================ Header ================================ -->
<div class="topbar d-none d-lg-block">
  <div class="shell">
    <div class="topbar__row">
      <ul class="topbar__list">
        <li class="topbar__item">
          <svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>
          <a href="mailto:{{ $s->get('email') }}">{{ $s->get('email') }}</a>
        </li>
        <li class="topbar__item">
          <svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg>
          <a href="tel:{{ $s->get('phone_link') }}">{{ $s->get('phone') }}</a>
        </li>
        <li class="topbar__item">
          <svg class="ico" aria-hidden="true"><use href="#i-clock"></use></svg>
          <span>{{ $s->get('reply_time') }}</span>
        </li>
      </ul>
      <ul class="topbar__list">
        <li class="topbar__item">
          <span class="topbar__stars" aria-hidden="true">★★★★★</span>
          <span>{{ $s->get('rating') }} rated agency on Upwork</span>
        </li>
        <li class="topbar__item">
          <svg class="ico" aria-hidden="true"><use href="#i-shield"></use></svg>
          <span>ISO 9001 certified</span>
        </li>
      </ul>
    </div>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="shell">
    <div class="nav-row">
      <a class="brand" href="{{ route('home') }}" aria-label="Archilance LLC — home">
        <img class="brand__mark" src="{{ asset('assets/img/brand/logo-mark.webp') }}" width="260" height="247" alt="Archilance LLC logo" fetchpriority="high">
        <span class="brand__word"><b>{{ $s->brandName() }}</b><span>{{ $s->brandSuffix() }}</span></span>
      </a>

      <nav class="nav" aria-label="Primary">
        <ul class="nav__list">
          <li><a class="nav__link" href="{{ route('home') }}" @class(['is-current' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Home</a></li>
          <li class="nav__item--has-menu">
            <a class="nav__link" href="{{ route('services.index') }}" aria-haspopup="true" @if(request()->routeIs('services.*')) aria-current="page" @endif>
              Services
              <svg class="nav__caret" aria-hidden="true"><use href="#i-chevron-down"></use></svg>
            </a>
            <div class="mega">
              <div class="mega__grid">
                @foreach($navServices as $svc)
                  <a class="mega__link" href="{{ route('services.show', $svc) }}">
                    <svg class="ico" aria-hidden="true"><use href="#{{ $svc->icon }}"></use></svg>
                    <span><b>{{ $svc->short_name }}</b><small>{{ \Illuminate\Support\Str::limit(strip_tags($svc->lede), 46) }}</small></span>
                  </a>
                @endforeach
              </div>
              <div class="mega__foot">
                <span>Every service is available inside one flat monthly subscription.</span>
                <a class="link-a" href="{{ route('pricing') }}">See pricing<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
              </div>
            </div>
          </li>
          <li><a class="nav__link" href="{{ route('projects.index') }}" @if(request()->routeIs('projects.*')) aria-current="page" @endif>Projects</a></li>
          <li><a class="nav__link" href="{{ route('pricing') }}">Pricing</a></li>
          <li><a class="nav__link" href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a></li>
          <li><a class="nav__link" href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a></li>
          <li><a class="nav__link" href="{{ route('faq') }}" @if(request()->routeIs('faq')) aria-current="page" @endif>FAQs</a></li>
          <li><a class="nav__link" href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a></li>
        </ul>
      </nav>

      <div class="nav-actions">
        <a class="btn-a btn-a--ghost btn-a--sm" href="{{ route('contact') }}" data-magnetic>Get a free trial</a>
        <a class="btn-a btn-a--sm" href="{{ route('contact') }}" data-magnetic>Free consultation</a>
        <button class="burger" type="button" id="burger" aria-expanded="false" aria-controls="drawer" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</header>

<div class="scrim" id="scrim" hidden></div>

<aside class="drawer" id="drawer" aria-label="Mobile menu" aria-hidden="true">
  <div class="drawer__top">
    <a class="brand" href="{{ route('home') }}" aria-label="Archilance LLC — home">
      <img class="brand__mark" src="{{ asset('assets/img/brand/logo-mark.webp') }}" width="260" height="247" alt="" style="height:40px">
      <span class="brand__word"><b>{{ $s->brandName() }}</b><span>{{ $s->brandSuffix() }}</span></span>
    </a>
    <button class="drawer__close" type="button" id="drawerClose" aria-label="Close menu">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-close"></use></svg>
    </button>
  </div>

  <ul class="drawer__nav">
    <li><a class="drawer__link" href="{{ route('home') }}">Home</a></li>
    <li>
      <button class="drawer__link" type="button" aria-expanded="false" aria-controls="drawerServices">
        Services <svg class="ico" aria-hidden="true"><use href="#i-chevron-down"></use></svg>
      </button>
      <div class="drawer__sub" id="drawerServices">
        <ul>
          @foreach($navServices as $svc)
            <li><a href="{{ route('services.show', $svc) }}">{{ $svc->name }}</a></li>
          @endforeach
        </ul>
      </div>
    </li>
    <li><a class="drawer__link" href="{{ route('projects.index') }}">Projects</a></li>
    <li><a class="drawer__link" href="{{ route('pricing') }}">Pricing</a></li>
    <li><a class="drawer__link" href="{{ route('about') }}">About</a></li>
    <li><a class="drawer__link" href="{{ route('blog.index') }}">Blog</a></li>
    <li><a class="drawer__link" href="{{ route('faq') }}">FAQs</a></li>
    <li><a class="drawer__link" href="{{ route('contact') }}">Contact</a></li>
  </ul>

  <div class="drawer__cta">
    <a class="btn-a btn-a--block" href="{{ route('contact') }}">Get a free consultation</a>
    <a class="btn-a btn-a--ghost btn-a--block" href="{{ route('pricing') }}">See pricing</a>
  </div>

  <div class="drawer__meta">
    <a href="mailto:{{ $s->get('email') }}"><svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>{{ $s->get('email') }}</a>
    <a href="tel:{{ $s->get('phone_link') }}"><svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg>{{ $s->get('phone') }}</a>
    <span>★★★★★ &nbsp;5.0 rated agency on Upwork</span>
  </div>
</aside>