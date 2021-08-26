<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Redirect;

class authController extends BaseController
{
    function index(){
        if(Auth::User()){
            return redirect('/');
        }else{
            return view('login');
        }
    }

    function getSign_in(){
        return redirect('/login');
    }

    function sign_in(Request $request){
        
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }else{
            return redirect()->back()->with('msg', 'Λάθος στοιχεία σύνδεσης.');   
        }
    }

    function logout(){
        Auth::logout();
        return redirect('/login');
    }
}