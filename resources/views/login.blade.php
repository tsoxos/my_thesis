<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href = "{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/fivos.css')}}" rel="stylesheet" />
    </head>
    <body>
        <div class="all d-flex flex-column justify-content-between">
            <div class="content">
                <div class="container pt-5">
                    <div class="row mx-0">
                        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center">
                            <img class="logo-black" src="{{asset('assets/img/logo_black.png')}}" alt="">
                            <h4 class="ml-4">End covid-19</h4>
                        </div>
                        <div class="col-12 col-lg-6 mt-lg-0 mt-5">
                            @if(Session::has('msg'))
                                <span class="d-block w-100 mb-3 text-danger text-center">{{Session::get('msg')}}</span>
                            @endif
                            <form class="login card" method="POST" action="{{route('auth.login')}}">
                                @csrf
                                <div class="form-group">
                                    <h1>Είσοδος</h1>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="email" placeholder="E-mail" value="{{old('email')}}">
                                    <span class="text-danger">@error( 'email' ){{ $message }} @enderror</span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="password" placeholder="Κωδικός" >
                                    <span class="text-danger">@error( 'password' ){{ $message }} @enderror</span>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="Είσοδος">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('incl.footer')
        </div>
    </body>
</html>