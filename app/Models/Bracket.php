<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bracket extends Model
{
    public $timestamps = false;
    
    protected $table = 'bracket';
    
    protected $fillable = ['league_id','display_name','active','deleted'];
    
    protected $primaryKey = "bracket_id";    
 
    public function team()
    {
        return $this->hasMany('App\Models\Team', 'bracket_id', 'bracket_id');
    }
    
}
