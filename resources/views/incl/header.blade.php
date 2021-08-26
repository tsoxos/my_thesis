<div class="all d-flex flex-column justify-content-between">
    <div class="content">
        <header class="container-fluid">
            <nav class="container d-flex justify-content-between align-items-center">
                <a href="/">
                    <img class="logo" src="{{asset('assets/img/logo.png')}}" alt="">
                    <span>End covid-19</span>
                </a>
                @if(isset($authUser)) 
                <div class="dropdown show">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user mr-1"></i> {{$authUser['first_name']}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="/questionnaires"><i class="fas fa-question-circle mr-1"></i>Ερωτηματολόγια</a>
                        @if($authUser->role->name == 'Admin' || $authUser->role->name == 'Super admin')
                            <a class="dropdown-item" href="/admin/users"><i class="fas fa-users mr-1"></i>Χρήστες</a>
                        @endif
                        <a class="dropdown-item" href="/profile"><i class="fas fa-user mr-1"></i>Προφίλ</a>
                        <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt mr-1"></i>Αποσύνδεση</a>
                    </div>
                </div>
                @else
                    <a href="{{route('login')}}"><i class="fas fa-sign-in-alt mr-2"></i>Login</a>
                @endif
            </nav>
        </header>

