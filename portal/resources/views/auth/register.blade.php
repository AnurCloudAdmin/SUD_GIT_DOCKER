<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>{{ config('app.name') }}</title>
        <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
        <meta content="Themesdesign" name="author" />
        <link rel="shortcut icon" href="{{ asset('images/icon.png')}}">
        <link href="{{ asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/assets/css/metismenu.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/assets/css/icons.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
                <div class="card card-pages shadow-none">
                <div class="card-body">
                <div class="text-center m-t-0 m-b-15">
                            <a href="index.html" class="logo logo-admin"><img src="{{asset('images/logo.jpg')}}" alt="" width="150"></a>
                        </div>
                        <h5 style="text-align:center">{{ __('Register') }}</h5>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        <!-- END wrapper -->
        <!-- jQuery  -->
        <script src="{{ asset('public/assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('public/assets/js/metismenu.min.js')}}"></script>
        <script src="{{ asset('public/assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{ asset('public/assets/js/waves.min.js')}}"></script>
        <!-- App js -->
        <script src="{{ asset('public/assets/js/app.js')}}"></script>
    </body>
</html>
