<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = false;
    
    protected $table = 'player';
    
    protected $fillable = ['league_id', 'team_id', 'photo', 'slug', 'first_name', 'middle_name', 'last_name', 'jersey_number', 
                           'position', 'age', 'height', 'weight', 'pts', 'fgm', 'fga', 'pm3', 'pa3', 'ftm','fta','oreb','dreb','reb','ast','stl','blk','tov','created_by',
                           'statistical_point','defensive_statistical_point','created_date','modified_by','modified_date','active','deleted'];
    
    protected $primaryKey = 'player_id';
    
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id', 'team_id');
    }    
    
     /**
     *  get all team players in a game
     */
    public function gamestats()
    {
        return $this->belongsTo('App\Models\PlayerGameStats','player_id', 'player_id');
    }    
}



