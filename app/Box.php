<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['title', 'description', 'difficulty', 'logo', 'author'];

    public function levels(){
        return $this->hasMany(Level::class);
    }
}
