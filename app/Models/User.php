<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //A user can have multible questionnaires
    public function questionnaire(){
        return $this->hasMany('App\Models\Questionnaire');
    }

    //A user can can only have one role
    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    //Checks if user is Admin or Super admin
    public function hasAdminRole(){
        if($this->role()->where('id', '2')->first() || $this->role()->where('id', '3')->first()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
