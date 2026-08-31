{{-- Row actions shared by every index table. --}}
<a class="btn btn--sm" href="{{ route($route . '.edit', $item) }}">Edit</a>

<form method="POST" action="{{ route($route . '.duplicate', $item) }}">
  @csrf
  <button class="btn btn--sm" type="submit">Duplicate</button>
</form>

@if(isset($item->is_published))
  <form method="POST" action="{{ route($route . '.toggle', $item) }}">
    @csrf
    <button class="btn btn--sm" type="submit">{{ $item->is_published ? 'Unpublish' : 'Publish' }}</button>
  </form>
@endif

<form method="POST" action="{{ route($route . '.destroy', $item) }}"
      data-confirm="Delete this permanently? This cannot be undone.">
  @csrf @method('DELETE')
  <button class="btn btn--sm btn--danger" type="submit">Delete</button>
</form>
