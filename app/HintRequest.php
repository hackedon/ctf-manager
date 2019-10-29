<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HintRequest extends Model
{
    protected $table = 'hint_requests';
    protected $fillable = ['user_id', 'box_id', 'cost', 'active'];

    public function box() {
        return $this->belongsTo(Box::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
