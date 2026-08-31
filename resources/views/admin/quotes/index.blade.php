@extends('admin.layout')
@section('title', 'Quote requests')
@section('crumb', 'Estimates built with the homepage wizard')

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>{{ $items->total() }} total</h2>
      @if($unread)<span class="pill pill--warn">{{ $unread }} new</span>@endif
      <form class="search" method="GET" style="margin-left:auto">
        <input class="ctrl" type="search" name="q" value="{{ $term }}" placeholder="Search name or email…">
        <button class="btn btn--sm" type="submit">Search</button>
      </form>
    </div>

    @if($items->isEmpty())
      <div class="empty">
        <h3>No quotes yet</h3>
        <p>When someone finishes the quote builder on the homepage, their answers and the figure they were shown land here.</p>
      </div>
    @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Who</th><th>Wants</th><th>Estimate</th><th>Plan</th><th>Received</th><th></th></tr>
          </thead>
          <tbody>
            @foreach($items as $q)
              <tr>
                <td>
                  @if(! $q->is_read)<span class="dot-new" aria-label="Unread"></span>@endif
                  <b style="font-size:.82rem">{{ $q->name }}</b><br>
                  <span style="font-size:.72rem;color:var(--muted)">{{ $q->email }}</span>
                </td>
                <td style="font-size:.74rem;color:var(--muted)">
                  {{ $q->project_type ?: '—' }}
                  @if($q->services)<br><span style="font-size:.7rem">{{ count($q->services) }} service{{ count($q->services) === 1 ? '' : 's' }}</span>@endif
                </td>
                <td style="font-size:.78rem;color:var(--gold-200);white-space:nowrap">
                  ${{ number_format($q->price_low) }} – ${{ number_format($q->price_high) }}
                </td>
                <td style="font-size:.74rem;color:var(--muted)">{{ $q->recommended_plan }}</td>
                <td style="font-size:.72rem;color:var(--muted)">{{ $q->created_at->diffForHumans() }}</td>
                <td class="actions">
                  <a class="btn btn--sm" href="{{ route('admin.quotes.show', $q) }}">Open</a>
                  <form method="POST" action="{{ route('admin.quotes.destroy', $q) }}" data-confirm="Delete this quote?">
                    @csrf @method('DELETE')
                    <button class="btn btn--sm btn--danger" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="pager">{{ $items->links() }}</div>
    @endif
  </div>
@endsection
