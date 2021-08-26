<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href = "{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/fivos.css')}}" rel="stylesheet" />
        <link href = "{{asset('assets/css/all.css')}}" rel="stylesheet" />
    </head>
        @include('incl.header')
                <div class="container mt-5">
                    <h1 class="text-center">Έρευνες</h1>
                </div>
                <div class="container mt-5">
                    <div class="row">
                        @foreach($questionnaires as $questionnaire)
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <a class="black" href="/questionnaire/{{$questionnaire->id}}">
                                    <div class="card">
                                        <div class="p-4 d-flex align-items-center justify-content-between">
                                            <div class="circle d-flex align-items-center justify-content-center">
                                                <i class="fas fa-poll"></i>
                                            </div>
                                            <span class="ml-3">{{$questionnaire->title}}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
               
            </div>
            @include('incl.footer')
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
</html>