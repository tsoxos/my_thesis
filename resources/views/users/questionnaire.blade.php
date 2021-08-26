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
                    <h1 class="text-center">Ερωτηματολόγια</h1>
                </div>
                <div class="container mt-5 alert alert-success @if(!session('message')) d-none @endif" role="alert">
                        @if(session('message'))
                            {{session('message')}}
                        @endif
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
        
                <div class="container mt-5">
                    <a class="f-btn-width btn btn-primary" href="/new-questionnaire"><i class="fas fa-plus mr-1"></i>Νέο</a>
                </div>
                <div class="container mt-5">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark head-font">
                            <tr>
                                <th class="col-2"><i class="fas fa-calendar-alt mr-2"></i><span class="d-none d-md-inline">Ημ/νία</span></th>
                                <th class="col-4"><i class="fas fa-heading mr-2"></i><span class="d-none d-md-inline">Τίτλος</span></th>
                                <th class="col-4"><i class="fas fa-user mr-2"></i><span class="d-none d-md-inline">Δημιουργός</span></th>
                                <th class="col-2"><i class="fas fa-radiation mr-2"></i><span class="d-none d-md-inline">Ενέργειες</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questionnaires as $questionnaire)
                                <tr>
                                    <td class="col-2">{{date_format($questionnaire['created_at'], 'd-m-Y')}}</td>
                                    <td class="col-4">{{$questionnaire['title']}}</td>
                                    <td class="col-4">{{$questionnaire->user->first_name}} {{$questionnaire->user->last_name}}</td>
                                    <td class="col-2">
                                        <a href="/edit-questionnaire/{{$questionnaire['id']}}" class="btn btn-secondary d-block w-100"><i class="fas fa-pencil-alt"></i></a>
                                        <br>
                                        <button data-id="{{$questionnaire['id']}}" class="btn btn-danger d-block w-100 btn-delete"><i class="fas fa-trash-alt"></i></button>
                                        @if($authUser->role->name == 'Admin' || $authUser->role->name == 'Super admin')
                                            <br>
                                            @if(!$questionnaire['active'])
                                                <button data-id="{{$questionnaire['id']}}" class="btn btn-dark d-block w-100 btn-active"><i class="fas fa-play"></i></button>
                                            @else 
                                                <button data-id="{{$questionnaire['id']}}" class="btn btn-success d-block w-100 btn-active"><i class="fas fa-stop"></i></button>
                                            @endif
                                            <br>
                                            @if(!$questionnaire['display'])
                                                <button data-id="{{$questionnaire['id']}}" class="btn btn-secondary d-block w-100 btn-display"><i class="fas fa-eye"></i></button>
                                            @else
                                                <button data-id="{{$questionnaire['id']}}" class="btn btn-primary d-block w-100 btn-display"><i class="fas fa-eye-slash"></i></button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mb-5">
                        {!! $questionnaires->links() !!}
                    </div>
                </div>
                {{-- Delete modal --}}
                <div class="modal fade" data-id="" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Είστε σίγουρος/η ότι θέλετε να σβήσετε το συγκεκριμένο ερωτηματολόγιο;</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Οι ερωτήσεις καθώς και οι απαντήσεις σε αυτές θα χαθούν.
                            </div>
                            <div class="modal-footer">
                                <input id="delete-id" type="hidden" value="">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
                                <button id="delete" type="button" class="btn btn-primary">Είμαι σίγουρος</button>
                            </div>
                        </div>
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
            var msg_modal = $('#msg-modal');
            var delete_modal = $('#delete-modal');
            var element;

            //Removing the alert message
            window.setTimeout(function() {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
                    $(this).hide(); 
                });
            }, 3000);

            $(document).on('click', '.btn-delete', function(){
                var id = $(this).data('id');
                delete_modal.find('#delete-id').val(id)
                delete_modal.modal('show');
                element = $(this).parent().parent();
            });

            delete_modal.find('#delete').on('click', function(e){
                var id = $(this).parent().find('#delete-id').val();
    
                $.ajax({
                    url: "{{route('questionnaire.delete')}}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success:function(response){
                        if(response['completed']){
                            element.remove();
                        }
                        delete_modal.modal('hide');
                        msg_modal.find('.modal-body').html('<p>'+response["msg"]+'</p>')
                        msg_modal.modal('show')
                    },
                    error: function(){
                        msg_modal.find('.modal-body').html('<p> Σφάλμα διακομιστή, παρακαλώ δοκιμάστε αργότερα. </p>')
                        delete_modal.modal('hide');
                        msg_modal.modal('show');
                    }
                });
            });
            
            $(document).on('click', '.btn-active', function(){
                var id = $(this).data('id');
                var btnActive = $(this);
                
                $.ajax({
                    url: "{{route('questionnaire.status')}}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success:function(response){
                        if(response['changeButtons']){
                            if(btnActive.hasClass('btn-success')){
                                btnActive.removeClass('btn-success');
                                btnActive.addClass('btn-dark');
                                btnActive.html('<i class="fas fa-play"></i>');
                            }else if(btnActive.hasClass('btn-dark')){
                                btnActive.removeClass('btn-dark');
                                btnActive.addClass('btn-success');
                                btnActive.html('<i class="fas fa-stop"></i>');
                                $('.btn-active').not(btnActive).removeClass('btn-success');
                                $('.btn-active').not(btnActive).addClass('btn-dark');
                                $('.btn-active').not(btnActive).html('<i class="fas fa-play"></i>');
                            }
                        }
                        msg_modal.find('.modal-body').html('<p>'+response["msg"]+'</p>')
                        msg_modal.modal('show');
                    },
                    error: function(){
                        msg_modal.find('.modal-body').html('<p> Σφάλμα διακομιστή, παρακαλώ δοκιμάστε αργότερα. </p>')
                        delete_modal.modal('hide');
                        msg_modal.modal('show');
                    }
                });
            });
            $(document).on('click', '.btn-display', function(){
                var id = $(this).data('id');
                var btnDisplay = $(this);

                $.ajax({
                    url: "{{route('questionnaire.display')}}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success:function(response){
                        if(response['changeButtons']){
                            if(btnDisplay.hasClass('btn-secondary')){
                                btnDisplay.removeClass('btn-secondary');
                                btnDisplay.addClass('btn-primary');
                                btnDisplay.html('<i class="fas fa-eye-slash"></i>');
                            }else if(btnDisplay.hasClass('btn-primary')){
                                btnDisplay.removeClass('btn-primary');
                                btnDisplay.addClass('btn-secondary');
                                btnDisplay.html('<i class="fas fa-eye"></i>');
                            }
                        }
                        msg_modal.find('.modal-body').html('<p>'+response["msg"]+'</p>')
                        msg_modal.modal('show');
                    },
                    error: function(){
                        msg_modal.find('.modal-body').html('<p> Σφάλμα διακομιστή, παρακαλώ δοκιμάστε αργότερα. </p>')
                        delete_modal.modal('hide');
                        msg_modal.modal('show');
                    }
                });
            });
        });
        
    </script>
</html>