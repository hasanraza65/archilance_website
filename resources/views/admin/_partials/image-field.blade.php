{{-- $name, $label, optional $value. Text path + upload + picker from bundled assets. --}}
@php $val = old($name, $item->{$name} ?? ''); @endphp
<div class="field">
  <label for="{{ $name }}">{{ $label }}</label>

  <div class="preview">
    <img id="{{ $name }}_preview" src="{{ $val ? asset($val) : '' }}" alt=""
         onerror="this.style.visibility='hidden'">
    <input class="ctrl" type="text" id="{{ $name }}_path" name="{{ $name }}_path"
           value="{{ $val }}" placeholder="assets/img/... or upload below">
  </div>

  <input class="ctrl" type="file" name="{{ $name }}" accept="image/*" style="padding:.4rem">

  @isset($bundled)
    <details style="margin-top:.5rem">
      <summary style="cursor:pointer;font-size:.74rem;color:var(--muted)">Choose from existing images</summary>
      <div class="picker" data-picker="{{ $name }}_path" style="margin-top:.5rem">
        @foreach($bundled as $group => $paths)
          @foreach($paths as $p)
            <button type="button" data-path="{{ $p }}" title="{{ $p }}">
              <img src="{{ asset($p) }}" alt="" loading="lazy">
            </button>
          @endforeach
        @endforeach
      </div>
    </details>
  @endisset
</div>
