<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::group(['namespace' => 'App\Http\Controllers\Sigma\Config', 'prefix' => 'configs'], function (){
    Route::get('/dir', 'SigmaConfigController@getDirectory');
    Route::get('/base', 'SigmaConfigController@checkBaseApi');
    Route::get('/ping', 'SigmaConfigController@checkAllApis');
});
*/

use App\Http\Controllers\Sigma\UpstreamController;

Route::group(['prefix' => 'crimson'],  function ( ) {
    /*
     * place any general crimson api stuff here e.g. checking status
     */

    //http://localhost:8000/api/crimson/v1/category/{payload=method, filters, data}
    //http://localhost:8000/api/crimson/v2/cardinal/v2/reviewer/{payload}

    Route::group(['prefix' => 'v1'],  function ( ) {
        /*
         * version based api stuff here
         */
        //EXAMPLES: 'category','charge', 'application',  'quote'
        //Route::apiResource('/category', UpstreamController::class);

        //http://sigma/categories?filter=MAIN -->GET Operation
        //http://sigma/appplications + {json payload} -->POST Operation


//        Route::apiResource('{entity}/{payload?}', UpstreamController::class);
    });
});
