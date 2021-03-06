<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Ui\FileController;
use App\Http\Controllers\Ui\HomeController;
use App\Http\Controllers\Ui\WelcomeController;
use App\Http\Controllers\Ui\PsController;
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

$proxy_url    = env('PROXY_URL');
$proxy_schema = env('PROXY_SCHEMA');

if(!empty($proxy_url)) {
    url()->forceRootUrl($proxy_url);
}
if(!empty($proxy_schema)) {
    url()->forceScheme($proxy_schema);
}

Route::get('/', [WelcomeController::class, 'index'])
    ->name('welcome');

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

$nationalIdRegex = '^(?:([a-zA-Z0-9]+)(\/|-)?)+$';

Route::get('/ps', [PsController::class, 'getById'])
    ->name('ps.getById');
Route::put('/ps/{psId}', [PsController::class, 'update'])
    ->name('ps.update')->where('psId', $nationalIdRegex);

Route::get('/files', [FileController::class, 'index'])
    ->name('files.index');
Route::post('/files', [FileController::class, 'upload'])
    ->name('files.upload');

Route::get('/auth/{provider}/redirect', [LoginController::class, 'redirectToProvider'])
    ->name('auth.redirect');
Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
