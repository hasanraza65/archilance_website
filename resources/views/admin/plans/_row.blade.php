<td><b>{{ $item->name }}</b>@if($item->featured)<span class="pill pill--warn" style="margin-left:.4rem">Featured</span>@endif</td>
<td>${{ $item->price }} <span style="color:var(--muted);font-size:.72rem">{{ $item->period }}</span></td>
<td style="font-size:.75rem;color:var(--muted)">{{ count($item->features ?? []) }} listed</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
