<td>@if($item->card_image)<img class="thumb" src="{{ asset($item->card_image) }}" alt="">@endif</td>
<td><b>{{ $item->title }}</b><br><span style="font-size:.7rem;color:var(--muted)">{{ Str::limit($item->description, 60) }}</span></td>
<td style="font-size:.72rem;color:var(--muted)">{{ $item->categories }}</td>
<td><span class="pill {{ $item->is_published ? 'pill--on' : 'pill--off' }}">{{ $item->is_published ? 'Live' : 'Draft' }}</span></td>
