<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    public $timestamps = false;
    
    protected $table = 'about';
    
    protected $fillable = ['about_website'];
    
    protected $primaryKey = "about_id";
}
