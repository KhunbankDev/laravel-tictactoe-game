<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boardgame extends Model
{
    protected $table = 'boardgame';
    protected $primaryKey = 'id';

    public function PlayGame()
    {
        return $this->belongsTo('App\PlayGame');
    }
}
