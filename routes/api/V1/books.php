<?php

use App\Http\Controllers\Api\V1\BooksController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine CategoryResourceSuccess
 * @apiSuccess {Number} id ID da livro.
 * @apiSuccess {String} name Nome da livro.
 * @apiSuccess {String} description Descrição da livro.
 * @apiSuccess {String} icon URL da imagem do icone da livro.
 * @apiSuccess {Timestamp} created_at Momento de criação da livro.
 * @apiSuccess {Timestamp} updated_at Momento de atualização da livro.
 */

/**
 * @api {get} /companies Listar Livros
 * @apiDescription Obtém a listagrem de todas as livros no sistema
 * @apiName ListarLivros
 * @apiGroup Livro
 * @apiVersion 0.0.1
 *
 * @apiUse CategoryResourceSuccess
 */
Route::get('/', [BooksController::class, 'index'])->name('index');

Route::middleware('auth:api')->group(function () {
    /**
     * @api {post} /companies Cadastrar Livro
     * @apiDescription Cadastra um nova livro no sistema
     * @apiName CadastrarLivro
     * @apiGroup Livro
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam {String} name Nome da livro.
     * @apiParam {String} description Descrição da livro.
     * @apiParam {File} icon URL da imagem do icone da livro.
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::post('/', [BooksController::class, 'store'])->name('store');;

    /**
     * @api {get} /companies/:book Obter Livro
     * @apiDescription Obtém a livro no sistema pelo id
     * @apiName ObterLivro
     * @apiGroup Livro
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} book ID da livro
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::get('/{book}', [BooksController::class, 'show'])->name('show');

    /**
     * @api {put} /companies/:book Editar Livro
     * @apiDescription Edita um livro no sistema
     * @apiName EditarLivro
     * @apiGroup Livro
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} book ID da livro
     *
     * @apiParam {String} name Nome da livro.
     * @apiParam {String} description Descrição da livro.
     * @apiParam {File} icon URL da imagem do icone da livro.
     *
     * @apiUse CategoryResourceSuccess
     */
    Route::put('/{book}', [BooksController::class, 'update'])->name('update');

    /**
     * @api {delete} /companies/:book Remover Livro
     * @apiDescription Remove um livro do sistema.
     * @apiName RemoverLivro
     * @apiGroup Livro
     * @apiVersion 0.0.1
     *
     * @apiUse ApiAccessToken
     *
     * @apiParam (URL) {Number} book ID da livro.
     */
    Route::delete('/{book}', [BooksController::class, 'destroy'])->name('destroy');
});
