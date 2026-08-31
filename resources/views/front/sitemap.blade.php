<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $u)
  <url>
    <loc>{{ $u['loc'] }}</loc>
@isset($u['lastmod'])
    <lastmod>{{ $u['lastmod']->toDateString() }}</lastmod>
@endisset
    <changefreq>{{ $u['freq'] }}</changefreq>
    <priority>{{ $u['priority'] }}</priority>
@if($u['loc'] === route('projects.index'))
@foreach($images as $img)
    <image:image><image:loc>{{ asset($img->full_image) }}</image:loc><image:title>{{ $img->title }}</image:title></image:image>
@endforeach
@endif
  </url>
@endforeach
</urlset>
