<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tba extends Model
{
    protected $fillable = ['tbaBody', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
