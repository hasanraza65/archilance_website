<td><b>{{ Str::limit($item->question, 80) }}</b></td>
<td style="font-size:.75rem;color:var(--muted)">{{ $item->category?->name ?? '—' }}</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
