<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable = ['user_id', 'submission', 'original_filename'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
