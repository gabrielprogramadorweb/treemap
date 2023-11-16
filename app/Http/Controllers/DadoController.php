<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dado;

class DadoController extends Controller
{
    public function index()
    {
        $dados = Dado::all();
        return view('editar_dados', compact('dados'));

        
        
    }
   
    public function listarCidades()
    {
        $cidades = Dado::orderBy('populacao', 'desc')->get();
        return view('index', compact('cidades'));
    }

    public function update(Request $request)
    {
        $cidade = $request->input('cidade');
        $populacao = $request->input('populacao');

        Dado::where('cidade', $cidade)->update(['populacao' => $populacao]);

        return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
    }

    public function create(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'nova_cidade' => 'required|string',
            'nova_populacao' => 'required|integer',
        ]);

        // Obtém os dados do formulário
        $novaCidade = $request->input('nova_cidade');
        $novaPopulacao = $request->input('nova_populacao');

        // Cria um novo registro no banco de dados usando o modelo Dado
        Dado::create([
            'cidade' => $novaCidade,
            'populacao' => $novaPopulacao,
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
