<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/css/fivos.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/css/all.css')}}" rel="stylesheet" />
    </head>
    <body>
        @include('incl.header')
                <div class="container mt-5">
                    <h1 class="text-center">Δημιουργία νέου χρήστη</h1>
                </div>
                <div class="container mt-5">
                    <form class="f-form card p-3" method="POST" action="{{route('admin.register.user')}}">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="first_name" placeholder="Όνομα" value="{{old('first_name')}}">
                            <span class="text-danger">@error('first_name') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="last_name" placeholder="Επίθετο" value="{{old('last_name')}}">
                            <span class="text-danger">@error('last_name') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="role_id">
                                <option value="1" @if(old('role') == '1') selected @endif>Editor</option>
                                <option value="2" @if(old('role') == '2') selected @endif>Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" placeholder="E-mail" value="{{old('email')}}">
                            <span class="text-danger">@error('email') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password" placeholder="Κωδικός">
                            <span class="text-danger">@error('password') {{$message}} @enderror</span>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Δημιουργία">
                        </div>
                    </form>
                </div>
            </div>
            @include('incl.footer')
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
</html>