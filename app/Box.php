<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['title', 'description', 'difficulty', 'logo', 'author', 'url'];

    public function levels() {
        return $this->hasMany(Level::class);
    }

    public function submissions() {
        return $this->hasMany(Submission::class);
    }
}
