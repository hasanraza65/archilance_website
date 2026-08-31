@extends('admin.layout')
@section('title', $mode === 'create' ? 'New faq' : 'Edit faq')
@section('crumb', $mode === 'edit' ? ($item->name ?? $item->title ?? $item->question ?? '') : '')

@section('actions')
  <a class="btn" href="{{ route('admin.faqs.index') }}">Back to list</a>
@endsection

@section('content')
  <form method="POST"
        action="{{ $mode === 'create' ? route('admin.faqs.store') : route('admin.faqs.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="grid grid--form">
      <div>
        <div class="card">
          <div class="card__head"><h2>Question</h2></div>
          <div class="field">
            <label for="question">Question</label>
            <input class="ctrl" type="text" id="question" name="question" value="{{ old('question', $item->question) }}" required>
          </div>
          <div class="field">
            <label for="answer">Answer</label>
            <textarea class="ctrl ctrl--tall" id="answer" name="answer" required>{{ old('answer', $item->answer) }}</textarea>
            <p class="field__hint">Plain text or simple HTML. This also feeds the FAQ schema Google reads.</p>
          </div>
        </div>

      </div>

      <div>
        <div class="card">
          <div class="card__head"><h2>Placement</h2></div>
          <div class="field">
            <label for="faq_category_id">Category</label>
            <select class="ctrl" id="faq_category_id" name="faq_category_id">
              <option value="">Uncategorised</option>
              @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('faq_category_id', $item->faq_category_id) == $c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
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

      </div>
    </div>
  </form>
@endsection
