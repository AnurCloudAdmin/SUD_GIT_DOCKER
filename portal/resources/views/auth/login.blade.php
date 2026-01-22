<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>{{ config('app.name') }}</title>
        <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
        <meta content="Themesdesign" name="author" />
        <link rel="shortcut icon" href="{{env('APP_URL')}}/public/images/logo.png">
        <link href="{{env('APP_URL')}}/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
                <div class="card card-pages shadow-none">
                    <div class="card-body">
                        <div class="text-center m-t-0 m-b-15">
                            <a href="index.html" class="logo logo-admin"><img src="{{env('APP_URL')}}/public/images/logo.png" alt="" width="80"></a>
                        </div>
                        <form class="form-horizontal m-t-30" method="post" action="{{env('APP_URL')}}login">
                          @csrf
                            <div class="form-group">
                                <div class="col-12">
                                        <label>{{ __('Email') }}</label>
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                        <label>{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
                                </div>
                            </div>
                            <div class="form-group text-center m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </div>
                            <!-- <div class="form-group row m-t-30 m-b-0">
                                <div class="col-sm-7">
                                    <a href="pages-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                                </div>
                                <div class="col-sm-5 text-right">
                                    <a href="pages-register.html" class="text-muted">Create an account</a>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        <!-- END wrapper -->
        <!-- jQuery  -->
        <script src="{{env('APP_URL')}}/public/assets/js/jquery.min.js"></script>
        <script src="{{env('APP_URL')}}/public/assets/js/bootstrap.bundle.min.js"></script>
        <script src="{{env('APP_URL')}}/public/assets/js/metismenu.min.js"></script>
        <script src="{{env('APP_URL')}}/public/assets/js/jquery.slimscroll.js"></script>
        <script src="{{env('APP_URL')}}/public/assets/js/waves.min.js"></script>
        <!-- App js -->
        <script src="{{env('APP_URL')}}/public/assets/js/app.js"></script>
    </body>
</html>
