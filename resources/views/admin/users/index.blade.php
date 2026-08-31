@extends('admin.layout')
@section('title', 'Users')
@section('crumb', 'Who can sign in to this panel')

@section('actions')
  <a class="btn btn--primary" href="{{ route('admin.users.create') }}">Add user</a>
@endsection

@section('content')
  <div class="card">
    <div class="card__head"><h2>{{ $items->total() }} account(s)</h2></div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Added</th><th></th></tr></thead>
        <tbody>
          @foreach($items as $u)
            <tr>
              <td><b style="font-size:.82rem">{{ $u->name }}</b></td>
              <td style="font-size:.78rem;color:var(--muted)">{{ $u->email }}</td>
              <td><span class="pill {{ $u->role === 'owner' ? 'pill--warn' : 'pill--off' }}">{{ $u->role }}</span></td>
              <td style="font-size:.72rem;color:var(--muted)">{{ $u->created_at?->format('j M Y') }}</td>
              <td class="actions">
                <a class="btn btn--sm" href="{{ route('admin.users.edit', $u) }}">Edit</a>
                @if($u->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Delete this user?">
                    @csrf @method('DELETE')
                    <button class="btn btn--sm btn--danger" type="submit">Delete</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="pager">{{ $items->links() }}</div>
  </div>
@endsection
