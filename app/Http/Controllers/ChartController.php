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
        $data = DB::table('dados')->select('cidade', 'populacao')->get()->toArray();

        // Formatar dados para o Treemap
        $treemapData = [['City', 'Country', 'Population', 'Color']];

        foreach ($data as $row) {
            $treemapData[] = [$row->cidade, 'Global', (int)$row->populacao, (int)$row->populacao];
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
