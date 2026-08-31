@extends('admin.layout')
@section('title', 'Enquiries')
@section('crumb', 'Everything submitted through the site')

@section('actions')
  <a class="btn" href="{{ route('admin.enquiries.export') }}">Export CSV</a>
@endsection

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>{{ $items->total() }} total</h2>
      @if($unread)<span class="pill pill--warn">{{ $unread }} unread</span>@endif
      <a class="btn btn--sm {{ $unreadOnly ? 'btn--primary' : '' }}"
         href="{{ route('admin.enquiries.index') }}{{ $unreadOnly ? '' : '?unread=1' }}">
        {{ $unreadOnly ? 'Show all' : 'Unread only' }}
      </a>
      <form class="search" method="GET" style="margin-left:auto">
        <input class="ctrl" type="search" name="q" value="{{ $term }}" placeholder="Search name, email, message…">
        <button class="btn btn--sm" type="submit">Search</button>
      </form>
    </div>

    @if($items->isEmpty())
      <div class="empty">
        <h3>No enquiries yet</h3>
        <p>Submissions from the contact form and homepage arrive here.</p>
      </div>
    @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th></th><th>Name</th><th>Service</th><th>Budget</th><th>Received</th><th></th></tr>
          </thead>
          <tbody>
            @foreach($items as $e)
              <tr>
                <td>@if(! $e->is_read)<span class="pill pill--warn">New</span>@endif</td>
                <td>
                  <b style="font-size:.82rem">{{ $e->name }}</b><br>
                  <span style="font-size:.72rem;color:var(--muted)">{{ $e->email }}</span>
                </td>
                <td style="font-size:.75rem;color:var(--muted)">{{ $e->service ?: '—' }}</td>
                <td style="font-size:.75rem;color:var(--muted)">{{ $e->budget ?: '—' }}</td>
                <td style="font-size:.72rem;color:var(--muted)">{{ $e->created_at->diffForHumans() }}</td>
                <td class="actions">
                  <a class="btn btn--sm" href="{{ route('admin.enquiries.show', $e) }}">Open</a>
                  <form method="POST" action="{{ route('admin.enquiries.destroy', $e) }}" data-confirm="Delete this enquiry?">
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
