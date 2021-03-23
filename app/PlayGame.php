<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayGame extends Model
{
    protected $table = 'playgame';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function Boardgame()
    {
        return $this->hasMany('App\Boardgame','ref_playgame_id');
    }
}
