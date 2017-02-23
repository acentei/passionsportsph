<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $timestamps = false;
    
    protected $table = 'game_gallery';
    
    protected $fillable = ['league_id', 'photo', 'caption', 'created_by', 'created_date', 'modified_by', 'modified_date', 'active', 'deleted'];
    
    protected $primaryKey = "gallery_id";
}
