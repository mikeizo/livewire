<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Signin</title>

    <!--<link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">-->
    <link href="/css/app.css" rel="stylesheet">

    <style>
      .form-signin {
          width: 100%;
          max-width: 330px;
          padding: 15px;
          margin: 0 auto;
      }
      .form-signin .checkbox {
          font-weight: 400;
      }
      .form-signin .form-control {
          position: relative;
          box-sizing: border-box;
          height: auto;
          padding: 10px;
          font-size: 16px;
          }
      .form-signin .form-control:focus {
          z-index: 2;
      }
      .form-signin input[type="email"] {
          margin-bottom: -1px;
          border-bottom-right-radius: 0;
          border-bottom-left-radius: 0;
          }
      .form-signin input[type="password"] {
          margin-bottom: 10px;
          border-top-left-radius: 0;
          border-top-right-radius: 0;
      }
    </style>
</head>

<body class="text-center">
  <form class="form-signin" method="POST" action="{{ route('login') }}">
    @csrf

    <h1 class="display-3 text-success">Livewire</h1>
    <h3 class="mb-3 font-weight-normal">Please sign in</h3>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror mb-1" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
    <label for="inputPassword" class="sr-only">Password</label>
    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
    <button type="submit" class="btn btn-lg btn-primary btn-block">
      {{ __('Login') }}
    </button>
    <div>
      @error('email')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
      @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
  </form>
</body>
</html>
