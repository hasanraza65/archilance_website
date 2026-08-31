<td><b>{{ $item->name }}</b><br><span style="font-size:.7rem;color:var(--muted)">{{ Str::limit($item->lede, 70) }}</span></td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->slug }}</td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->icon }}</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
