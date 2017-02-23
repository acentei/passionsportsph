<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamGameStats extends Model
{
     public $timestamps = false;
    
    protected $table = 'team_game_stats';
    
    protected $fillable = ['game_id','team_id', 'pts', 'fgm', 'fga', '3pm', '3pa', 'ftm','fta',
                           'oreb','dreb','reb','ast','stl','blk','tov','1Q_score','2Q_score','3Q_score',
                           '4Q_score','1OT_score','2OT_score','3OT_score','4OT_score','5OT_score','total_score',
                           'created_by','created_date','modified_by','modified_date','active','deleted'];
    
    protected $primaryKey = 'teamstats_id';
    
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id', 'game_id');
    }
    
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id', 'team_id');
    }
}
