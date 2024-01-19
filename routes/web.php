<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password'
    ];
    if (!Auth::attempt($credentials)){

        $user = User::create([
            'name'=>'Admin',
            'email'=>$credentials['email'],
            'password'=>\Illuminate\Support\Facades\Hash::make($credentials['password'])
        ]);
        Auth::login($user);

        if (Auth::attempt($credentials)){
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token',['create','update','delete']);
            $updateToken = $user->createToken('admin-token',['create','update']);
            $basicToken = $user->createToken('basic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken,
            ];
        }
    }
});
