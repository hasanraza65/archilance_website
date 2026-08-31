@extends('admin.layout')
@section('title', $mode === 'create' ? 'New blog post' : 'Edit blog post')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.posts.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.posts.store') : route('admin.posts.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Article</h2></div>
          <div class="field">
            <label for="title">Title</label>
            <input class="ctrl" type="text" id="title" name="title" data-slug-source
                   value="{{ old('title', $item->title) }}" required>
          </div>
          <div class="field">
            <label for="slug">URL slug</label>
            <input class="ctrl" type="text" id="slug" name="slug" data-slug-target value="{{ old('slug', $item->slug) }}">
          </div>
          <div class="field">
            <label for="excerpt">Excerpt</label>
            <textarea class="ctrl" id="excerpt" name="excerpt" data-counter="200">{{ old('excerpt', $item->excerpt) }}</textarea>
          </div>
          <div class="field">
            <label for="body">Body (HTML)</label>
            <textarea class="ctrl ctrl--tall" id="body" name="body">{{ old('body', $item->body) }}</textarea>
            <p class="field__hint">Use &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;b&gt; and &lt;a&gt;. Content is rendered as-is.</p>
          </div>
        </div>

      @include('admin._partials.seo-fields')
      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Publishing</h2></div>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published))><i></i>
            Published
          </label>
          <div class="field">
            <label for="published_at">Publish date</label>
            <input class="ctrl" type="datetime-local" id="published_at" name="published_at"
                   value="{{ old('published_at', $item->published_at?->format('Y-m-d\TH:i')) }}">
            <p class="field__hint">A future date keeps the post hidden until then.</p>
          </div>
          <div class="field">
            <label for="post_category_id">Category</label>
            <select class="ctrl" id="post_category_id" name="post_category_id">
              <option value="">Uncategorised</option>
              @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('post_category_id', $item->post_category_id) == $c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label for="read_minutes">Read time (minutes)</label>
            <input class="ctrl" type="number" id="read_minutes" name="read_minutes" value="{{ old('read_minutes', $item->read_minutes ?: 3) }}">
          </div>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create post' : 'Save changes' }}
          </button>
        </div>

        <div class="card">
          <div class="card__head"><h2>Cover image</h2></div>
          @include('admin._partials.image-field', ['name' => 'cover_image', 'label' => 'Cover'])
          <div class="field">
            <label for="image_alt">Alt text</label>
            <input class="ctrl" type="text" id="image_alt" name="image_alt" value="{{ old('image_alt', $item->image_alt) }}">
          </div>
        </div>

      </div>
    </div>
  </form>
@endsection
