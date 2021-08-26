<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Questionnaire;
use App\Models\Answer;

use Auth;

use Illuminate\Routing\Controller as BaseController;

class homeController extends BaseController
{
    function index(){
        if(Auth::user()){
            $user = User::find(Auth::user()->id);
        }else{
            $user = null;
        }

        $questionnaires = Questionnaire::where('display', '1')->get();

        return view('public.home', ['authUser' => $user, 'questionnaires' => $questionnaires]);
    }
}