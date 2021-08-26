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
                    <h1 class="text-center">Χρήστες</h1>
                </div>
                @if(session('message'))
                    <div class="container mt-5">
                        <div class="alert alert-success">
                            {{session('message')}}
                        </div>
                    </div>
                @endif
                <div class="container mt-5">
                    <a class="f-btn-width btn btn-primary" href="/admin/new-user"><i class="fas fa-plus mr-1"></i>Νέος χρήστης</a>
                </div>
                <div class="container mt-5">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark head-font">
                            <tr>
                                <th scope="col"><i class="fas fa-calendar-alt mr-2"></i><span class="d-none d-md-inline">Ημ/νία</span></th>
                                <th scope="col"><i class="fas fa-signature mr-2"></i><span class="d-none d-md-inline">Όνομα</span></th>
                                <th scope="col"><i class="fas fa-user-tag mr-2"></i><span class="d-none d-md-inline">Ρόλος</span></th>
                                <th scope="col"><i class="fas fa-radiation mr-2"></i><span class="d-none d-md-inline">Ενέργειες</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="col-2">{{date_format($user['created_at'], 'd-m-Y')}}</td>
                                    <td class="col-3">{{$user['first_name']}} {{$user['last_name']}}</td>
                                    <td class="col-5">
                                        @if($authUser->role->id == '3')
                                            @if($user->role->id == '3')
                                                {{$user->role->name}}
                                            @else
                                                <select class="role form-control">
                                                    @foreach($roles as $role)
                                                        <option value="{{$role['id']}}" @if($user->role->id == $role['id']) selected @endif >{{$role['name']}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        @else
                                            @if($user->role->id == '3' || $user->role->id == '2')
                                                {{$user->role->name}}
                                            @else
                                                <select class="role form-control">
                                                    @foreach($roles as $role)
                                                        <option value="{{$role['id']}}" @if($user->role->id == $role['id']) selected @endif >{{$role['name']}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="col-2">
                                        @if($authUser->role->id == '3')
                                            @if($user->role->id == '2' || $user->role->id == '1')
                                                <a href="#" data-id="{{$user['id']}}" class="save-user btn btn-primary d-block w-100"><i class="fas fa-save"></i></i></a>
                                                <br>
                                                <button data-id="{{$user['id']}}" class="btn btn-danger d-block w-100 btn-delete"><i class="fas fa-trash-alt"></i</button>
                                            @endif
                                        @else
                                            @if($user->role->id == '1')
                                                <a href="#" data-id="{{$user['id']}}" class="save-user btn btn-primary d-block w-100"><i class="fas fa-save"></i></i></a>
                                                <br>
                                                <button data-id="{{$user['id']}}" class="btn btn-danger d-block w-100 btn-delete"><i class="fas fa-trash-alt"></i</button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </div>

                {{-- Delete modal --}}
                <div class="modal fade" data-id="" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Είστε σίγουρος/η ότι θέλετε να διαγράψετε τον συγκεκριμένο χρήστη;</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Τα ερωτηματολόγια που έχει δημιουργήσει ο συγκεκριμένος χρήστης θα περάσουν στον Super admin.
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
            var element;
            var msg_modal = $('#msg-modal');
            var delete_modal = $('#delete-modal');

            //Removing the alert message
            window.setTimeout(function() {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
                    $(this).remove(); 
                });
            }, 3000);

            $(document).on('click', '.btn-delete', function(e){
                var id = $(this).data('id');
                delete_modal.find('#delete-id').val(id)
                delete_modal.modal('show');
                element = $(this).parent().parent();
            });

            delete_modal.find('#delete').on('click', function(e){
                var id = $(this).parent().find('#delete-id').val();
                //alert(id);
                $.ajax({
                    url: "{{route('admin.user.delete')}}",
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

            //updating user
            $('.save-user').on('click', function(e){
                e.preventDefault();
                var saveBtn = $(this);
                var deleteBtn = saveBtn.parent().find('.btn-delete');
                var id = saveBtn.data('id');
                var roleElem = saveBtn.parent().prev().find('.role');
                var role = roleElem.val();
                
                $.ajax({
                    url: "/admin/update-role",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        role_id: role
                    },
                    success:function(response){
                        msg_modal.find('.modal-body').html('<p>'+response["msg"]+'</p>')
                        if(response["role"] == 'Admin'){
                            var parent = roleElem.parent();
                            saveBtn.remove();
                            deleteBtn.remove();
                            roleElem.remove();
                            parent.html(response["role"])
                        }
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