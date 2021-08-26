<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;

use Auth;
use Redirect;

class userController extends BaseController
{
    function index(){
        $user = User::find(Auth::user()->id);
        $users = User::all();
        $roles = Role::where('name', 'Editor')->orWhere('name', 'Admin')->get();
        return view('users.users', ['users' => $users, 'authUser' => $user, 'roles' => $roles]);
    }

    function getNew(){
        $user = User::find(Auth::user()->id);
        return view('users.new-user', ['authUser' => $user]);
    }

    function register(Request $request){
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'role_id'=>'required|in:1,2,3',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|max:12'
        ]);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.users')->with('message', 'Ο χρήστης προστέθηκε');
    }

    function updateRole(Request $request){
        $validator = Validator::make(request()->all(), [
            'role_id'=>'required|in:1,2'
        ]);

        if(!$validator->fails()){
            if(Auth::user()->role->id == '3'){
                $user = User::find($request->id);
                $user->role_id = $request->role_id;
                $user->save();
                return response()->json(['role' => $user->role->name, 'msg' => 'Η αλλαγή πραγματοποιήθηκε.']);
            }else{
                if(Auth::user()->role->id == '2'){
                    if($request->role_id == '1'){
                        return response()->json(['msg' => 'Δεν μπορείτε να εκχωρήσετε τον συγκεκριμένο ρόλο.']);
                    }else{
                        $user = User::find($request->id);
                        $user->role_id = $request->role_id;
                        $user->save();
                        return response()->json(['role' => $user->role->name, 'msg' => 'Η αλλαγή πραγματοποιήθηκε.']);
                    }
                }
            }
        }else{
            return response()->json(['msg' => 'Δεν μπορείτε να εκχωρήσετε τον συγκεκριμένο ρόλο.']);
        }
    }

    function getProfile(){
        $user = User::find(Auth::user()->id);
        return view('users.profile', ['authUser' => $user]);
    }

    function postProfile(Request $request){
        $user = Auth::user();

        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
        ]);

        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('users.profile')->with('message', 'H αλλαγές καταχωρήθηκαν');
    }

    function postPassword(Request $request){
        $request->validate([
            'password' => ['required', new MatchOldPassword],
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('users.profile')->with('message', 'Ο κωδικός σας άλλαξε');
    }

    function postDelete(Request $request){
        $validator = Validator::make(request()->all(), [
            'id'=>'required|not_in:1'
        ]);
        if(!$validator->fails()){
            if(Auth::user()->role->id == '3'){
                $user = User::find($request->id);
                $questionnaires = $user->questionnaire()->update([
                    'user_id' => '1'
                ]);
                $user->delete();
                return response()->json(['completed' => true, 'msg' => 'Ο χρήστης διαγράφτηκε.']);
            }else{
                $user = User::find($request->id);
                if($user->role->id != '2'){
                    $questionnaires = $user->questionnaire()->update([
                        'user_id' => '1'
                    ]);
                    $user->delete();
            
                    return response()->json(['completed' => true, 'msg' => 'Ο χρήστης διαγράφτηκε.']);
                }else{
                    return response()->json(['completed' => false, 'msg' => 'Δεν μπορείτε να διαγράψετε τον συγκεκριμένο χρήστη']);
                }
            }   
        }else{
            return response()->json(['completed' => false, 'msg' => 'Αποτυχία διαγραφής.']);
        }
    }
}