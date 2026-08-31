@extends('admin.layout')
@section('title', $mode === 'create' ? 'New faq category' : 'Edit faq category')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.faq-categories.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $mode === 'create' ? route('admin.faq-categories.store') : route('admin.faq-categories.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Details</h2></div>
          <div class="field">
            <label for="name">Name</label>
            <input class="ctrl" type="text" id="name" name="name" data-slug-source value="{{ old('name', $item->name) }}" required>
          </div>
          <div class="field">
            <label for="slug">Slug</label>
            <input class="ctrl" type="text" id="slug" name="slug" data-slug-target value="{{ old('slug', $item->slug) }}">
          </div>
        </div>

      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Save</h2></div>
          <div class="field">
            <label for="sort">Order</label>
            <input class="ctrl" type="number" id="sort" name="sort" value="{{ old('sort', $item->sort ?? 0) }}">
          </div>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create' : 'Save changes' }}
          </button>
        </div>

      </div>
    </div>
  </form>
@endsection
