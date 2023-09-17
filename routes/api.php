<?php


use App\Http\Controllers\AgentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperationController;

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

// Route::get('agent/{agent}', [AgentController::class,'show']);
// Route::resource('agent', AgentController::class);    


// Route::middleware(['auth:sanctum'])->group(function () {
// Route::get('agent/search/{agent}', [AgentController::class,'search']);    
// });

Route::post('client', [ClientController::class,'store']);

// Agent
Route::get('agent', [AgentController::class,'index']);  
Route::get('agent/{agent}', [AgentController::class,'show']);  
Route::get('agent/search/{agent}', [AgentController::class,'search']);

// Client
Route::get('client', [ClientController::class,'index']);
Route::get('clientCompte', [ClientController::class,'clientCompte']);
Route::get('client/{client}', [ClientController::class,'show']);  
Route::get('client/search/{client}', [ClientController::class,'search']);

// Compte
Route::get('compte', [CompteController::class,'index']);  

Route::get('compte/{compte}', [CompteController::class,'show']);  
Route::get('compte/search/{compte}', [CompteController::class,'search']);

Route::get('compteClient/{Client}', [CompteController::class,'compteClient']); 


//User
Route::get('user', [UserController::class,'index']);  
Route::get('user/{user}', [UserController::class,'show']);  
Route::get('user/search/{user}', [UserController::class,'search']);


//Operatins
Route::get('operation', [OperationController::class,'index']);  
Route::get('operation/agent/{id}', [OperationController::class,'agentOperation']);  


//
Route::post('register', [AuthController::class,'register'])->name('user.register');
Route::post('login', [AuthController::class,'login'])->name('user.login');


Route::post('agent', [AgentController::class,'store']);  
Route::put('agent/{agent}', [AgentController::class,'update']); 
Route::delete('agent/{agent}', [AgentController::class,'destroy']);

Route::post('client', [ClientController::class,'store']);
Route::put('client/{client}', [ClientController::class,'update']);  
Route::delete('client/{client}', [ClientController::class,'destroy']); 
      
Route::post('compte', [CompteController::class,'store']);
Route::post('compte/depot', [CompteController::class,'depotCompte'])->name('depotCompte');  
Route::post('compte/retrait', [CompteController::class,'retraitCompte'])->name('retraitCompte');  
Route::post('compte/cloture', [CompteController::class,'clotureCompte'])->name('clotureCompte');  
Route::put('compte/{compte}', [CompteController::class,'update']);  
Route::delete('compte/{compte}', [CompteController::class,'destroy']); 

Route::post('operation/recherche', [OperationController::class,'rechercheOperation'])->name('rechercheOperation');  
Route::post('operation/notification', [OperationController::class,'notificationOperation'])->name('notificationOperation');  


Route::post('user', [UserController::class,'store']);
Route::put('user/{user}', [UserController::class,'update']);  
Route::delete('user/{user}', [UserController::class,'destroy']); 
Route::post('user/edit', [UserController::class,'edit'])->name('user.edit');


Route::group(['middleware'=>['auth:sanctum']], function () {
    
    Route::get('agent', [AgentController::class,'index']);  

    Route::post('agent', [AgentController::class,'store']);  
    Route::put('agent/{agent}', [AgentController::class,'update']); 
    Route::delete('agent/{agent}', [AgentController::class,'destroy']);
    
   // Route::post('client', [ClientController::class,'store']);
    Route::put('client/{client}', [ClientController::class,'update']);  
    Route::delete('client/{client}', [ClientController::class,'destroy']); 
          
    Route::post('compte', [CompteController::class,'store']);
    Route::put('compte/{compte}', [CompteController::class,'update']);  
    Route::delete('compte/{compte}', [CompteController::class,'destroy']); 
    
    Route::post('user', [UserController::class,'store']);
    Route::put('user/{user}', [UserController::class,'update']);  
    Route::delete('user/{user}', [UserController::class,'destroy']); 


});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
// });