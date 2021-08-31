<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;


/**
 * @api {post} /login Login
 * @apiDescription Entra no sistema
 * @apiName Login
 * @apiGroup Auth
 * @apiVersion 0.0.1
 *
 * @apiParam {String} email Email do usuário.
 * @apiParam {String} password Senha do usuário.
 *
 * @apiSuccess {String} name Nome do usuário.
 * @apiSuccess {String} gender Sexo do usuário. (M, F)
 * @apiSuccess {String} email Email do usuário.
 * @apiSuccess {String} avatar Avatar do usuário.
 * @apiSuccess {String} phone Celular do usuário.
 * @apiSuccess {String} origin Origem do registro do usuário.
 * @apiSuccess {String} status Status do usuário.
 * @apiSuccess {Timestamp} created_at Momento de criação do usuário.
 * @apiSuccess {Object} auth Informações de autenticação.
 * @apiSuccess {String} auth.access_token Token de acesso
 * @apiSuccess {String} auth.token_type Tipo do token
 * @apiSuccess {Number} auth.expires_in Tempo de validade do token
 */
Route::post('login', [AuthController::class, 'login']);

/**
 * @api {post} /register Registrar Usuário
 * @apiDescription Registra um novo usuário
 * @apiName Registrar
 * @apiGroup Auth
 * @apiVersion 0.0.1
 *
 * @apiParam {String} name Nome do usuário.
 * @apiParam {String} email Email do usuário.
 * @apiParam {String} password Senha do usuário.
 * @apiParam {String} [gender] Sexo do usuário. (M, F)
 * @apiParam {String} [avatar] Avatar do usuário.
 * @apiParam {String} [phone] Celular do usuário.
 *
 * @apiSuccess {String} name Nome do usuário.
 * @apiSuccess {String} gender Sexo do usuário. (M, F)
 * @apiSuccess {String} email Email do usuário.
 * @apiSuccess {String} avatar Avatar do usuário.
 * @apiSuccess {String} phone Celular do usuário.
 * @apiSuccess {String} origin Origem do registro do usuário.
 * @apiSuccess {String} status Status do usuário.
 * @apiSuccess {Timestamp} created_at Momento de criação do usuário.
 * @apiSuccess {Object} auth Informações de autenticação.
 * @apiSuccess {String} auth.access_token Token de acesso
 * @apiSuccess {String} auth.token_type Tipo do token
 * @apiSuccess {Number} auth.expires_in Tempo de validade do token
 */
Route::post('register', [AuthController::class, 'register']);

/**
 * @api {post} /forgot-password Esqueci Senha
 * @apiDescription Envia o link de redefinição de senha para o email do usuário
 * @apiName EsqueciSenha
 * @apiGroup Auth
 * @apiVersion 0.0.1
 *
 * @apiParam {String} email Email do usuário
 *
 * @apiSuccess {String} status Status da operação
 */
Route::post('/forgot-password', [AuthController::class, 'sendResetPasswordLink']);

Route::middleware('auth:api')->group(function () {

    /**
     * @api {get} /logout Revogar Token
     * @apiDescription Revoga o token de acesso do usuário
     * @apiName Logout
     * @apiGroup Auth
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiSuccess {String} response Mensagem de sucesso
     */
    Route::get('/logout', [AuthController::class, 'logout']);
});
