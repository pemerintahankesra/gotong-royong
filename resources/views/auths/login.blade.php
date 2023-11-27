<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi Gotong Royong | Bagian Pemerintahan dan Kesejahteraan Rakyat</title>
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Login Style -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <div class="container-fluid">
    <div class="row no-gutter">
      <div class="col-md-6 d-none d-md-flex bg-image"></div>
      <div class="col-md-6 bg-light">
        <div class="login d-flex align-items-center">
          <div class="container">
            <div class="row">
              <div class="col-lg-10 col-xl-7 col-xxl-6 text-center mx-auto">
                <h4 class="display-5 mb-3">Dana Bantuan Gotong Royong</h4>
                <hr>
                <p class="display-6 mb-3">Login</p>
                <form action="{{ route('auth.login_action') }}" method="POST">
                  @csrf
                  @if($errors->any())
                  <div class="text-danger mb-3"><small>Mohon cek kembali inputan anda</small></div>
                  @endif
                  @if(\Session::has('success'))
                  <div class="text-success mb-3"><small>{!!\Session::get('success')!!}</small></div>
                  @endif
                  <div class="form-group mb-3">
                    <input type="text" name="username" id="username" class="form-control border-0 shadow-sm px-4 rounded-pill" placeholder="Username atau Email" value="{{ old('username') }}" autofocus required>
                  </div>
                  <div class="form-group mb-3">
                    <input type="password" name="password" id="password" class="form-control border-0 shadow-sm px-4 rounded-pill" placeholder="Password" required>
                  </div>
                  <div class="g-recaptcha mb-3" data-sitekey={{config('services.recaptcha.key')}}></div>
                  <div class="d-grid gap-2">
                    <button class="btn btn-primary rounded-pill btn-block shadow-sm">Log In</button>
                    <small class="text-center text-muted">Reset Password? <a href="{{route('auth.reset_password')}}" class="text-small">Klik disini</a></small>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>