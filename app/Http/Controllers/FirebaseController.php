<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FirebaseToken;


class FirebaseController extends Controller
{
    function postToken(Request $request){
        $validator = Validator::make(request()->all(), [
            'token'=>'required|unique:firebase_tokens'
        ]);
        
        if(!$validator->fails()){
            $token = new FirebaseToken;
            $token->token = $request->token;
            if($token->save()){
                return response()->json(['completed' => true, 'msg' => 'App Registered']);
            }else{
                return response()->json(['completed' => false, 'msg' => 'Η εφαρμογή δεν ταυτοποιήθηκε']);
            }
        }else{
            return response()->json(['completed' => false, 'msg' => 'Παρουσιάστηκε σφάλμα']);
        }
    }
}
