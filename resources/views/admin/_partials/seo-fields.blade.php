{{-- The SEO block every content type shares. --}}
<div class="card">
  <div class="card__head"><h2>Search engine listing</h2></div>

  <div class="field">
    <label for="meta_title">Meta title</label>
    <input class="ctrl" type="text" id="meta_title" name="meta_title"
           value="{{ old('meta_title', $item->meta_title) }}" data-counter="60"
           placeholder="Shown as the clickable headline in Google">
    <p class="field__hint">Aim for 50–60 characters. Leave blank to fall back to the item name.</p>
  </div>

  <div class="field">
    <label for="meta_description">Meta description</label>
    <textarea class="ctrl" id="meta_description" name="meta_description" data-counter="160"
              placeholder="The grey summary underneath the link">{{ old('meta_description', $item->meta_description) }}</textarea>
    <p class="field__hint">Aim for 120–160 characters. Write it for a human, not a crawler.</p>
  </div>

  <div class="field">
    <label for="meta_keywords">Keywords</label>
    <input class="ctrl" type="text" id="meta_keywords" name="meta_keywords"
           value="{{ old('meta_keywords', $item->meta_keywords) }}"
           placeholder="comma, separated, phrases">
  </div>

  <div class="row">
    <div class="field">
      <label for="canonical">Canonical URL</label>
      <input class="ctrl" type="text" id="canonical" name="canonical"
             value="{{ old('canonical', $item->canonical) }}" placeholder="Leave blank to use this page's own URL">
    </div>
    <div class="field">
      <label for="og_image">Share image path</label>
      <input class="ctrl" type="text" id="og_image" name="og_image"
             value="{{ old('og_image', $item->og_image) }}" placeholder="assets/img/...">
    </div>
  </div>

  <label class="switch">
    <input type="checkbox" name="noindex" value="1" @checked(old('noindex', $item->noindex))><i></i>
    Hide from search engines (noindex)
  </label>
</div>
