@extends('admin.layout')
@section('title', 'Settings')
@section('crumb', 'Global values used across the whole site')

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>Groups</h2>
    </div>
    <div style="display:flex;gap:.4rem;flex-wrap:wrap">
      @foreach($groups as $group => $rows)
        <a class="btn btn--sm {{ $active === $group ? 'btn--primary' : '' }}"
           href="{{ route('admin.settings.index') }}?group={{ $group }}">
          {{ ucfirst($group) }} <span style="opacity:.6">{{ $rows->count() }}</span>
        </a>
      @endforeach
    </div>
  </div>

  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
      <div class="card__head">
        <h2>{{ ucfirst($active) }}</h2>
        <button class="btn btn--primary" type="submit">Save settings</button>
      </div>

      @foreach($groups[$active] ?? [] as $setting)
        <div class="field">
          <label for="set_{{ $setting->key }}">
            {{ $setting->label ?: $setting->key }}
            <span style="color:var(--muted);font-weight:400"> · {{ $setting->key }}</span>
          </label>

          @if($setting->type === 'textarea' || $setting->type === 'json')
            <textarea class="ctrl {{ $setting->type === 'json' ? 'ctrl--tall' : '' }}"
                      id="set_{{ $setting->key }}" name="settings[{{ $setting->key }}]">{{ $setting->value }}</textarea>
          @elseif($setting->type === 'bool')
            <label class="switch">
              <input type="checkbox" name="settings[{{ $setting->key }}]" value="1" @checked($setting->value)><i></i>
              Enabled
            </label>
          @elseif($setting->type === 'image')
            @if($setting->value)
              <div class="preview"><img src="{{ asset($setting->value) }}" alt="" onerror="this.style.visibility='hidden'"></div>
            @endif
            <input class="ctrl" type="text" id="set_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
            <input class="ctrl" type="file" name="files[{{ $setting->key }}]" accept="image/*" style="margin-top:.4rem;padding:.4rem">
          @elseif($setting->type === 'number')
            <input class="ctrl" type="number" id="set_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
          @else
            <input class="ctrl" type="text" id="set_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
          @endif

          @if($setting->hint)<p class="field__hint">{{ $setting->hint }}</p>@endif
        </div>
      @endforeach

      <button class="btn btn--primary" type="submit">Save settings</button>
    </div>
  </form>

  <div class="card">
    <div class="card__head"><h2>Add a setting</h2></div>
    <form method="POST" action="{{ route('admin.settings.store') }}">
      @csrf
      <div class="row">
        <div class="field">
          <label for="new_group">Group</label>
          <input class="ctrl" type="text" id="new_group" name="group" value="{{ $active }}" required>
        </div>
        <div class="field">
          <label for="new_key">Key</label>
          <input class="ctrl" type="text" id="new_key" name="key" required placeholder="my_new_value">
        </div>
        <div class="field">
          <label for="new_label">Label</label>
          <input class="ctrl" type="text" id="new_label" name="label" required>
        </div>
        <div class="field">
          <label for="new_type">Type</label>
          <select class="ctrl" id="new_type" name="type">
            @foreach(['text', 'textarea', 'image', 'bool', 'number', 'json'] as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <button class="btn" type="submit">Add setting</button>
    </form>
  </div>
@endsection
