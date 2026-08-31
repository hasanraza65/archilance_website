@extends('admin.layout')
@section('title', $mode === 'create' ? 'New plan' : 'Edit plan')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.plans.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $mode === 'create' ? route('admin.plans.store') : route('admin.plans.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Plan</h2></div>
          <div class="row">
            <div class="field">
              <label for="name">Name</label>
              <input class="ctrl" type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="field">
              <label for="price">Price</label>
              <input class="ctrl" type="text" id="price" name="price" value="{{ old('price', $item->price) }}" required placeholder="1,895">
            </div>
          </div>
          <div class="row">
            <div class="field">
              <label for="period">Period label</label>
              <input class="ctrl" type="text" id="period" name="period" value="{{ old('period', $item->period) }}" placeholder="/mo">
            </div>
            <div class="field">
              <label for="hours">Hours line</label>
              <input class="ctrl" type="text" id="hours" name="hours" value="{{ old('hours', $item->hours) }}" placeholder="160 hours">
            </div>
          </div>
          <div class="field">
            <label for="rate_note">Rate note</label>
            <input class="ctrl" type="text" id="rate_note" name="rate_note" value="{{ old('rate_note', $item->rate_note) }}" placeholder="$11.84/hr | 40 hrs /w">
          </div>
          <div class="row">
            <div class="field">
              <label for="cta_label">Button label</label>
              <input class="ctrl" type="text" id="cta_label" name="cta_label" value="{{ old('cta_label', $item->cta_label) }}">
            </div>
            <div class="field">
              <label for="cta_url">Button URL</label>
              <input class="ctrl" type="text" id="cta_url" name="cta_url" value="{{ old('cta_url', $item->cta_url) }}">
            </div>
          </div>
          @include('admin._partials.repeat', ['name' => 'features', 'label' => 'Features',
            'fields' => [['key' => '_', 'label' => 'Feature', 'type' => 'text']]])
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
          <label class="switch">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $item->featured))><i></i>
            Highlight as most popular
          </label>
        </div>

      </div>
    </div>
  </form>
@endsection
