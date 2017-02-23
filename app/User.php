<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    
    protected $table = 'account';
    
    protected $fillable = ['username', 'email', 'password', 'first_name', 'middle_name', 'last_name',
                           'account_type','created_by','created_date','modified_by','modified_date',
                           'active','deleted'];
    
    protected $primaryKey = "account_id";
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function league()
    {
        return $this->belongsTo('App\Models\League', 'handled_league', 'league_id');
    }
}
