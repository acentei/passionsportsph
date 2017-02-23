<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    public $timestamps = false;
    
    protected $table = 'game_regulation';
    
    protected $fillable = ['league_id','details'];
    
    protected $primaryKey = "regulation_id";
}
