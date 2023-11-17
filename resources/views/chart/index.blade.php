<?php
// Conectar ao banco de dados (substitua com suas credenciais)
$servername = 'localhost'; // Endereço do servidor MySQL
$username = 'root'; // Nome de usuário do MySQL
$password = ''; // Senha do MySQL
$dbname = 'dados'; // Nome do banco de dados

// Conexão ao banco de dados usando o modelo mysqli (não é Laravel ORM)
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    // Se a conexão falhar, encerra o script e exibe uma mensagem de erro
    die('Conexão falhou: ' . $conn->connect_error);
} else {
    // A conexão foi bem-sucedida
}

// Consulta SQL para obter dados da tabela 'dados'
$sql = 'SELECT cidade, populacao, populacao_ano_passado FROM dados';

// Executar a consulta e armazenar o resultado na variável $result
$result = $conn->query($sql);

// Formatar os dados para o Treemap
$data = [['City', 'Parent', 'Population', 'PreviousPopulation', 'Color']];

// Adicionar entrada para o Brasil com a população fixa
$data[] = ['Brasil', null, 203080756, 190700000, '#00FF00FF'];

// Este é um loop que percorre cada linha do resultado da consulta.
while ($row = $result->fetch_assoc()) {
    $populacao = (int) $row['populacao'];
    $populacao_ano_passado = (int) $row['populacao_ano_passado'];

    // Calcular a mudança percentual
    $mudanca_percentual = (($populacao - $populacao_ano_passado) / $populacao_ano_passado) * 100;

    // Adicione entrada ao array $data com a cor determinada
    $data[] = [$row['cidade'], 'Brasil', $populacao, $populacao_ano_passado, $mudanca_percentual];
}

// Fechar a conexão com o banco de dados
$conn->close();

// Converter dados para formato JSON usando a função json_encode do PHP
$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
?>

@extends('layouts.main')

@section('title', 'Gráfico Treemap')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Google Charts - Incluindo a biblioteca do Google Charts -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            // Carregar a biblioteca do Google Charts com o pacote 'treemap' quando estiver pronta
            google.charts.load('current', {
                'packages': ['treemap']
            });

            // Executar a função drawChart após o carregamento da biblioteca
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Usar dados dinâmicos obtidos do PHP convertidos para um objeto DataTable do Google Charts
                var data = google.visualization.arrayToDataTable(<?php echo $json_data; ?>);

                // Adicionar uma coluna com a cor intensificada com base na mudança percentual
            data.addColumn('string', 'Cor');
            for (var i = 1; i < data.getNumberOfRows(); i++) {
                var mudanca_percentual = data.getValue(i, 4);
                console.log("Mudança Percentual para " + data.getValue(i, 0) + ": " + mudanca_percentual);
                var cor = intensificarCor('#00FF00FF', mudanca_percentual);
                console.log("Cor para " + data.getValue(i, 0) + ": " + cor);
                data.setValue(i, 5, cor);
            }

                var tree = new google.visualization.TreeMap(document.getElementById('chart_div'));
                
                tree.draw(data, {
                    minColor: '#f00',
                    headerHeight: 50,
                    fontColor: 'black',
                    showScale: true,
                    generateTooltip: showFullTooltip,
                    maxColor: '#00FF00',
                    highlightOnMouseOver:true,
                    enableHighlight: true,
                    useWeightedAverageForAggregation: true,
                    generateTooltip: showFullTooltip
                    
                });
                
                tree.draw(data, options);

                function showFullTooltip(row, size, value) {
                    var cidade = data.getValue(row, 0);

                    if (cidade === 'Brasil') {
                        // Saída específica para o Brasil
                        var populacaoBrasil = data.getValue(row, 2);
                        var populacaoAnoPassadoBrasil = data.getValue(row, 3);

                        var mudanca_percentual_brasil = ((populacaoBrasil - populacaoAnoPassadoBrasil) /
                            populacaoAnoPassadoBrasil) * 100;

                        var formattedPopulacaoBrasil = populacaoBrasil.toLocaleString('pt-BR');
                        var formattedPopulacaoAnoPassadoBrasil = populacaoAnoPassadoBrasil.toLocaleString('pt-BR');
                        var formattedPorcentagemBrasil = mudanca_percentual_brasil ? (mudanca_percentual_brasil > 0 ? '+' :
                            '') + mudanca_percentual_brasil.toFixed(2) + '%' : 'N/A';

                        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
                            '<span style="font-family:Courier"><b>Brasil</b></span><br>' +
                            'População do Brasil | Censo 2022: ' + formattedPopulacaoBrasil +
                            '<br>População do Brasil | Censo 2010: ' + formattedPopulacaoAnoPassadoBrasil +
                            '<br>Mudança Percentual: ' + formattedPorcentagemBrasil + '' +
                            '</div>';
                    } else {
                        // Saída padrão para outras cidades
                        var populacaoCidade = data.getValue(row, 2);
                        var populacaoAnoPassadoCidade = data.getValue(row, 3);

                        var mudanca_percentual = ((populacaoCidade - populacaoAnoPassadoCidade) / populacaoAnoPassadoCidade) *
                            100;

                        var formattedPopulacaoCidade = populacaoCidade.toLocaleString('pt-BR');
                        var formattedPopulacaoAnoPassadoCidade = populacaoAnoPassadoCidade.toLocaleString('pt-BR');
                        var formattedPorcentagem = mudanca_percentual ? (mudanca_percentual > 0 ? '+' : '') + mudanca_percentual
                            .toFixed(2) + '%' : 'N/A';

                        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
                            '<span style="font-family:Courier"><b>' + cidade + '</b></span><br>' +
                            'População da Cidade | Censo 2022: ' + formattedPopulacaoCidade +
                            '<br>População da Cidade | Censo 2010: ' + formattedPopulacaoAnoPassadoCidade +
                            '<br>Mudança Percentual: ' + formattedPorcentagem + '' +
                            '</div>';
                    }
                }


                function formatarMudancaPercentual(percentual) {
                    var formattedPorcentagem = percentual.toFixed(2);
                    var cor = intensificarCor('#00FF00FF', percentual);
                    return '<span style="color:' + cor + ';">' + formattedPorcentagem + '%</span>';
                }

                function intensificarCor(cor, percentual) {
                    // Ajuste a intensidade da cor com base na mudança percentual
                    var intensidade = Math.abs(percentual) / 100;
                    var corIntensificada = intensificarCorRGB(cor, intensidade);
                    return corIntensificada;
                }

                function intensificarCorRGB(cor, intensidade) {
                    var r = parseInt(cor.substring(1, 3), 16);
                    var g = parseInt(cor.substring(3, 5), 16);
                    var b = parseInt(cor.substring(5, 7), 16);

                    r = Math.round(r * intensidade);
                    g = Math.round(g * intensidade);
                    b = Math.round(b * intensidade);

                    return '#' + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
                }
            }
        </script>
    </head>

    <body>
        <div class="container">
            <header class="text-center mt-4">
                <h1>Gráfico Treemap - População de Cidades do Brasil</h1>
                <h4>Segundo o <a
                        href="https://censo2022.ibge.gov.br/panorama/?utm_source=ibge&utm_medium=home&utm_campaign=portal"
                        target="_blank">IBGE</a></h4>
                        <div class="intensidade"><p >intensidade de cores conforme tamanho da população</p></div>
                        
            </header>

            <div id="chart_div" style="width: 100%; height: 400px;"></div>
        </div>

        <div class="d-flex justify-content-center align-items-center mt-3">
            <p class="text-center">
                Para voltar ao gráfico anterior, clique com o botão direito do mouse.
            </p>
        </div>

        <div class="d-flex justify-content-center align-items-center">
            <img src="/img/mouse.png" alt="Mouse" width="50">
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
@endsection
