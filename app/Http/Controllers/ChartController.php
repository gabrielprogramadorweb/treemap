<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    public function index()
    {
        // Obter dados do banco de dados
        $data = DB::table('dados')->select('cidade', 'populacao', 'populacao_ano_passado')->get()->toArray();

        // Formatar dados para o Treemap
        $treemapData = [['City', 'Country', 'Population', 'PreviousPopulation', 'Color']];

        foreach ($data as $row) {
            $populacao = (int)$row->populacao;
            $populacao_ano_passado = (int)$row->populacao_ano_passado;

            // Ajuste na lógica para determinar a cor com base na condição populacao > populacao_ano_passado
            $cor = $populacao > $populacao_ano_passado ? '#00FF00FF' : '#FF0000FF';

            $treemapData[] = [$row->cidade, 'Brasil', $populacao, $populacao_ano_passado, $cor];
        }

        // Converter dados para formato JSON
        $json_data = json_encode($treemapData, JSON_UNESCAPED_UNICODE);

        return view('chart.index', compact('json_data'));
    }
    // ChartController.php

    public function columnChart()
    {
        return view('column-chart');
    }
}
