<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerCareerStats extends Model
{
    public $timestamps = false;
    
    protected $table = 'player_career_stats';
    
    protected $fillable = ['player_id', 'pts','reb','ast','stl','blk','fgp','pm3','ftm','ftp','created_by','created_date',
                           'modified_by','modified_date','active','deleted'];
    
    protected $primaryKey = 'careerstats_id';
    
    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'player_id', 'player_id');
    }
}
