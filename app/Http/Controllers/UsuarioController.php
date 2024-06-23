<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Extensions\CPFValidator;
use App\Extensions\CustomHasher;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecover;
use App\Models\Empresa;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Obtém informações de um usuário com base em seu ID.
     *
     * Esta função recebe um ID de usuário e retorna informações sobre o usuário
     * da tabela 'usuarios' do banco de dados. O ID do usuário é fornecido como
     * parte da URI da requisição e é usado como um parâmetro de consulta na
     * busca na tabela de usuários. O resultado é retornado como um conjunto de
     * dados JSON.
     *
     * @param  \Illuminate\Http\Request  $request  A requisição HTTP recebida.
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON com informações do usuário.
     */
    public function index(Request $request)
    {
        //Obtém a query string completa
        $queryString = $request->getQueryString();
        $userHash = rtrim($queryString, '=');  // retira um = que fica automaticamente ao final da querystring

        // Realiza uma consulta na tabela 'usuarios' para obter informações do usuário com base no ID fornecido
        $userInfo = DB::table('usuarios')->where('id', $userHash)->get();

        // Retorna os dados do usuário como uma resposta JSON
        return response()->json($userInfo);
    }

    /**
     * Retorna o ID do usuário se as credenciais estiverem corretas.
     *
     * @param  \Illuminate\Http\Request  $request  A requisição HTTP recebida.
     * @param  string  $userData  Os dados do usuário no formato "cpf_cnpj:senha".
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON com o ID do usuário ou uma mensagem de erro.
     */
    public function show(Request $request, string $userData)
    {
        //Obtém a query string completa
        $queryString = $request->getQueryString();
        $userHash = rtrim($queryString, '=');  // retira um = que fica automaticamente ao final da querystring

        // Divide os dados do usuário em CPF/CNPJ e senha
        $userDataArray = explode(':', $userData);
        $userCpf = $userDataArray[0];
        $password = $userDataArray[1];

        // Formata o CPF ou CNPJ para fazer a busca no banco de dados
        $userCpf = CPFValidator::formatarCpfOuCnpj($userCpf);

        // Busca o usuário no banco de dados pelo CPF/CNPJ fornecido
        $user = DB::table('usuarios')->where('cpf_cnpj', $userCpf)->get()->first();

        // Verifica se o usuário foi encontrado
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Obtém a senha criptografada do usuário
        $hashedPassword = $user->senha;

        // Verifica se a senha fornecida é correta
        $correctPassword = Hash::check($password, $hashedPassword);

        // Verifica se o usuário e a senha estão corretos e retorna o ID do usuário
        if ($user && $correctPassword) {
            return response()->json(['id' => $user->id]);
        } else {
            return response()->json(['error' => 'Senha incorreta'], 401);
        }
    }

    /**
     * Registra um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request  A requisição HTTP contendo os dados do usuário a ser registrado.
     * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando sucesso ou falha ao registrar o usuário.
     */
    public function store(Request $request, string $userHash)
    {
        // Valida os dados da requisição
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf_cnpj' => 'required',
            'senha' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'endereco' => 'required',
            'cep' => 'required',
            'nascimento' => 'required',
            'empresario' => 'required|numeric|between:0,1',
            'nome_da_empresa' => 'nullable'
        ]);

        // Verifica se a validação falhou e retorna os erros
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Valida e formata o CPF/CNPJ
        $cpf = CPFValidator::validarCpfOuCnpj($request->input('cpf_cnpj'));
        if (!$cpf) {
            // Se o CPF ou CNPJ for inválido, retorna uma mensagem de erro
            return response()->json(['errors' => 'CPF ou CNPJ inválido'], 422);
        }

        // Gera um ID único para o usuário com base no CPF/CNPJ
        $cpf = $request->input('cpf_cnpj');
        $userId = CustomHasher::hashId($cpf);

        // Cria um novo usuário no banco de dados
        $created = Usuario::create([
            'id' => $userId,
            'name' => $request->input('name'),
            'cpf_cnpj' => $request->input('cpf_cnpj'),
            'senha' => Hash::make($request->input('senha')),
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'endereco' => $request->input('endereco'),
            'cep' => $request->input('cep'),
            'nascimento' => $request->input('nascimento'),
            'empresario' => $request->input('empresario'),
            'nome_da_empresa' => $request->input('nome_da_empresa')
        ]);

        // Retorna uma resposta JSON indicando sucesso ou falha ao registrar o usuário
        if ($created) {
            return response()->json(['success' => 'Usuário registrado com sucesso'], 201);
        }
        return response()->json(['errors' => 'Houve algum erro ao registrar usuário'], 422);
    }

    /**
     * Atualiza os dados de um usuário com base no ID fornecido.
     *
     * Esta função recebe uma requisição contendo um JSON com os dados a serem
     * atualizados para um usuário específico. Os dados são atualizados na
     * tabela 'usuarios' do banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request   A requisição HTTP contendo o JSON com os dados a serem atualizados.
     * @param  string                      $userId    O ID do usuário a ser atualizado.
     * @param  string                      $userHash  Um hash usado para verificar as permissões do usuário.
     * @return \Illuminate\Http\JsonResponse          Uma resposta JSON indicando sucesso ou falha na atualização dos dados.
     */
    public function update(Request $request, string $userId, string $userHash)
    {
        // Obtém o JSON da requisição
        $json = $request->getContent();

        // Decodifica o JSON em um array associativo
        $dados = json_decode($json, true);

        // Verifica se o JSON foi decodificado com sucesso
        if ($dados === null) {
            // Se houver algum erro na decodificação do JSON
            return response()->json(['errors' => 'Erro ao decodificar o JSON'], 400);
        }

        // Itera sobre as chaves e valores do array associativo
        foreach ($dados as $chave => $valor) {
            // Se o valor a ser alterado for a senha, uma operação de criptografia deve ser feita
            if ($chave == "senha") {
                $novaSenha = Hash::make($valor);  // A nova senha é criptografada
                // Atualiza os dados do usuário na tabela 'usuarios'
                $updated = DB::table('usuarios')->where('id', $userId)->update(["senha" => $novaSenha]);
                $valor = "";
                $novaSenha = "";
            } else {
                // Atualiza os dados do usuário na tabela 'usuarios'
                $updated = DB::table('usuarios')->where('id', $userId)->update([$chave => $valor]);
            }
        }

        // Retorna uma resposta JSON indicando sucesso na atualização dos dados
        return response()->json(['success' => 'Dados atualizados com sucesso'], 200);
    }

    /**
     * Exclui um usuário e suas associações de empresas.
     *
     * @param  string  $identificacaoUsuario  O ID do usuário ou seu CPF/CNPJ.
     * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando o resultado da operação.
     */
    public function destroy(string $identificacaoUsuario, string $userHash)
    {
        // Obtém o ID do usuário com base no ID ou CPF/CNPJ fornecido
        $userId = DB::table('usuarios')
            ->where('id', $identificacaoUsuario)
            ->orWhere('cpf_cnpj', CPFValidator::formatarCpfOuCnpj($identificacaoUsuario))
            ->pluck('id');

        // Verifica se o usuário possui empresas associadas e exclui essas empresas
        $possuiEmpresa = DB::table('empresas')->where('usuario_id', $userId)->exists();
        if ($possuiEmpresa) {
            $deletedEmpresa = DB::table('empresas')->where('usuario_id', $userId)->delete();
        }

        // Exclui o usuário da tabela 'usuarios'
        $deleted = DB::table('usuarios')->where('id', $userId)->delete();

        // Retorna uma resposta JSON indicando o resultado da operação
        if ($deleted) {
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        }
        return response()->json(['errors' => 'Erro ao deletar usuário'], 400);
    }

    /**
     * Recupera o email do usuário com base no CPF ou CNPJ fornecido e envia um email de recuperação de senha.
     *
     * @param  \Illuminate\Http\Request  $request  A requisição HTTP.
     * @param  string  $cpfCnpj  O CPF ou CNPJ do usuário a ser recuperado.
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON contendo o email do usuário ou uma mensagem de erro.
     */
    public function sendRecoverEmail(Request $request, string $cpfCnpj)
    {
        // Email do remetente (sistema)
        $emailCronos = 'cronos@hotmail.com';

        // Formata o CPF ou CNPJ
        $cpfCnpj = CPFValidator::formatarCpfOuCnpj($cpfCnpj);

        // Busca o email do usuário com base no CPF ou CNPJ
        $userEmail = DB::table('usuarios')->where('cpf_cnpj', $cpfCnpj)->value('email');
        $user = DB::table('usuarios')->where('cpf_cnpj', $cpfCnpj)->first();

        // Verifica se o usuário foi encontrado
        if ($user) {
            $userName = $user->name;
            // Se o email foi encontrado, envia email para recuperação
            try {
                // Gera o código que será enviado no email para confirmação
                $codigoConfirmacao = Str::random(6);
                $updated = DB::table('usuarios')->where('id', $user->id)->update(["codigo_confirmacao" => $codigoConfirmacao]);

                Mail::to($userEmail)->send(new PasswordRecover([
                    'fromName' => 'Cronos',
                    'fromEmail' => 'cronos@gmail.com',
                    'codigoConfirmacao' => $codigoConfirmacao,
                    'subject' => 'Recuperação de Senha',
                    'userName' => $userName,
                ]));
                return response()->json(['success' => 'E-mail de recuperação enviado com sucesso']);
            } catch (\Exception $e) {
            // Se ocorrer um erro ao enviar o email
            return response()->json(['error' => 'Erro ao enviar e-mail de recuperação'], 500);
        }
    }

        // Se o email não foi encontrado ou o usuário não existe, retorna uma mensagem de erro em formato JSON
        return response()->json(['error' => 'O CPF/CNPJ informado não existe no banco de dados']);
    }

    /**
     * Atualiza a senha do usuário com base no ID do usuário e a nova senha fornecida.
     *
     * @param  \Illuminate\Http\Request  $request  A requisição HTTP.
     * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando sucesso ou falha na atualização da senha.
     */
    public function recoverPassword(Request $request)
    {
        // Valida os dados da requisição
        $validator = Validator::make($request->all(), [
            'nova_senha' => 'required',
            'codigo_confirmacao' => 'required',
        ]);

        // Verifica se a validação falhou e retorna os erros
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtém o JSON da requisição
        $json = $request->getContent();

        // Decodifica o JSON em um array associativo
        $dados = json_decode($json, true);

        // Verifica se o JSON foi decodificado com sucesso
        if ($dados === null) {
            // Se houver algum erro na decodificação do JSON
            return response()->json(['errors' => 'Erro ao decodificar o JSON'], 400);
        }

        // Obtém os dados do usuário a partir do JSON
        $novaSenha = $dados['nova_senha'];
        $codigoUsuario = $dados['codigo_confirmacao'];
        $userId = DB::table('usuarios')->where('codigo_confirmacao', $codigoUsuario)->get('id')->first();
        var_dump($userId);
        $codigoConfirmacao = DB::table('usuarios')->where('id', $userId->id)->get('codigo_confirmacao')->first();

        if ($codigoConfirmacao->codigo_confirmacao == $codigoUsuario){
            // Criptografa a nova senha
            $novaSenha = Hash::make($novaSenha);

            // Atualiza a senha do usuário na tabela 'usuarios'
            $updated = DB::table('usuarios')->where('id', $userId->id)->update(['senha' => $novaSenha]);
        }
        else {
            return response()->json(['errors' => 'Código de confirmação inválido'], 400);
        }

        // Verifica se a atualização foi bem-sucedida
        if (!$updated) {
            return response()->json(['errors' => 'Erro ao trocar senha'], 400);
        }

        // Retorna uma resposta JSON indicando sucesso na atualização dos dados
        return response()->json(['success' => 'Senha trocada com sucesso'], 200);
    }

    public function partnerCompanies(Empresa $empresa, Usuario $hash)
    {
        return $empresa->usuariosParceiros;
    }
}

