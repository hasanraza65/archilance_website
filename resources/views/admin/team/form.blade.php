@extends('admin.layout')
@section('title', $mode === 'create' ? 'New team member' : 'Edit team member')
@section('crumb', $mode === 'edit' ? ($item->name ?? '') : '')

@section('actions')
  @if($mode === 'edit' && $item->slug && $item->is_published && $item->has_profile)
    <a class="btn" href="{{ route('team.show', $item) }}" target="_blank" rel="noopener">View profile page</a>
  @endif
  <a class="btn" href="{{ route('admin.team.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST" enctype="multipart/form-data"
        action="{{ $mode === 'create' ? route('admin.team.store') : route('admin.team.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>

        {{-- ---------------------------------------------------- identity --}}
        <div class="card">
          <div class="card__head"><h2>Person</h2></div>
          <div class="row">
            <div class="field">
              <label for="name">Full name</label>
              <input class="ctrl" type="text" id="name" name="name" data-slug-source value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="field">
              <label for="role">Role</label>
              <input class="ctrl" type="text" id="role" name="role" value="{{ old('role', $item->role) }}">
            </div>
          </div>
          <div class="row">
            <div class="field">
              <label for="slug">Slug</label>
              <input class="ctrl" type="text" id="slug" name="slug" data-slug-target value="{{ old('slug', $item->slug) }}">
              <p class="field__hint">The profile lives at <code>/team/&lt;slug&gt;</code>.</p>
            </div>
            <div class="field">
              <label for="team">Team / colour group</label>
              <select class="ctrl" id="team" name="team">
                @foreach($teams as $key => $label)
                  <option value="{{ $key }}" @selected(old('team', $item->team) === $key)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="field">
            <label for="blurb">What they do — short</label>
            <textarea class="ctrl" id="blurb" name="blurb">{{ old('blurb', $item->blurb) }}</textarea>
            <p class="field__hint">One or two lines. Used by the org chart and its hover card.</p>
          </div>
          <div class="field">
            <label for="extra">Education / extra note</label>
            <textarea class="ctrl" id="extra" name="extra">{{ old('extra', $item->extra) }}</textarea>
            <p class="field__hint">Shown on the profile only when the Education rows below are empty.</p>
          </div>
        </div>

        {{-- ------------------------------------------------ profile page --}}
        <div class="card">
          <div class="card__head"><h2>Profile page</h2></div>

          <div class="field">
            <label for="headline">Headline</label>
            <input class="ctrl" type="text" id="headline" name="headline" data-counter="150"
                   value="{{ old('headline', $item->headline) }}"
                   placeholder="The lead sentence under their name">
            <p class="field__hint">Falls back to the short blurb when empty.</p>
          </div>

          <div class="field">
            <label for="bio">Biography</label>
            <textarea class="ctrl" id="bio" name="bio" rows="7">{{ old('bio', $item->bio) }}</textarea>
            <p class="field__hint">Leave a blank line between paragraphs. Falls back to the short blurb.</p>
          </div>

          <div class="row">
            <div class="field">
              <label for="quote">Pull quote</label>
              <input class="ctrl" type="text" id="quote" name="quote" value="{{ old('quote', $item->quote) }}"
                     placeholder="Something in their own words">
            </div>
            <div class="field">
              <label for="location">Location</label>
              <input class="ctrl" type="text" id="location" name="location" value="{{ old('location', $item->location) }}"
                     placeholder="Islamabad, Pakistan">
            </div>
          </div>

          @include('admin._partials.repeat', [
            'name' => 'focus',
            'label' => 'Responsibilities',
            'fields' => [
              ['key' => 'title', 'label' => 'What they own', 'type' => 'text'],
              ['key' => 'text', 'label' => 'Detail', 'type' => 'textarea'],
            ],
          ])

          @include('admin._partials.repeat', [
            'name' => 'expertise',
            'label' => 'Software & expertise',
            'fields' => [
              ['key' => 'name', 'label' => 'Tool or skill', 'type' => 'text'],
            ],
          ])

          @include('admin._partials.repeat', [
            'name' => 'highlights',
            'label' => 'Stat tiles',
            'fields' => [
              ['key' => 'value', 'label' => 'Value', 'type' => 'text'],
              ['key' => 'label', 'label' => 'Label', 'type' => 'text'],
            ],
          ])

          @include('admin._partials.repeat', [
            'name' => 'education',
            'label' => 'Education',
            'fields' => [
              ['key' => 'degree', 'label' => 'Qualification', 'type' => 'text'],
              ['key' => 'school', 'label' => 'Institution', 'type' => 'text'],
              ['key' => 'year', 'label' => 'Year', 'type' => 'text'],
            ],
          ])
        </div>

        {{-- ---------------------------------------------------- contact --}}
        <div class="card">
          <div class="card__head"><h2>Contact &amp; links</h2></div>
          <div class="row">
            <div class="field">
              <label for="email">Email</label>
              <input class="ctrl" type="email" id="email" name="email" value="{{ old('email', $item->email) }}">
            </div>
            <div class="field">
              <label for="linkedin">LinkedIn URL</label>
              <input class="ctrl" type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $item->linkedin) }}"
                     placeholder="https://www.linkedin.com/in/...">
            </div>
          </div>
          <div class="field">
            <label for="behance">Behance URL</label>
            <input class="ctrl" type="url" id="behance" name="behance" value="{{ old('behance', $item->behance) }}"
                   placeholder="https://www.behance.net/...">
          </div>
        </div>

        @include('admin._partials.seo-fields')
      </div>

      <div>
        {{-- ------------------------------------------------- publishing --}}
        <div class="card">
          <div class="card__head"><h2>Chart position</h2></div>
          <div class="field">
            <label for="parent_id">Reports to</label>
            <select class="ctrl" id="parent_id" name="parent_id">
              <option value="">Nobody (top of a branch)</option>
              @foreach($parents as $p)
                @continue($p->id === $item->id)
                <option value="{{ $p->id }}" @selected(old('parent_id', $item->parent_id) == $p->id)>{{ $p->name }} — {{ $p->role }}</option>
              @endforeach
            </select>
          </div>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_leadership" value="1" @checked(old('is_leadership', $item->is_leadership))><i></i>
            Show in the leadership band
          </label>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true))><i></i>
            Published
          </label>
          <label class="switch" style="margin-bottom:.9rem">
            <input type="checkbox" name="has_profile" value="1" @checked(old('has_profile', $item->has_profile ?? true))><i></i>
            Give them a profile page
          </label>
          <p class="field__hint" style="margin:-.5rem 0 .9rem">
            Turn off for a department or placeholder card — the chart still shows it, but it
            links nowhere and stays out of the sitemap.
          </p>
          <div class="field">
            <label for="sort">Order</label>
            <input class="ctrl" type="number" id="sort" name="sort" value="{{ old('sort', $item->sort ?? 0) }}">
          </div>
          <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">
            {{ $mode === 'create' ? 'Create' : 'Save changes' }}
          </button>
        </div>

        {{-- ---------------------------------------------------- imagery --}}
        <div class="card">
          <div class="card__head"><h2>Photos</h2></div>
          @include('admin._partials.image-field', ['name' => 'photo', 'label' => 'Portrait (chart + cards)'])
          @include('admin._partials.image-field', ['name' => 'photo_full', 'label' => 'Large portrait (profile page)'])
          <p class="field__hint">The large one is optional — the chart portrait is used when it is blank.</p>
          <div class="field">
            <label for="image_alt">Photo alt text</label>
            <input class="ctrl" type="text" id="image_alt" name="image_alt" value="{{ old('image_alt', $item->image_alt) }}"
                   placeholder="Defaults to “Name — Role”">
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
