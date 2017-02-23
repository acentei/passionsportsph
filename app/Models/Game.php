<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    
    protected $table = 'game';
    
    protected $fillable = ['league_id', 'venue_id', 'hometeam_id', 'awayteam_id','bracket_id', 'match_date',
                           'match_time','slug','created_by','created_date','modified_by','modified_date',
                           'active', 'deleted','isFinished'];
    
    protected $primaryKey = "game_id";
    
    public function venue()
    {
         return $this->belongsTo('App\Models\Venue', 'venue_id', 'venue_id');
    }
    
    public function hometeam()
    {
        return $this->belongsTo('App\Models\Team', 'hometeam_id', 'team_id');
    }
    
    public function awayteam()
    {
        return $this->belongsTo('App\Models\Team', 'awayteam_id', 'team_id');
    }
              
    public function stats()
    {   
        return $this->hasMany('App\Models\TeamGameStats', 'game_id', 'game_id');
    }
    
    public function league()
    {   
        return $this->hasMany('App\Models\League', 'league_id', 'league_id');
    }
    
    public function bracket()
    {   
        return $this->hasMany('App\Models\Bracket', 'bracket_id', 'bracket_id');
    }
        
}
