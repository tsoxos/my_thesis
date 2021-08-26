<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;

    public function answers(){
        return $this->belongsToMany('App\Models\Answer');
    }
}
