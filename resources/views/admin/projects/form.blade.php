@extends('admin.layout')
@section('title', $mode === 'create' ? 'New project' : 'Edit project')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.projects.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.projects.store') : route('admin.projects.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Basics</h2></div>
          <div class="field">
            <label for="title">Project title</label>
            <input class="ctrl" type="text" id="title" name="title" data-slug-source
                   value="{{ old('title', $item->title) }}" required>
          </div>
          <div class="row">
            <div class="field">
              <label for="slug">URL slug</label>
              <input class="ctrl" type="text" id="slug" name="slug" data-slug-target value="{{ old('slug', $item->slug) }}">
            </div>
            <div class="field">
              <label for="category_label">Category label (shown on card)</label>
              <input class="ctrl" type="text" id="category_label" name="category_label"
                     value="{{ old('category_label', $item->category_label) }}">
            </div>
          </div>
          <div class="field">
            <label for="description">Description <span class="field__hint">— the one line on the card</span></label>
            <textarea class="ctrl" id="description" name="description">{{ old('description', $item->description) }}</textarea>
          </div>

          {{-- Everything below drives the project's own page. --}}
          <div class="field">
            <label for="summary">Summary <span class="field__hint">— the lede under the title on its page</span></label>
            <textarea class="ctrl" id="summary" name="summary">{{ old('summary', $item->summary) }}</textarea>
          </div>
          <div class="field">
            <label for="body">Write-up <span class="field__hint">— HTML; wrap paragraphs in &lt;p&gt;</span></label>
            <textarea class="ctrl" id="body" name="body" rows="8">{{ old('body', $item->body) }}</textarea>
          </div>

          @include('admin._partials.repeat', [
            'name' => 'facts', 'id' => 'facts', 'label' => 'At-a-glance facts',
            'fields' => [
              ['key' => 'label', 'label' => 'Label', 'type' => 'text'],
              ['key' => 'value', 'label' => 'Value', 'type' => 'text'],
            ],
          ])

          @include('admin._partials.repeat', [
            'name' => 'deliverables', 'id' => 'deliverables', 'label' => 'What was delivered',
            'fields' => [['key' => '_', 'label' => 'Item', 'type' => 'text']],
          ])

          @include('admin._partials.repeat', [
            'name' => 'gallery', 'id' => 'gallery', 'label' => 'Gallery images',
            'fields' => [
              ['key' => 'image', 'label' => 'Path (e.g. assets/img/portfolio/x.webp)', 'type' => 'text'],
              ['key' => 'alt', 'label' => 'Alt text', 'type' => 'text'],
              ['key' => 'caption', 'label' => 'Caption', 'type' => 'text'],
            ],
          ])
          <div class="field">
            <label for="categories">Filter keys</label>
            <input class="ctrl" type="text" id="categories" name="categories"
                   value="{{ old('categories', $item->categories) }}" placeholder="design render modeling">
            <p class="field__hint">Space separated. Used by the portfolio filter: design, interior, landscape, bim, render, modeling.</p>
          </div>
          @include('admin._partials.repeat', ['name' => 'tags', 'label' => 'Tags shown on hover',
            'fields' => [['key' => '_', 'label' => 'Tag', 'type' => 'text']]])
        </div>

      @include('admin._partials.seo-fields')
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
          <div class="card__head"><h2>Images</h2></div>
          @include('admin._partials.image-field', ['name' => 'card_image', 'label' => 'Card image'])
          @include('admin._partials.image-field', ['name' => 'full_image', 'label' => 'Full-size image'])
          <div class="field">
            <label for="image_alt">Alt text</label>
            <input class="ctrl" type="text" id="image_alt" name="image_alt" value="{{ old('image_alt', $item->image_alt) }}">
          </div>
          <div class="row">
            <div class="field">
              <label for="card_width">Card width</label>
              <input class="ctrl" type="number" id="card_width" name="card_width" value="{{ old('card_width', $item->card_width) }}">
            </div>
            <div class="field">
              <label for="card_height">Card height</label>
              <input class="ctrl" type="number" id="card_height" name="card_height" value="{{ old('card_height', $item->card_height) }}">
            </div>
          </div>
          <div class="field">
            <label for="span">Grid span</label>
            <select class="ctrl" id="span" name="span">
              <option value="">Normal</option>
              <option value="wide" @selected(old('span', $item->span) === 'wide')>Wide (2 columns)</option>
              <option value="tall" @selected(old('span', $item->span) === 'tall')>Tall (2 rows)</option>
            </select>
          </div>
          <label class="switch">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $item->featured))><i></i> Featured
          </label>
        </div>

      </div>
    </div>
  </form>
@endsection
