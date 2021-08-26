<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href = "{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/fivos.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/all.css')}}" rel="stylesheet" />
    </head>
    <body>
        @include('incl.header')
                <div class="container mt-5">
                    <h1 class="text-center">Το προφίλ μου</h1>
                </div>
                @if(session('message'))
                    <div class="container mt-5">
                        <div class="alert alert-success">
                            {{session('message')}}
                        </div>
                    </div>
                @endif
                <div class="container mt-5">
                    <div class="row">
                        <form class="col-12 col-md-6" method="POST" action="{{route('users.edit')}}">
                            @csrf
                            <h5>Επεξεργασία στοιχείων</h5>
                            <div class="form-group mt-4">
                                <input type="text" class="form-control" name="first_name" value="@if(old('first_name')) {{old('first_name')}} @else {{$authUser->first_name}} @endif" placeholder="Όνομα">
                                <span class="text-danger">@error('first_name') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" value="@if(old('last_name')) {{old('last_name')}} @else {{$authUser->last_name}} @endif" placeholder="Όνομα">
                                <span class="text-danger">@error('last_name') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" value="@if(old('email')) {{old('email')}} @else {{$authUser->email}} @endif" placeholder="E-mail">
                                <span class="text-danger">@error('email') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Αποθήκευση">
                            </div>
                        </form>
                        <form class="col-12 col-md-6 mt-5 mt-md-0" method="POST" action="{{route('users.password')}}">
                            @csrf
                            <h5>Αλλαγή κωδικού</h5>
                            <div class="form-group mt-4">
                                <input type="password" class="form-control" name="password" placeholder="τρέχων κωδικός">
                                <span class="text-danger">@error('password') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="new_password" placeholder="Κωδικός">
                                <span class="text-danger">@error('new_password') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Επιβεβαίωση κωδικού">
                                <span class="text-danger">@error('confirm_password') {{$message}} @enderror</span>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Αλλαγή">
                            </div>
                        </form>
                </div>
                </div>

                {{-- Msg modal --}}
                <div id="msg-modal" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>Modal body text goes here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('incl.footer')
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
    <script>
        $(document).ready(function(){
            
            //Removing the alert message
            window.setTimeout(function() {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
                    $(this).remove(); 
                });
            }, 3000);
        });
        
    </script>
</html>