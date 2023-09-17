<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Models\Client;
use App\Models\Compte;
use App\Models\Operation;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('register', [AuthController::class,'register'])->name('user.register');

// Route::get('/', function () {

//     $clients = Client::all();
//     return view('ess',[
//         'clients'=> $clients
//     ]);

    // $comptes = Compte::find(28);
    // return $comptes->client->nom;

    // $client = Client::all();

    // foreach($client as $cl){
    //     echo $cl->prenom. '<hr>';
    //     foreach($cl->comptes as $clc){
    //     echo $clc->total . '<br>';
    //     }
    // }



// });

Route::get('welcome',function(){
    //return 'libenga_api';

    $acienneOperation = Operation::where([
        ['compte_id','=',27],
        ['Type_Operation','=','retrait'],
    ])->count();

    return response()->json([
        "nombre " => $acienneOperation,
        "statut" => 200
    ]);
});