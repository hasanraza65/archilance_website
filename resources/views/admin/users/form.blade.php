@extends('admin.layout')
@section('title', $mode === 'create' ? 'New user' : 'Edit user')

@section('actions')
  <a class="btn" href="{{ route('admin.users.index') }}">Back</a>
@endsection

@section('content')
  <form method="POST" action="{{ $mode === 'create' ? route('admin.users.store') : route('admin.users.update', $item) }}">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <div class="card" style="max-width:560px">
      <div class="card__head"><h2>Account</h2></div>
      <div class="field">
        <label for="name">Name</label>
        <input class="ctrl" type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <input class="ctrl" type="email" id="email" name="email" value="{{ old('email', $item->email) }}" required>
      </div>
      <div class="field">
        <label for="role">Role</label>
        <select class="ctrl" id="role" name="role">
          @foreach(['owner' => 'Owner — full access', 'admin' => 'Admin — full access', 'editor' => 'Editor — content only'] as $k => $label)
            <option value="{{ $k }}" @selected(old('role', $item->role ?: 'admin') === $k)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="ctrl" type="password" id="password" name="password" autocomplete="new-password"
               @if($mode === 'create') required @endif>
        <p class="field__hint">
          {{ $mode === 'edit' ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.' }}
        </p>
      </div>
      <button class="btn btn--primary" type="submit">{{ $mode === 'create' ? 'Create user' : 'Save changes' }}</button>
    </div>
  </form>
@endsection
