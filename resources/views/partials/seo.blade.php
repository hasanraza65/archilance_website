{{-- Every meta tag the site emits lives here, so SEO is one edit, not fourteen. --}}
@php
    $seoTitle       = $seo['title']       ?? $s->get('meta_title');
    $seoDescription = $seo['description'] ?? $s->get('meta_description');
    $seoKeywords    = $seo['keywords']    ?? $s->get('meta_keywords');
    $seoImage       = $seo['image']       ?? $s->get('og_image');
    $seoCanonical   = $seo['canonical']   ?? url()->current();
    $seoRobots      = ($seo['noindex'] ?? false) ? 'noindex, nofollow' : $s->get('robots');
    $seoType        = $seo['type']        ?? 'website';
    $seoImageUrl    = $seoImage ? (str_starts_with($seoImage, 'http') ? $seoImage : asset($seoImage)) : null;
@endphp
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
@if($seoKeywords)<meta name="keywords" content="{{ $seoKeywords }}">@endif
<meta name="author" content="{{ $s->get('site_name') }}">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:type" content="{{ $seoType }}">
<meta property="og:site_name" content="{{ $s->get('site_name') }}">
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
@if($seoImageUrl)
<meta property="og:image" content="{{ $seoImageUrl }}">
<meta property="og:image:alt" content="{{ $seo['imageAlt'] ?? $seoTitle }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if($seoImageUrl)<meta name="twitter:image" content="{{ $seoImageUrl }}">@endif

@stack('schema')
@if($s->get('head_scripts')){!! $s->get('head_scripts') !!}@endif
@if($s->get('google_analytics')){!! $s->get('google_analytics') !!}@endif
