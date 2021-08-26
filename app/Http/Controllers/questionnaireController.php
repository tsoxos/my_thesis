<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Answer;
use App\Models\Gender;
use App\Models\Questionnaire;
use App\Models\FirebaseToken;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

use Auth;

class questionnaireController extends BaseController
{
    function index(){
        
        $user = User::find(Auth::user()->id);
        if($user->role->id == '2' || $user->role->id == '3'){
            $questionnaires = Questionnaire::paginate(5);
        }else{
            $questionnaires = Questionnaire::where('user_id', Auth::user()->id)->paginate(5);
        }
       
        return view('users.questionnaire', ['authUser' => $user], ['questionnaires' => $questionnaires]);
    }

    function getQuestionnaire($id){

        if(Auth::user()){
            $user = User::find(Auth::user()->id);
        }else{
            $user = null;
        }


        $questionnaire = Questionnaire::where(['id' => $id, 'display' => 1])->first();
        if($questionnaire){
            $answers_all = Answer::where(['questionnaire_id' => $questionnaire->id])->get();

            $answers_men = Answer::whereHas('gender', function($q){
                $q ->where('id', '=', 1);
            })->where((['questionnaire_id' => $questionnaire->id]))->get();
    
            $answers_women = Answer::whereHas('gender', function($q){
                $q ->where('id', '=', 2);
            })->where((['questionnaire_id' => $questionnaire->id]))->get();

            $answers_other = Answer::whereHas('gender', function($q){
                $q ->where('id', '=', 3);
            })->where((['questionnaire_id' => $questionnaire->id]))->get();
           
            if(!$answers_all->isEmpty()){
                $data_all = $this->answerData($questionnaire, $answers_all);
            }else{
                $data_all = null;
            }

            if(!$answers_men->isEmpty()){
                $data_men = $this->answerData($questionnaire, $answers_men);
            }else{
                $data_men = null;
            }

            if(!$answers_women->isEmpty()){
                $data_women = $this->answerData($questionnaire, $answers_women);
            }else{
                $data_women = null;
            }
            if(!$answers_other->isEmpty()){
                $data_other = $this->answerData($questionnaire, $answers_other);
            }else{
                $data_other = null;
            }
    
            return view('public.questionnaire', ['authUser' => $user, 'questionnaire' => $questionnaire, 'data_all' => $data_all, 'data_men' => $data_men, 'data_women' => $data_women, 'data_other' => $data_other]);
        }else{
            abort(404);
        }

        
    }

    // Prepares the data for showng them in charts
    function answerData($questionnaire, $answers){

        //Creating an empty array
        $data = array();

        $isFisrtRun = true;
        foreach($answers as $a){
            $i = 0;
            foreach($a->answers as $answer){
                if($answer == "true"){
                    if($isFisrtRun){
                        array_push($data, array('question' => '', 'true' => 1, 'false' => 0));
                    }else{
                        $data[$i]['true']++;
                    }
                }else{
                    if($isFisrtRun){
                        array_push($data, array('question' => '', 'true' => 0, 'false' => 1));
                    }else{
                        $data[$i]['false']++;
                    }
                }
                $i++;
            }
            $isFisrtRun = false;
        }

        $i = 0;
        foreach($questionnaire->questions as $question){
            $data[$i]['question'] = $question;
            $i++;
        }

        

        return $data;
    }

    function addNew(){

        $user = User::find(Auth::user()->id);

        return view('users.new-questionnaire', ['authUser' => $user]);
    }

    function edit($id){
        $user = User::find(Auth::user()->id);
        $questionnaire = Questionnaire::find($id);
        if(Auth::user()->role->id == '1'){
            if($questionnaire->user_id == Auth::user()->id){
                if($questionnaire->editable){
                    return view('users.edit-questionnaire', ['authUser' => $user, 'questionnaire' => $questionnaire]);
                }else{
                    return redirect()->route('questionnaires')->with('message', 'Δεν μπορείτε να επεξεργαστείτε ένα ερωτηματολόγιο το οποίο έχει ενεργοποιηθεί τουλαχιστον μία φορά');
                }
            }else{
                return redirect()->route('questionnaires')->with('message', 'Δεν έχετε δικαιώματα ανάγνωσης στο συγκεκριμένο ερωτηματολόγιο');
            }
        }else{
            if($questionnaire->editable){
                return view('users.edit-questionnaire', ['authUser' => $user, 'questionnaire' => $questionnaire]);
            }else{
                return redirect()->route('questionnaires')->with('message', 'Δεν μπορείτε να επεξεργαστείτε ένα ερωτηματολόγιο το οποίο έχει ενεργοποιηθεί τουλαχιστον μία φορά');
            }
        }
    }

    function postEdit(Request $request){

        $request->validate([
            'title'=>'required',
            'question.*'=>'required'
        ]);

        $questionnaire = Questionnaire::find($request->id);

        if(Auth::user()->role->id == '1'){
            if($questionnaire->user_id == Auth::user()->id){
                if($questionnaire->editable){
                    $questionnaire->title = $request->title;
                    $questionnaire->questions = $request->question;
                    $questionnaire->save();
                    return redirect()->route('questionnaires')->with('message', 'H αλλαγές πραγματοποιήθηκαν με επιτυχία');
                }else{
                    return redirect()->route('questionnaires')->with('message', 'Δεν μπορείτε να επεξεργαστείτε ένα ερωτηματολόγιο το οποίο έχει ενεργοποιηθεί τουλαχιστον μία φορά');
                }
            }else{
                return response()->json(['msg' => 'Δεν έχετε δικαίωμα επεξεργασίας στο συγκεκριμένο ερωτηματολόγιο']);
            }
        }else{
            if($questionnaire->editable){
                $questionnaire->title = $request->title;
                $questionnaire->questions = $request->question;
                $questionnaire->save();
                return redirect()->route('questionnaires')->with('message', 'H αλλαγές πραγματοποιήθηκαν με επιτυχία');
            }else{
                return redirect()->route('questionnaires')->with('message', 'Δεν μπορείτε να επεξεργαστείτε ένα ερωτηματολόγιο το οποίο έχει ενεργοποιηθεί τουλαχιστον μία φορά');
            }
        }
    }

    function postNew(Request $request){

        $request->validate([
            'title'=>'required',
            'question.*'=>'required'
        ]);

        $questionnaire = new Questionnaire;
        $questionnaire->user_id = Auth::user()->id;
        $questionnaire->title = $request->title;
        $questionnaire->questions = $request->question;
        $questionnaire->save();

        return redirect()->route('questionnaires')->with('message', 'Το ερωτηματολόγιο προστέθηκε με επιτυχία');
    }

    function postDisplay(Request $request){
        $questionnaire = Questionnaire::find($request->id);
        if($questionnaire->display == '0'){
            $questionnaire->display = '1';
            $changeButtons = true;
            $msg = 'Το ερωτηματολόγιο είναι πλέον ορατό';
        }else{
            $questionnaire->display = '0';
            $changeButtons = true;
            $msg = 'Το ερωτηματολόγιο δεν είναι πια ορατό';
        }
        $questionnaire->save();

        return response()->json((['msg' => $msg, 'changeButtons' => $changeButtons]));
    }

    function postActive(Request $request){

        $questionnaire = Questionnaire::find($request->id);
        if($questionnaire->active == '0'){

            Questionnaire::where('active', '=', '1')->update(['active' => '0']);
            $questionnaire->active = '1';
            $questionnaire->editable = '0';
            $changeButtons = true;
            $msg = 'Το ερωτηματολόγιο ενεργοποιήθηκε.';

            $devices = FirebaseToken::pluck('token')->all();
            $message = 'Υπάρχει διαθέσιμο ένα νέο ερωτηματολόγιο, μπορείτε να το συμπληρώσετε συμβάλλοντας στην έρευνα.';
            $this->sendNotification($devices, $message);

        }else{

            $questionnaire->active = '0';
            $changeButtons = true;
            $msg = 'Το ερωτηματολόγιο απενεργοποιήθηκε.';

        }

        $questionnaire->save();
        return response()->json((['msg' => $msg, 'changeButtons' => $changeButtons]));
    }

    function sendNotification($devices, $message){

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids' => $devices,
            'notification' => array(
                'title' => 'Νέο ερωτηματολόγιο',
                'body' => $message
            )
        );

        $headers = array(
            'Authorization:key=' . env('FIREBASE_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        

        if ($result === FALSE) {
            $msg = 'Error sending notifications';
            
        }else{
            $msg = 'Notificatios sended';
        }

        return $msg;

    }

    function getActiveQuestionnaire(){

        $questionnaire = Questionnaire::where('active', '1')->first();
        
        return response()->json($questionnaire);
    }

    function postDelete(Request $request){
        $validator = Validator::make(request()->all(), [
            'id'=>'required'
        ]);
        if(!$validator->fails()){
            try{
                $questionnaire = Questionnaire::find($request->id);
                if(Auth::user()->role->id = 1){
                    if($questionnaire->user_id == Auth::user()->id){
                        $questionnaire->answers()->delete();
                        $questionnaire->delete();
                        return response()->json(['completed' => true, 'msg' => 'Το ερωτηματολόγιο διαγράφτηκε']);
                    }else{
                        return response()->json(['completed' => false, 'msg' => 'Δεν έχετε δικαίωμα διαγραφής σε αυτό το ερωτηματολόγιο']);
                    }
                }else{
                    $questionnaire->answers()->delete();
                    $questionnaire->delete();
                    return response()->json(['completed' => true, 'msg' => 'Το ερωτηματολόγιο διαγράφτηκε']);
                }

            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['completed' => false, 'msg' => 'Αποτυχία διαγραφής']);
            }
        }else{
            return response()->json(['completed' => false, 'msg' => 'Αποτυχία διαγραφής']);
        }
    }

    function postAnswer(Request $request){
        $validator = Validator::make(request()->all(), [
            'questionnaire_id'=>'required',
            'gender_id'=>'required',
            'age_group_id'=>'required',
            'answers'=>'required',
            'token'=>'required'
        ]);

        if(!$validator->fails()){
            try{
                $token = FirebaseToken::where('token', $request->token)->first();

                $answer = new Answer;
                $answer->questionnaire_id = $request->questionnaire_id;
                $answer->gender_id = $request->gender_id;
                $answer->age_group_id = $request->age_group_id;
                $answer->token_id = $token->id;
                $answer->answers = $request->answers;
                $answer->save();

                return response()->json(['completed' => true, 'msg' => 'Οι απαντήσεις σας καταχωρήθηκαν με επιτυχία. Σας ευχαριστούμε για τον χρόνο σας, θα σας ενημερώσουμε για νέα ερωτηματολόγια.']);
              
              
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    return response()->json(['completed' => false, 'msg' => 'Έχετε ήδη απαντήσει στο συγκεκριμένο ερωτηματολόγιο.']);
                }
                return response()->json(['completed' => false, 'msg' => 'Συνέβη κάποιο σφάλμα παρακαλούμε δοκιμάστε αργότερα.']);
            }
           
        }else{
            return response()->json(['completed' => false, 'msg' => 'Συνέβη κάποιο σφάλμα παρακαλούμε δοκιμάστε αργότερα.']);
        }
    }
}