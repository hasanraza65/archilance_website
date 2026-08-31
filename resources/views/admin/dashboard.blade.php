@extends('admin.layout')
@section('title', 'Dashboard')
@section('crumb', 'Everything on the site at a glance')

@section('content')
  <div class="grid grid--stats">
    @foreach($stats as $stat)
      <a class="stat-card" href="{{ route($stat['route']) }}">
        <b>{{ $stat['value'] }}</b>
        <span>{{ $stat['label'] }}</span>
      </a>
    @endforeach
  </div>

  <div class="grid grid--2" style="margin-top:1rem">
    <div class="card">
      <div class="card__head">
        <h2>Recent enquiries</h2>
        @if($unread)<span class="pill pill--warn">{{ $unread }} unread</span>@endif
        <a class="btn btn--sm" href="{{ route('admin.enquiries.index') }}">View all</a>
      </div>

      @forelse($enquiries as $e)
        <a href="{{ route('admin.enquiries.show', $e) }}"
           style="display:flex;gap:.6rem;align-items:baseline;padding:.5rem 0;border-bottom:1px solid var(--line)">
          <b style="font-size:.82rem">{{ $e->name }}</b>
          <span style="font-size:.74rem;color:var(--muted)">{{ $e->service ?: 'General' }}</span>
          <span style="margin-left:auto;font-size:.7rem;color:var(--muted)">{{ $e->created_at->diffForHumans() }}</span>
          @if(! $e->is_read)<span class="pill pill--warn">New</span>@endif
        </a>
      @empty
        <p style="color:var(--muted);font-size:.82rem;margin:0">No enquiries yet. They will land here the moment the contact form is used.</p>
      @endforelse
    </div>

    <div class="card">
      <div class="card__head">
        <h2>SEO health</h2>
        <a class="btn btn--sm" href="{{ route('admin.seo.index') }}">Full audit</a>
      </div>
      @foreach($seoGaps as $type => $count)
        <div style="display:flex;align-items:center;gap:.6rem;padding:.45rem 0;border-bottom:1px solid var(--line)">
          <span style="font-size:.82rem;text-transform:capitalize">{{ $type }}</span>
          <span style="margin-left:auto">
            @if($count)
              <span class="pill pill--err">{{ $count }} missing description</span>
            @else
              <span class="pill pill--on">All complete</span>
            @endif
          </span>
        </div>
      @endforeach

      @if($drafts->isNotEmpty())
        <p class="side__label" style="padding-left:0">Unpublished drafts</p>
        @foreach($drafts as $d)
          <a href="{{ route('admin.posts.edit', $d) }}" style="display:block;font-size:.8rem;padding:.3rem 0;color:var(--muted)">
            {{ $d->title }}
          </a>
        @endforeach
      @endif
    </div>
  </div>

  <div class="card">
    <div class="card__head"><h2>Quick actions</h2></div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
      <a class="btn btn--primary" href="{{ route('admin.posts.create') }}">New blog post</a>
      <a class="btn" href="{{ route('admin.projects.create') }}">New project</a>
      <a class="btn" href="{{ route('admin.services.create') }}">New service</a>
      <a class="btn" href="{{ route('admin.team.create') }}">New team member</a>
      <a class="btn" href="{{ route('admin.faqs.create') }}">New FAQ</a>
      <a class="btn" href="{{ route('admin.media.index') }}">Upload media</a>
      <a class="btn" href="{{ route('admin.settings.index') }}">Site settings</a>
    </div>
  </div>
@endsection
