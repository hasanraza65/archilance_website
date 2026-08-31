@extends('admin.layout')
@section('title', $mode === 'create' ? 'New service' : 'Edit service')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.services.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.services.store') : route('admin.services.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Basics</h2></div>
          <div class="row">
            <div class="field">
              <label for="name">Service name</label>
              <input class="ctrl" type="text" id="name" name="name" data-slug-source
                     value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="field">
              <label for="short_name">Short name</label>
              <input class="ctrl" type="text" id="short_name" name="short_name"
                     value="{{ old('short_name', $item->short_name) }}">
            </div>
          </div>
          <div class="row">
            <div class="field">
              <label for="slug">URL slug</label>
              <input class="ctrl" type="text" id="slug" name="slug" data-slug-target
                     value="{{ old('slug', $item->slug) }}">
            </div>
            <div class="field">
              <label for="icon">Icon (sprite id)</label>
              <input class="ctrl" type="text" id="icon" name="icon"
                     value="{{ old('icon', $item->icon) }}" placeholder="i-svc-revit">
            </div>
          </div>
          <div class="row">
            <div class="field">
              <label for="h1_lead">Headline — plain part</label>
              <input class="ctrl" type="text" id="h1_lead" name="h1_lead" value="{{ old('h1_lead', $item->h1_lead) }}">
            </div>
            <div class="field">
              <label for="h1_gold">Headline — highlighted part</label>
              <input class="ctrl" type="text" id="h1_gold" name="h1_gold" value="{{ old('h1_gold', $item->h1_gold) }}">
            </div>
          </div>
          <div class="field">
            <label for="lede">Intro line</label>
            <textarea class="ctrl" id="lede" name="lede">{{ old('lede', $item->lede) }}</textarea>
          </div>
          <div class="row">
            <div class="field">
              <label for="portfolio_filter">Portfolio filter key</label>
              <input class="ctrl" type="text" id="portfolio_filter" name="portfolio_filter"
                     value="{{ old('portfolio_filter', $item->portfolio_filter) }}" placeholder="design, bim, render…">
            </div>
            <div class="field">
              <label for="image_alt">Image alt text</label>
              <input class="ctrl" type="text" id="image_alt" name="image_alt" value="{{ old('image_alt', $item->image_alt) }}">
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__head"><h2>Page content</h2></div>
          @include('admin._partials.repeat', ['name' => 'intro', 'label' => 'Intro paragraphs',
            'fields' => [['key' => '_', 'label' => 'Paragraph', 'type' => 'textarea']]])
          @include('admin._partials.repeat', ['name' => 'stats', 'label' => 'Hero stats',
            'fields' => [['key' => 'value', 'label' => 'Value', 'type' => 'text'], ['key' => 'label', 'label' => 'Label', 'type' => 'text']]])
          @include('admin._partials.repeat', ['name' => 'deliverables', 'label' => 'What is included',
            'fields' => [['key' => 'title', 'label' => 'Title', 'type' => 'text'], ['key' => 'text', 'label' => 'Description', 'type' => 'textarea']]])
          @include('admin._partials.repeat', ['name' => 'process', 'label' => 'Process steps',
            'fields' => [['key' => 'title', 'label' => 'Title', 'type' => 'text'], ['key' => 'text', 'label' => 'Description', 'type' => 'textarea']]])
          @include('admin._partials.repeat', ['name' => 'tools', 'label' => 'Software list',
            'fields' => [['key' => '_', 'label' => 'Tool', 'type' => 'text']]])
          @include('admin._partials.repeat', ['name' => 'faqs', 'label' => 'Service FAQs',
            'fields' => [['key' => 'q', 'label' => 'Question', 'type' => 'text'], ['key' => 'a', 'label' => 'Answer', 'type' => 'textarea']]])
          @include('admin._partials.repeat', ['name' => 'related', 'label' => 'Related service slugs',
            'fields' => [['key' => '_', 'label' => 'Slug', 'type' => 'text']]])
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
          @include('admin._partials.image-field', ['name' => 'hero_image', 'label' => 'Hero image (1600px)'])
          @include('admin._partials.image-field', ['name' => 'hero_image_sm', 'label' => 'Hero image (900px)'])
          @include('admin._partials.image-field', ['name' => 'card_image', 'label' => 'Card image'])
        </div>

      </div>
    </div>
  </form>
@endsection
