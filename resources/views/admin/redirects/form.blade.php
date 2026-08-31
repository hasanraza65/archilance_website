@extends('admin.layout')
@section('title', $mode === 'create' ? 'New redirect' : 'Edit redirect')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.redirects.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $mode === 'create' ? route('admin.redirects.store') : route('admin.redirects.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Redirect</h2></div>
          <div class="field">
            <label for="from">From path</label>
            <input class="ctrl" type="text" id="from" name="from" value="{{ old('from', $item->from) }}" required placeholder="/old-page">
          </div>
          <div class="field">
            <label for="to">To path or URL</label>
            <input class="ctrl" type="text" id="to" name="to" value="{{ old('to', $item->to) }}" required placeholder="/services/revit-drafting">
          </div>
          <div class="field">
            <label for="status">Status code</label>
            <select class="ctrl" id="status" name="status">
              @foreach([301 => '301 — permanent', 302 => '302 — temporary', 307 => '307 — temporary (keep method)', 308 => '308 — permanent (keep method)'] as $code => $label)
                <option value="{{ $code }}" @selected(old('status', $item->status ?: 301) == $code)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>

      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Save</h2></div>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))><i></i>
            Active
          </label>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create' : 'Save changes' }}
          </button>
        </div>

      </div>
    </div>
  </form>
@endsection
