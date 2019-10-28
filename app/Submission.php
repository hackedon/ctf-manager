<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['user_id', 'level_id', 'submitted_text'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function box() {
        return $this->belongsTo(Box::class);
    }
}
