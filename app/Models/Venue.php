<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    
class Venue extends Model
{
    public $timestamps = false;
    
    protected $table = 'venue';
    
    protected $fillable = ['display_name', 'address', 'created_by', 'created_date', 'modified_by',
                           'modified_date','active','deleted'];
    
    protected $primaryKey = "venue_id";
}
