<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\questionnaireController;

class Questionnaire extends Model
{
    use HasFactory;

    //A questionnaire belongs to only one user
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    //Every question has many answers
    public function answers(){
        return $this->hasMany('App\Models\Answer');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'questions' => 'array',
        
    ];

}
