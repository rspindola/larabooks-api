<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Api\V1\AuthRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Password, DB};

class AuthController extends Controller
{
    private $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Autenticação do usuãrio atraves do passport.
     *
     * @method POST
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        // validando os campos que vem no formulário pelo metodo padrão do lumen
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // Obtendo os dados
            $data = $request->only(['email', 'password']);

            // tentando fazer o login
            $result = $this->repository->authenticate($data);

            // retornando sucesso
            return response()->json($result);
        } catch (AuthorizationException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        }
    }

    /**
     * Autenticação do usuãrio atraves do passport.
     *
     * @method POST
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        // validando os campos que vem no formulário pelo metodo padrão do lumen
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // Obtendo os dados
            $data = $request->only(['name', 'email', 'password']);

            // tentando fazer o login
            $result = $this->repository->register($data);

            // retornando sucesso
            return response()->json($result);
        } catch (AuthorizationException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        }
    }

    /**
     * Realiza logout
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = 'Token revogado com sucesso!';
        return response($response, 200);
    }

    /**
     * Envia o link de redefinição de senha para o email do usuário
     *
     * @param SendResetPasswordLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetPasswordLink(Request $request)
    {
        $email = $request->email;

        // Verificando se o usuário já possui um token gerado em menos de 5 minutos.
        $hasRecentToken = DB::table('password_resets')->where('email', $email)
            ->where('created_at', '>=', now()->subMinutes(5)->toDateTimeString())->exists();

        if ($hasRecentToken) {
            abort(400, 'Você solicitou a redefinição de senha recentemente, verifique seu email ou aguarde pelo menos 5 minutos para solicitar novamente.');
        }

        // Se o usuário não acessou antes....
        if (!User::whereEmail($email)->exists()) {
            // TODO Sincronizar os dados do usuário ASPIN na base local
            // Se o usuário não existir na ASPIN, lançar um erro.
        }

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return trans($response);
        } else {
            abort(500, "Erro ao enviar link de redefinição de senha!");
        }
    }
}
