<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public $timestamps = false;
    
    protected $table = 'league';
    
    protected $fillable = ['display_name','photo', 'slug','photo','isShowFgm','isShowFga','isShowFgp','isShow3pm','isShow3pa',
                           'isShow3pp','isShowFtm','isShowFta','isShowFtp','isShowReb','isShowOreb','isShowDreb','isShowStl',
                           'isShowBlk','isShowAst','isShowTov','hasBracket','hasPhotos','created_by', 'created_date', 'modified_by',
                           'modified_date','active','deleted'];
    
    protected $primaryKey = "league_id";
}
