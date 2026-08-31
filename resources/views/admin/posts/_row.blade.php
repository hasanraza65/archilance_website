<td>@if($item->cover_image)<img class="thumb" src="{{ asset($item->cover_image) }}" alt="">@endif</td>
<td><b>{{ $item->title }}</b><br><span style="font-size:.7rem;color:var(--muted)">{{ Str::limit($item->excerpt, 60) }}</span></td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->category?->name ?? '—' }}</td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->published_at?->format('j M Y') ?? '—' }}</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
