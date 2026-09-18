{{--
  Shared shell for every HTTP error page (404, 403, 419, 500, 503, ...).
  Kept as its own tiny layout — not layouts.front — because a 500 means
  something already went wrong; the fewer partials and DB-backed composers
  between the exception and this page rendering, the less likely the error
  page itself fails to render. header/footer pull live nav + settings, which
  is exactly the kind of thing that can be mid-failure during a real outage.
--}}
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#08090a">
<meta name="robots" content="noindex, nofollow">
<title>{{ $title }} — Archilance LLC</title>

<link rel="icon" href="{{ asset('assets/img/brand/favicon-32.png') }}" sizes="32x32" type="image/png">
<link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

<header class="site-header">
  <div class="shell">
    <div class="nav-row">
      <a class="brand" href="/" aria-label="Archilance LLC — home">
        <img class="brand__mark" src="{{ asset('assets/img/brand/logo-mark.webp') }}" width="260" height="247" alt="Archilance LLC logo">
        <span class="brand__word"><b>Archilance</b><span>LLC</span></span>
      </a>
    </div>
  </div>
</header>

<main id="main">
  <section class="page-hero page-hero--flat" style="min-height:70vh;display:flex;align-items:center;">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell text-center">
      <p class="eyebrow" style="justify-content:center">Error {{ $code }}</p>

      <span class="err-ico" aria-hidden="true">
        <svg class="ico"><use href="#i-{{ $icon }}"></use></svg>
      </span>

      <h1 class="page-hero__title" style="max-width:none">{{ $title }}</h1>
      <p class="page-hero__lede" style="margin-left:auto;margin-right:auto">{{ $message }}</p>

      <div class="page-hero__cta" style="justify-content:center;margin-top:2rem">
        <a class="btn-a" href="/" data-magnetic>
          Back to homepage
          <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
        <a class="btn-a btn-a--ghost" href="/contact" data-magnetic>Contact us</a>
      </div>
    </div>
  </section>
</main>

<svg style="display:none" aria-hidden="true">
  <symbol id="i-arrow-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16M13 5l7 7-7 7"/></symbol>
  <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18 14 14 0 010-18z"/></symbol>
  <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.6 19.6 5.6v5.5c0 4.7-3.1 8.9-7.6 10.1-4.5-1.2-7.6-5.4-7.6-10.1V5.6z"/><path d="m8.7 11.9 2.3 2.3 4.3-4.5"/></symbol>
  <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 11a8.5 8.5 0 00-14.6-5L3 9M3.5 13a8.5 8.5 0 0014.6 5l2.9-3"/><path d="M3 4v5h5M21 20v-5h-5"/></symbol>
  <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5.3l3.4 2"/></symbol>
</svg>

<style>
  .err-ico{display:inline-flex;align-items:center;justify-content:center;width:88px;height:88px;border-radius:50%;background:rgba(233,166,63,.08);border:1px solid rgba(233,166,63,.25);margin:1.5rem auto;color:var(--gold)}
  .err-ico .ico{width:40px;height:40px}
</style>
</body>
</html>
