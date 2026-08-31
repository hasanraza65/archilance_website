{{-- stays a <button>: the chart suppresses clicks after a drag by stopping
     propagation, which a real link would ignore and navigate anyway --}}
<button class="onode onode--{{ $m->team }}" type="button"
        data-name="{{ $m->name }}" data-role="{{ $m->role }}"
        data-blurb="{{ $m->blurb }}" data-slug="{{ $m->slug }}" data-group="{{ $m->team }}"
        @if($m->hasPublicProfile()) data-url="{{ route('team.show', $m) }}" @endif
        @if($m->extra) data-extra="{{ $m->extra }}" @endif>
  <span class="onode__pic">
    @if($m->photo)
      <img src="{{ asset($m->photo) }}" width="360" height="360" draggable="false"
           alt="{{ $m->name }} — {{ $m->role }} at {{ $s->get('site_name') }}" loading="lazy" decoding="async">
    @else
      {{-- no portrait yet: initials read as a person, a repeated generic icon
           does not — and a fair few of the newer team have no photo on file --}}
      <span class="org-card__ph" aria-hidden="true">{{ $m->initials() }}</span>
    @endif
  </span>
  <span class="onode__name">{{ $m->name }}</span>
  <span class="onode__role">{{ $m->role }}</span>
</button>
