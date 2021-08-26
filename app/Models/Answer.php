<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    //An answer belongs to only one questionnaire
    public function questionnaire(){
        return $this->belongsTo('App\Models\Questionnaire');
    }

    //An answer belongs to only one token
    public function firebaseToken(){
        return $this->belongsTo('App\Models\FirebaseToken');
    }

    public function gender(){
        return $this->BelongsTo('App\Models\Gender');
    }

    public function age(){
        return $this->HasOne('App\Models\AgeGroup');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'answers' => 'array',
    ];
}
