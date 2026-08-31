@extends('admin.layout')
@section('title', 'Team')

@section('actions')
  <a class="btn btn--primary" href="{{ route('admin.team.create') }}">Add new</a>
@endsection

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>{{ $items->total() }} team</h2>
      <span id="sortNote" style="font-size:.72rem;color:var(--ok)"></span>
      <form class="search" method="GET" style="margin-left:auto">
        <input class="ctrl" type="search" name="q" value="{{ $term }}" placeholder="Search…">
        <button class="btn btn--sm" type="submit">Search</button>
        @if($term)<a class="btn btn--sm" href="{{ route('admin.team.index') }}">Clear</a>@endif
      </form>
    </div>

    @if($items->isEmpty())
      <div class="empty">
        <h3>Nothing here yet</h3>
        <p>Create the first entry and it will appear on the site straight away.</p>
        <a class="btn btn--primary" href="{{ route('admin.team.create') }}">Add new</a>
      </div>
    @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              @if($sortable)<th style="width:34px"></th>@endif
              <th style="width:48px">Photo</th>
              <th>Name</th>
              <th>Role</th>
              <th>Group</th>
              <th>Reports to</th>
              <th>Status</th>
              <th style="width:1%"></th>
            </tr>
          </thead>
          <tbody @if($sortable) data-sortable="{{ route('admin.team.reorder') }}" @endif>
            @foreach($items as $item)
              <tr data-id="{{ $item->id }}">
                @if($sortable)<td class="drag">&#8942;&#8942;</td>@endif
                @include('admin/team._row')
                <td class="actions">@include('admin._partials.actions', ['item' => $item, 'route' => 'admin.team'])</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="pager">{{ $items->links() }}</div>
    @endif
  </div>

@endsection
