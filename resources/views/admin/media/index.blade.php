@extends('admin.layout')
@section('title', 'Media library')
@section('crumb', 'Upload once, reuse anywhere on the site')

@section('content')
  <div class="card">
    <div class="card__head"><h2>Upload</h2></div>
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data"
          style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
      @csrf
      <input class="ctrl" type="file" name="files[]" multiple accept="image/*,.pdf,.mp4" style="max-width:340px;padding:.4rem">
      <button class="btn btn--primary" type="submit">Upload</button>
      <span style="font-size:.72rem;color:var(--muted)">JPG, PNG, WebP, SVG, GIF, PDF or MP4 · up to 10 MB each</span>
    </form>
  </div>

  <div class="card">
    <div class="card__head">
      <h2>{{ $items->total() }} uploaded</h2>
      <form class="search" method="GET" style="margin-left:auto">
        <input class="ctrl" type="search" name="q" value="{{ $term }}" placeholder="Search files…">
        <button class="btn btn--sm" type="submit">Search</button>
      </form>
    </div>

    @if($items->isEmpty())
      <div class="empty">
        <h3>No uploads yet</h3>
        <p>The design already ships with a large image set — see below. Uploads land here.</p>
      </div>
    @else
      <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(190px,1fr))">
        @foreach($items as $m)
          <div class="card" style="padding:.6rem">
            @if($m->isImage())
              <img src="{{ $m->url }}" alt="{{ $m->alt }}" loading="lazy"
                   style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:6px;background:var(--ink-800)">
            @else
              <div style="aspect-ratio:4/3;display:grid;place-items:center;background:var(--ink-800);border-radius:6px;color:var(--muted);font-size:.7rem">
                {{ strtoupper(pathinfo($m->name, PATHINFO_EXTENSION)) }}
              </div>
            @endif

            <p style="font-size:.72rem;margin:.5rem 0 .2rem;word-break:break-all">{{ Str::limit($m->name, 34) }}</p>
            <p style="font-size:.68rem;color:var(--muted);margin:0 0 .5rem">
              {{ $m->readable_size }}@if($m->width) · {{ $m->width }}×{{ $m->height }}@endif
            </p>

            <form method="POST" action="{{ route('admin.media.update', $m) }}" style="margin-bottom:.4rem">
              @csrf @method('PUT')
              <input class="ctrl" type="text" name="alt" value="{{ $m->alt }}" placeholder="Alt text"
                     style="font-size:.72rem;padding:.35rem .5rem">
              <button class="btn btn--sm" type="submit" style="margin-top:.35rem;width:100%;justify-content:center">Save alt</button>
            </form>

            <input class="ctrl" type="text" readonly value="{{ $m->path }}" onclick="this.select()"
                   style="font-size:.66rem;padding:.3rem .45rem" title="Click to select, then copy into any image field">

            <form method="POST" action="{{ route('admin.media.destroy', $m) }}"
                  data-confirm="Delete this file permanently?" style="margin-top:.35rem">
              @csrf @method('DELETE')
              <button class="btn btn--sm btn--danger" type="submit" style="width:100%;justify-content:center">Delete</button>
            </form>
          </div>
        @endforeach
      </div>
      <div class="pager">{{ $items->links() }}</div>
    @endif
  </div>

  <div class="card">
    <div class="card__head"><h2>Images bundled with the design</h2></div>
    <p style="font-size:.75rem;color:var(--muted);margin-top:-.5rem">
      Already on the server — paste any path straight into an image field.
    </p>
    @foreach($bundled as $group => $paths)
      <p class="side__label" style="padding-left:0">{{ $group }} · {{ count($paths) }}</p>
      <div class="picker" style="max-height:220px">
        @foreach($paths as $p)
          <button type="button" data-path="{{ $p }}" title="{{ $p }}"
                  onclick="navigator.clipboard.writeText('{{ $p }}'); this.style.borderColor='var(--ok)';">
            <img src="{{ asset($p) }}" alt="" loading="lazy">
          </button>
        @endforeach
      </div>
    @endforeach
    <p style="font-size:.72rem;color:var(--muted);margin-top:.6rem">Click any thumbnail to copy its path.</p>
  </div>
@endsection
