@extends('admin.layout')
@section('title', $mode === 'create' ? 'New page' : 'Edit: ' . $item->title)
@section('crumb', $mode === 'edit' ? '/' . $item->slug : '')

@section('actions')
  @if($mode === 'edit' && $preview)
    <a class="btn" href="{{ $preview }}" target="_blank" rel="noopener">View page &nearr;</a>
  @endif
  <a class="btn" href="{{ route('admin.pages.index') }}">All pages</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.pages.store') : route('admin.pages.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    {{-- Sticky bar: these pages are long, so the save button follows you down. --}}
    <div class="savebar">
      <div class="savebar__tabs">
        <a class="savebar__tab" href="#sec-basics">Header</a>
        @foreach($blueprint as $key => $section)
          <a class="savebar__tab" href="#sec-{{ Str::slug($key) }}">{{ $section['label'] }}</a>
        @endforeach
        <a class="savebar__tab" href="#sec-seo">SEO</a>
      </div>
      <button class="btn btn--primary" type="submit">Save page</button>
    </div>

    <div class="grid grid--form">
      <div>
        <div class="card" id="sec-basics">
          <div class="card__head"><h2>Page header</h2></div>

          <div class="row">
            <div class="field">
              <label for="title">Page title</label>
              <input class="ctrl" type="text" id="title" name="title" data-slug-source
                     value="{{ old('title', $item->title) }}" required>
            </div>
            <div class="field">
              <label for="slug">Slug</label>
              <input class="ctrl" type="text" id="slug" name="slug" data-slug-target
                     value="{{ old('slug', $item->slug) }}"
                     @if(in_array($item->slug, $locked, true)) readonly @endif>
              @if(in_array($item->slug, $locked, true))
                <p class="field__hint">Fixed — this page has its own route.</p>
              @endif
            </div>
          </div>

          <div class="field">
            <label for="eyebrow">Eyebrow (small label above the headline)</label>
            <input class="ctrl" type="text" id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $item->eyebrow) }}">
          </div>

          <div class="row">
            <div class="field">
              <label for="h1_lead">Headline — plain part</label>
              <input class="ctrl" type="text" id="h1_lead" name="h1_lead" value="{{ old('h1_lead', $item->h1_lead) }}">
            </div>
            <div class="field">
              <label for="h1_gold">Headline — gold part</label>
              <input class="ctrl" type="text" id="h1_gold" name="h1_gold" value="{{ old('h1_gold', $item->h1_gold) }}">
            </div>
          </div>
          <p class="field__hint" style="margin-top:-.6rem">
            Renders as: <b>{{ $item->h1_lead }}</b>
            <em style="color:var(--gold);font-style:normal">{{ $item->h1_gold }}</em>
          </p>

          <div class="field">
            <label for="lede">Intro paragraph</label>
            <textarea class="ctrl" id="lede" name="lede">{{ old('lede', $item->lede) }}</textarea>
          </div>
        </div>

        @foreach($blueprint as $sectionKey => $section)
          <div class="card" id="sec-{{ Str::slug($sectionKey) }}">
            <div class="card__head"><h2>{{ $section['label'] }}</h2></div>

            @foreach($section['fields'] as $key => $field)
              @php
                $current = old('content.' . $key, $item->text($key));
                $inputName = 'content[' . $key . ']';
                // Str::slug() drops dots and underscores, which collides;
                // this keeps one id per key.
                $fid = 'f_' . preg_replace('/[^a-z0-9]+/i', '-', $key);
              @endphp

              @if(in_array($field['type'], ['repeat', 'list'], true))
                @include('admin._partials.repeat', [
                  'name' => $inputName,
                  'id' => $fid,
                  'label' => $field['label'],
                  'fields' => $field['type'] === 'repeat'
                      ? $field['schema']
                      : [['key' => '_', 'label' => 'Value', 'type' => ($field['rich'] ?? false) ? 'textarea' : 'text']],
                  'value' => is_array($current) ? $current : [],
                ])

              @elseif($field['type'] === 'html')
                <div class="field">
                  <label>{{ $field['label'] }}</label>
                  <div class="rt" data-rt>
                    <div class="rt__bar">
                      <button type="button" data-cmd="bold" title="Bold"><b>B</b></button>
                      <button type="button" data-cmd="italic" title="Italic"><i>I</i></button>
                      <button type="button" data-cmd="gold" title="Gold highlight">Gold</button>
                      <button type="button" data-cmd="createLink" title="Insert link">Link</button>
                      <button type="button" data-cmd="insertUnorderedList" title="Bulleted list">List</button>
                      <button type="button" data-cmd="removeFormat" title="Clear formatting">Clear</button>
                      <button type="button" data-cmd="source" title="Edit raw HTML">HTML</button>
                    </div>
                    <div class="rt__area" contenteditable="true">{!! $current !!}</div>
                    <textarea class="rt__src" name="{{ $inputName }}" hidden>{{ $current }}</textarea>
                  </div>
                </div>

              @elseif($field['type'] === 'textarea')
                <div class="field">
                  <label for="{{ $fid }}">{{ $field['label'] }}</label>
                  <textarea class="ctrl" id="{{ $fid }}" name="{{ $inputName }}">{{ $current }}</textarea>
                </div>

              @else
                <div class="field">
                  <label for="{{ $fid }}">{{ $field['label'] }}</label>
                  <input class="ctrl" type="text" id="{{ $fid }}" name="{{ $inputName }}" value="{{ $current }}">
                </div>
              @endif
            @endforeach
          </div>
        @endforeach

        <div id="sec-seo">
          @include('admin._partials.seo-fields')
        </div>
      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Publish</h2></div>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true))><i></i>
            Published
          </label>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create page' : 'Save changes' }}
          </button>
          @if($mode === 'edit' && $preview)
            <a class="btn" href="{{ $preview }}" target="_blank" rel="noopener"
               style="width:100%;justify-content:center;margin-top:.5rem">Preview</a>
          @endif
        </div>

        <div class="card">
          <div class="card__head"><h2>Hero image</h2></div>
          @include('admin._partials.image-field', ['name' => 'hero_image', 'label' => 'Background'])
        </div>

        <div class="card">
          <div class="card__head"><h2>Tips</h2></div>
          <p style="font-size:.76rem;color:var(--muted);margin:0">
            Leave a field blank to fall back to the original wording. The
            <b>Gold</b> button wraps the selected words in the brand highlight
            used across the site.
          </p>
        </div>
      </div>
    </div>
  </form>
@endsection
