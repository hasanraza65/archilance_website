@extends('admin.layout')
@section('title', $mode === 'create' ? 'New testimonial' : 'Edit testimonial')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.testimonials.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.testimonials.store') : route('admin.testimonials.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Quote</h2></div>
          <div class="row">
            <div class="field">
              <label for="name">Client name</label>
              <input class="ctrl" type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="field">
              <label for="company">Company</label>
              <input class="ctrl" type="text" id="company" name="company" value="{{ old('company', $item->company) }}">
            </div>
          </div>
          <div class="row">
            <div class="field">
              <label for="platform">Platform</label>
              <input class="ctrl" type="text" id="platform" name="platform" value="{{ old('platform', $item->platform) }}" placeholder="Upwork / LinkedIn">
            </div>
            <div class="field">
              <label for="rating">Rating (1–5)</label>
              <input class="ctrl" type="number" min="1" max="5" id="rating" name="rating" value="{{ old('rating', $item->rating ?: 5) }}">
            </div>
          </div>
          <div class="field">
            <label for="quote">Testimonial</label>
            <textarea class="ctrl ctrl--tall" id="quote" name="quote" required>{{ old('quote', $item->quote) }}</textarea>
          </div>
        </div>

      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Visibility</h2></div>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true))><i></i>
            Published
          </label>
          <div class="field">
            <label for="sort">Order</label>
            <input class="ctrl" type="number" id="sort" name="sort" value="{{ old('sort', $item->sort ?? 0) }}">
            <p class="field__hint">Lower numbers appear first. You can also drag rows on the list.</p>
          </div>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create' : 'Save changes' }}
          </button>
        </div>
        <div class="card">
          <div class="card__head"><h2>Avatar</h2></div>
          @include('admin._partials.image-field', ['name' => 'avatar', 'label' => 'Portrait'])
        </div>

      </div>
    </div>
  </form>
@endsection
