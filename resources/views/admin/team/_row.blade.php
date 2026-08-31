<td>@if($item->photo)<img class="thumb" style="width:34px;height:34px;border-radius:50%" src="{{ asset($item->photo) }}" alt="">@endif</td>
<td><b>{{ $item->name }}</b></td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->role }}</td>
<td><span class="pill pill--off">{{ $item->teamLabel() }}</span></td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->parent?->name ?? '—' }}</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
