<?php

use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1','middleware' => 'cors'],function(){



Route::get('files',[FileController::class, 'index']);
Route::get('archivedfiles',[FileController::class, 'archivedfiles']);
Route::get('starredfiles',[FileController::class, 'favourites']);


Route::post('archivefile',[FileController::class, 'archiveFiles']); 
Route::post('unarchivefile',[FileController::class, 'unarchiveFiles']); 


Route::post('addtofavourites',[FileController::class, 'favouriteFiles']); 
Route::post('removefromfavourites',[FileController::class, 'removefavouriteFiles']); 


Route::post('/upload',[FileController::class, 'uploadfiles']);
Route::post('/uploader',[FileController::class, 'uploader']);


});

