<?php

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


/*
|--------------------------------------------------------------------------
| Rotas de autenticação
|--------------------------------------------------------------------------
|
| POST /oauth/token
| POST /forgot-password
| GET  /logout
|
*/
Route::prefix('auth')->name('auth.')->group(base_path('routes/api/V1/auth.php'));

Route::prefix('categories')->name('categories.')->group(base_path('routes/api/V1/categories.php'));
Route::prefix('books')->name('books.')->group(base_path('routes/api/V1/books.php'));
Route::prefix('companies')->name('companies.')->group(base_path('routes/api/V1/companies.php'));

