<?php

use App\Http\Controllers\Api\V1\CompanyController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine CategoryResourceSuccess
 * @apiSuccess {Number} id ID da editora.
 * @apiSuccess {String} name Nome da editora.
 * @apiSuccess {String} description Descrição da editora.
 * @apiSuccess {String} icon URL da imagem do icone da editora.
 * @apiSuccess {Timestamp} created_at Momento de criação da editora.
 * @apiSuccess {Timestamp} updated_at Momento de atualização da editora.
 */

/**
 * @api {get} /companies Listar Editoras
 * @apiDescription Obtém a listagrem de todas as editoras no sistema
 * @apiName ListarEditoras
 * @apiGroup Editora
 * @apiVersion 0.0.1
 *
 * @apiUse CategoryResourceSuccess
 */
Route::get('/', [CompanyController::class, 'index'])->name('index');

Route::middleware('auth:api')->group(function () {
    /**
     * @api {post} /companies Cadastrar Editora
     * @apiDescription Cadastra uma nova editora no sistema
     * @apiName CadastrarEditora
     * @apiGroup Editora
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam {String} name Nome da editora.
     * @apiParam {String} description Descrição da editora.
     * @apiParam {File} icon URL da imagem do icone da editora.
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::post('/', [CompanyController::class, 'store'])->name('store');;

    /**
     * @api {get} /companies/:company Obter Editora
     * @apiDescription Obtém a editora no sistema pelo id
     * @apiName ObterEditora
     * @apiGroup Editora
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} company ID da editora
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::get('/{company}', [CompanyController::class, 'show'])->name('show');

    /**
     * @api {put} /companies/:company Editar Editora
     * @apiDescription Edita uma editora no sistema
     * @apiName EditarEditora
     * @apiGroup Editora
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} company ID da editora
     *
     * @apiParam {String} name Nome da editora.
     * @apiParam {String} description Descrição da editora.
     * @apiParam {File} icon URL da imagem do icone da editora.
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::put('/{company}', [CompanyController::class, 'update'])->name('update');

    /**
     * @api {delete} /companies/:company Remover Editora
     * @apiDescription Remove uma editora do sistema.
     * @apiName RemoverEditora
     * @apiGroup Editora
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} company ID da editora.
     */
    Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
});
