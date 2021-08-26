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
                    <h1 class="text-center">Δημιουργία νέου ερωτηματολογίου</h1>
                </div>
                <div class="container mt-5">
                    <form class="questionnaire-form" method="POST" action='/add-questionnaire'>
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" placeholder="Τίτλος" value="{{old('title')}}">
                        </div>
                        <div class="form-group">
                            <h2>Ερωτήσεις</h2>
                        </div>
                        <hr>
                        @if(old('question'))
                            @foreach(old('question') as $question)
                                <div class="add-more-group">
                                    <label>Ερώτηση 1</label>
                                    <div class="row m-0 p-0">
                                        <div class="col-11 p-0">
                                            <div class="form-group">
                                                <textarea class="form-control f-textarea" name="question[]" placeholder="Ερώτηση">{{$question}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-1 p-0 d-flex align-items-center justify-content-center">
                                            <button class="remove btn btn-link"><i class="fas fa-minus mr-1"></i></button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endforeach
                        @else
                            <div class="add-more-group">
                                <label>Ερώτηση 1</label>
                                <div class="row m-0 p-0">
                                    <div class="col-11 p-0">
                                        <div class="form-group">
                                            <textarea class="form-control f-textarea" name="question[]" placeholder="Ερώτηση"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-1 p-0 d-flex align-items-center justify-content-center">
                                        <button class="remove btn btn-link"><i class="fas fa-minus mr-1"></i></button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endif
                        <div class="form-group">
                            <button id="add-more" class="btn btn-link"><i class="fas fa-plus mr-1"></i>Ερώτηση</button>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Υποβολή">
                        </div>
                    </form>
                </div>
            </div>
            @include('incl.footer')
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
    <script>
        $(document).ready(function() {

            //Counting questions
            var i = 1;

            //Inserts a new question to the DOM
            $('#add-more').on('click', function(e){
                e.preventDefault();
                i++;
                var questionBox = $(this).parent().prev();
                var clonedBox = questionBox.clone();
                clonedBox.find('label').text('Ερώτηση ' + i);
                clonedBox.find('textarea').val('');
                clonedBox.insertBefore($(this).parent());
            });

            //Removing a question
            $('body').on('click', '.remove', function(e){
                e.preventDefault();

                //Checking if we have more than one question so we 
                //can remove
                if($(".add-more-group").length > 1){
                    var questionBox = $(this).closest('.add-more-group');
                    questionBox.remove();
                    i = 0;
                    //Updating question number after removing
                    $(".add-more-group").each(function(){
                        i++
                        $(this).find('label').text('Ερώτηση ' + i);
                    });
                }
            });
        });
    </script>
</html>