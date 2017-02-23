<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'LeagueController@index');

Route::get('carousel', function () {
    return view('pages.index');
});


Route::get('welcome', function () {
    return view('pages.welcome.index');
});

View::creator('layout.navi', function($view)
{ 
    
    //check if there is a moderator logged in with restrictions
    if(Auth::user())        
    {   
        if((Auth::user()->account_type == "Moderator") && (Auth::user()->handled_league != 0))
        {
            $league = App\Models\League::where('league_id',Auth::user()->handled_league)
                                ->where("active", 1)
                                ->where("deleted", 0)
                                ->get();        
        }
        else
        {            
            $league = App\Models\League::where("active", 1)
                                ->where("deleted", 0)
                                ->get();
        }
    }
    else
    {            
        $league = App\Models\League::where("active", 1)
                                ->where("deleted", 0)
                                ->get();
    }    
    
    
    $selectedLeague = App\Models\League::where("league_id",Session::get('selectedLeague'))
                                        ->where("active", 1)
                                        ->where("deleted", 0)
                                        ->get();    
        
    //game details and player details
    $gameDetails = App\Models\Game::with('hometeam','awayteam','stats')
                                ->where('deleted',0)
                                ->where('active',1)
                                ->orderBy('match_date','ASC')
                                ->orderBy('match_time','ASC')
                                ->where('league_id',Session::get("selectedLeague"))
                                ->get();

    $view->with(['game' => $gameDetails,'league' => $league,'selLeg' => $selectedLeague]);
    
});
   

//----------------- RESOURCE ---------------//
    Route::resource('league', 'LeagueController');
    Route::resource('team', 'TeamController');
    Route::resource('player', 'PlayerController');
    Route::resource('venue', 'VenueController');
    Route::resource('schedule', 'ScheduleController');
    Route::resource('gallery', 'GalleryController');
    Route::resource('game', 'GameController');
    Route::resource('about', 'AboutController');
    Route::resource('moderator', 'ModeratorController');
    Route::resource('standing', 'LeagueStandingController');
    Route::resource('bracket', 'TeamBracketController');
    Route::resource('select-bracket', 'SelectBracketController');
    Route::resource('stat-leader', 'StatLeaderController');

    Route::get('editStats/{id}', 
        [
            'as' => 'editStats',
            'uses' => 'GameController@editStats'
        ]);

    Route::patch('updateStats/{id}', 
                 [
            'as' => 'updateStats',
            'uses' => 'GameController@updateStats'
        ]);

    Route::get('deactivate/{id}', 
        [
            'as' => 'deactivate',
            'uses' => 'ModeratorController@deactivate'
        ]);

    Route::get('activate/{id}', 
        [
            'as' => 'activate',
            'uses' => 'ModeratorController@activate'
        ]);


//------------------ AUTHENTICATION ------------------//    
Route::post('auth/authenticate', 'Auth\AuthController@authenticate');

/* LOGOUT ROUTE */
Route::get('auth/logout', 'Auth\AuthController@logout');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
   // 'webapiauth' =>'Auth\WebApiAuthController'
]);



