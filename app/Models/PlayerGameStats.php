<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerGameStats extends Model
{
    public $timestamps = false;
    
    protected $table = 'player_game_stats';
    
    protected $fillable = ['game_id','player_id', 'pts', 'fgm', 'fga', 'pm3', 'pa3', 'ftm','fta',
                           'oreb','dreb','reb','ast','stl','blk','tov','created_by',
                           'created_date','modified_by','modified_date','active','deleted'];
    
    protected $primaryKey = 'gamestats_id';
    
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id', 'game_id');
    }
    
    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'player_id', 'player_id');
    }
    
    public function perGameStats()
    {
        return $this->hasMany('App\Models\Game','game_id','game_id');       
    }
}
