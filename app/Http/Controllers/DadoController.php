<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dado;

class DadoController extends Controller
{
    public function index()
    {
        // Obtendo dados usando Eloquent
        $dados = Dado::select('cidade')->get();

        // Dados formatados para o Treemap
        $data = [['City', 'Parent', 'Population', 'Color']];
        $data[] = ['Global', null, 0, 0];

        foreach ($dados as $dado) {
            $data[] = [$dado->cidade, 'Global', (int)$dado->populacao, (int)$dado->populacao];
        }

        // Conversão para JSON
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);

        // Passar os dados para a visualização
        return view('editar_dados', compact('dados', 'json_data'));
    }

   

    public function update(Request $request)
    {

        $request->validate([
            'cidade' => 'required|string',
            'populacao' => 'required|numeric',
            'populacao_ano_passado' => 'required|numeric',
        ]);

        $cidade = $request->input('cidade');
        $populacao = $request->input('populacao');
        $populacao_ano_passado = $request->input('populacao_ano_passado');

        // Atualizar ambas população e população_ano_passado
        Dado::where('cidade', $cidade)->update([
            'populacao' => $populacao,
            'populacao_ano_passado' => $populacao_ano_passado,
        ]);

        return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
    }

    public function create(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'nova_cidade' => 'required|string',
            'nova_populacao' => 'required|integer|min:0',
            'populacao_ano_passado' => 'required|integer|min:0',
        ]);

        // Obtém os dados do formulário
        $novaCidade = $request->input('nova_cidade');
        $novaPopulacao = $request->input('nova_populacao');
        $populacaoAnoPassado = $request->input('populacao_ano_passado');

        // Cria um novo registro no banco de dados usando o modelo Dado
        Dado::create([
            'cidade' => $novaCidade,
            'populacao' => $novaPopulacao,
            'populacao_ano_passado' => $populacaoAnoPassado,
        ]);

        // Redireciona de volta para a página de edição ou outra página desejada
        return redirect('/editar-dados')->with('success', 'Dados adicionados com sucesso!');
    }

    public function editarDados()
    {
        // Lógica para obter $dados e $valorAtual
        // Certifique-se de definir $valorAtual com os dados que deseja passar para a visão

        return view('sua-visao', compact('dados', 'valorAtual'));
    }

    public function excluirDados(Request $request)
    {
        $cidadeExcluir = $request->input('cidade_excluir');

        // Valide se $cidadeExcluir está presente ou faça outras validações necessárias

        try {
            // Execute a lógica para excluir a cidade do banco de dados
            Dado::where('cidade', $cidadeExcluir)->delete();

            \Log::info('Cidade excluída com sucesso', ['cidade' => $cidadeExcluir]);

            // Após excluir, redirecione para a página de edição ou qualquer outra página desejada
            return redirect('/editar-dados')->with('status', 'Cidade excluída com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir cidade', ['cidade' => $cidadeExcluir, 'error' => $e->getMessage()]);

            // Trate o erro da maneira que fizer sentido para o seu aplicativo
            return redirect('/editar-dados')->with('error', 'Erro ao excluir cidade');
        }
    }
    
}
