<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['box_id', 'flag', 'points', 'flag_no'];

    public function box(){
        return $this->belongsTo(Box::class);
    }

    public function submissions(){
        return $this->hasMany(Submission::class);
    }
}
