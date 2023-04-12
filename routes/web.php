<?php

use App\Models\Link;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/{link}', function(Link $link){

    $ip = env('APP_ENV') == 'local' ? '128.14.95.255' : request()->ip();

    $response = Http::get('http://ip-api.com/json/' . $ip)
        ->json();

    $link->visits()->create([
        'country' => $response['country'],
    ]);

    return redirect($link->url);

})->name('shortlink');

Route::get('prueba', function(){
    //Recuperar ultimo link
    $link = Link::latest()->first();
    
    return $link;
});

