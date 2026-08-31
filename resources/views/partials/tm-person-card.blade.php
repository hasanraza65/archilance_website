{{--
  One person, linked to their profile when they have one.
    $p      the TeamMember
    $light  true on paper sections, so the card flips to the light palette
--}}
@php
    $linked = $p->hasPublicProfile();
    $tag = $linked ? 'a' : 'div';
    $pic = $p->photo ?: $p->photo_full;
@endphp

<{{ $tag }} class="tm-card @if($light ?? false) tm-card--light @endif"
   style="--accent: var(--g-{{ $p->team }})"
   @if($linked) href="{{ route('team.show', $p) }}" @endif>
  <span class="tm-card__pic">
    @if($pic)
      <img src="{{ asset($pic) }}" width="120" height="120" alt="{{ $p->name }}" loading="lazy" decoding="async">
    @else
      <span class="tm-card__ph" aria-hidden="true">{{ $p->initials() }}</span>
    @endif
  </span>
  <span class="tm-card__id">
    <b>{{ $p->name }}</b>
    <small>{{ $p->role }}</small>
  </span>
  @if($linked)
    <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
  @endif
</{{ $tag }}>
