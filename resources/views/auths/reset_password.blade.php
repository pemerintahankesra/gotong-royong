<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi Gotong Royong | Bagian Pemerintahan dan Kesejahteraan Rakyat</title>
    <!-- Bootstrap 5.2.0 -->
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Login Style -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row no-gutter">
            <div class="col-md-6 d-none d-md-flex bg-image"></div>
            <div class="col-md-6 bg-light">
                <div class="login d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 col-xl-7 mx-auto">
                                <h4 class="display-5 mb-3">Reset Password</h4>
                                <form action="{{ route('auth.reset_password_action') }}" method="POST">
                                    @csrf
                                    @if($errors->any())
                                    <div class="text-danger mb-3"><small>Mohon cek kembali username</small></div>
                                    @endif
                                    <div class="form-group mb-3">
                                        <input type="text" name="username" id="username" class="form-control border-0 shadow-sm px-4 rounded-pill" placeholder="Username atau Email" value="{{ old('username') }}" autofocus required>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary rounded-pill btn-block shadow-sm">Reset Password</button>
                                        <small class="text-center text-muted">Mau Log In?<a href="{{route('auth.login')}}" class="text-small">Klik disini</a></small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Jquery -->
    <script src="{{ asset('libs/jquery/jquery-3.6.0.min.js') }}"></script>
    <!-- Bootstrap & Popper -->
    <script src="{{ asset('libs/popper/popper.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>