{{--
  Repeatable JSON block.
    $name   input name, e.g. "deliverables" or "content[coverage.regions]"
    $label  heading shown above the rows
    $fields array of ['key', 'label', 'type']
    $id     optional DOM id — required when $name contains brackets or dots
    $value  optional rows; falls back to the matching model attribute
--}}
@php
    $rid = $id ?? $name;
    $rows = $value ?? ($item->{$name} ?? []);
    $json = is_string($rows) ? $rows : json_encode($rows ?: []);
@endphp
<div class="field">
  <label>{{ $label }}</label>
  <input type="hidden" id="{{ $rid }}_json" name="{{ $name }}" value="{{ $json }}">
  <div data-repeat="{{ $rid }}" data-fields='@json($fields)'></div>
  <button class="btn btn--sm" type="button" data-repeat-add="{{ $rid }}">+ Add {{ Str::singular(strtolower($label)) }}</button>
</div>
