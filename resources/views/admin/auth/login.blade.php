<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Sign in · {{ $s->get('site_name') }} CMS</title>
<link rel="icon" href="{{ asset('assets/img/brand/favicon-32.png') }}" sizes="32x32" type="image/png">
<link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
  <main class="login">
    <div class="login__box">
      <div class="login__brand">
        <img src="{{ asset('assets/img/brand/logo-mark.webp') }}" alt="">
        <h1>{{ $s->get('site_name') }}</h1>
        <p>Content manager</p>
      </div>

      @if($errors->any())
        <div class="notice notice--err">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf
        <div class="field">
          <label for="email">Email address</label>
          <input class="ctrl" type="email" id="email" name="email" value="{{ old('email') }}"
                 autocomplete="username" required autofocus>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input class="ctrl" type="password" id="password" name="password"
                 autocomplete="current-password" required>
        </div>
        <label class="switch" style="margin-bottom:1.1rem">
          <input type="checkbox" name="remember" value="1"><i></i> Keep me signed in
        </label>
        <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">Sign in</button>
      </form>
    </div>
  </main>
</body>
</html>
