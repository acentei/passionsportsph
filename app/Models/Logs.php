<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    public $timestamps = false;
    
    protected $table = '_logs';
    
    protected $fillable = ['message', 'timestamp', 'user'];
    
    protected $primaryKey = "log_id";
}
