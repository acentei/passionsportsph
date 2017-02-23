<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = false;
    
    protected $table = 'team';
    
    protected $fillable = ['league_id','bracket_id','photo','display_name', 'nickname', 'wins', 'losses', 'league_status', 'slug',
                           'created_by','created_date','modified_by','modified_date','active',
                           'deleted'];
    
    protected $primaryKey = 'team_id';


    public function league()
    {
        return $this->belongsTo('App\Models\League', 'league_id', 'league_id');
    }
    
    /**
     *  get all team players 
     *  format: (Model,foreign key,local key)
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player','team_id', 'team_id');
    }     
   
    
    public function teamStats()
    {
        return $this->belongsTo('App\Models\TeamGameStats', 'team_id', 'team_id');
    }
    
    public function bracket()
    {
        return $this->belongsTo('App\Models\Bracket', 'bracket_id', 'bracket_id');
    }
    
    public function getTeamBracketAttribute()
    {        
        if($this->league->hasBracket == 1)
        {
            $this->brac = $this->bracket->display_name;

            return "$this->display_name( Bracket $this->brac )";    
        }
        else
        {
            return "$this->display_name";
        }
    }
    
}
