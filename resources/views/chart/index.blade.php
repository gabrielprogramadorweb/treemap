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
$sql = 'SELECT cidade, populacao FROM dados';

// Executar a consulta e armazenar o resultado na variável $result
$result = $conn->query($sql);

// Formatar os dados para o Treemap
$data = [['City', 'Parent', 'Population', 'Color']];

// Adicionar entrada para o Brasil com a população fixa
$data[] = ['Brasil', null, 203080756, 203080756];

// Isso verifica se o resultado da consulta ($result) contém mais de zero linhas. Em outras palavras, está verificando se há algum resultado retornado pela consulta, indicando que há registros no banco de dados.
if ($result->num_rows > 0) {
    // Este é um loop que percorre cada linha do resultado da consulta. fetch_assoc() é um método que retorna uma linha do conjunto de resultados como uma matriz associativa (um array onde os índices são nomes das colunas do banco de dados). A cada iteração do loop, a variável $row contém os dados de uma linha da tabela.
    while ($row = $result->fetch_assoc()) {
        //  Nesta linha, estamos adicionando uma nova entrada ao array $data, que será usado posteriormente para criar o gráfico Treemap. Cada entrada do array representa uma linha de dados no formato desejado para o Treemap. Os elementos do array correspondem às colunas da tabela no banco de dados.
        $data[] = [$row['cidade'], 'Brasil', (int) $row['populacao'], (int) $row['populacao']];
        // $row['cidade']: Representa o nome da cidade, obtido da coluna 'cidade' no banco de dados.
        // 'Brasil': Indica que a cidade pertence ao Brasil. Esta é uma entrada fixa, pois todas as cidades estão sendo agrupadas sob o Brasil no Treemap.
        // (int) $row['populacao']: Representa a população da cidade, convertida para um número inteiro. Isso é importante para garantir que os valores numéricos sejam tratados corretamente no gráfico.
    }
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
        <script type="text/javascript" src="http://www.gstatic.com/charts/loader.js"></script>
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

                // Exibir os dados no console para depuração
                console.log(data);

                // Criar um objeto TreeMap e associá-lo ao elemento HTML com o id 'chart_div'
                tree = new google.visualization.TreeMap(document.getElementById('chart_div'));

                // Desenhar o TreeMap com as opções de estilo
                tree.draw(data, {
                    minColor: '#f00',
                    midColor: '#00FF00FF ',
                    maxColor: '#0d0',
                    headerHeight: 20, // Ajuste conforme necessário
                    fontColor: 'black',
                    showScale: true,
                    generateTooltip: showFullTooltip
                });

                // Função para gerar o conteúdo do tooltip personalizado
                function showFullTooltip(row, size, value) {
                    // Se o mouse estiver sobre o Brasil (linha 0)
                    if (row === 0) {
                        // Obtém o valor da população do Brasil (posição 2 na tabela)
                        var populacaoBrasil = data.getValue(0, 2);

                        // Formata o valor usando pontos para milhares e vírgulas para decimais
                        var formattedPopulacaoBrasil = populacaoBrasil.toLocaleString('pt-BR');

                        // Retorna o conteúdo HTML do tooltip personalizado para o Brasil
                        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
                            '<span style="font-family:Courier"><b>' + data.getValue(row, 0) + '</b></span><br>' +
                            'População do Brasil: ' + formattedPopulacaoBrasil +
                            '</div>';
                    }

                    // Se o mouse estiver sobre uma cidade (outra linha)
                    else {
                        // Obtém o valor da população da cidade
                        var populacaoCidade = data.getValue(row, 3);

                        // Obtém o valor da população do Brasil (posição 2 na tabela)
                        var populacaoBrasil = data.getValue(0, 2);

                        // Calcula a porcentagem da população da cidade em relação à população do Brasil
                        var porcentagem = (populacaoCidade / populacaoBrasil) * 100;

                        // Formata o valor usando pontos para milhares e vírgulas para decimais
                        var formattedPopulacaoCidade = populacaoCidade.toLocaleString('pt-BR');
                        var formattedPorcentagem = porcentagem.toFixed(
                        2); // Ajuste o número de casas decimais conforme necessário

                        // Retorna o conteúdo HTML do tooltip personalizado para uma cidade
                        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
                            '<span style="font-family:Courier"><b>' + data.getValue(row, 0) + '</b></span><br>' +
                            'População da Cidade: ' + formattedPopulacaoCidade + '<br>' +
                            'Porcentagem da População da Cidade em relação ao Brasil: ' + formattedPorcentagem + '%' +
                            '</div>';
                    }
                }
            }
        </script>

    </head>

    <body>
        <!-- Adicionando um contêiner Bootstrap -->
        <div class="container">
            <!-- Adicionando um cabeçalho com o título -->
            <header class="text-center mt-4">
                <h1>Gráfico Treemap - População de Cidades do Brasil </h1>
                <h4>Segundo o <a
                        href="https://censo2022.ibge.gov.br/panorama/?utm_source=ibge&utm_medium=home&utm_campaign=portal"
                        target="_blank">IBGE</a></h4>
            </header>

            <!-- Div para exibir o gráfico -->
            <script src="http://www.gstatic.com/charts/loader.js"></script>

            <div id="chart_div" style="width: 100%; height: 400px;"></div>
        </div>

        <div class="d-flex justify-content-center align-items-center mt-3">
            <p class="text-center">
                Para voltar ao gráfico anterior, clique com o botão direito do mouse.
            </p>
        </div>

        <!-- Adicionando a imagem do mouse -->
        <div class="d-flex justify-content-center align-items-center">
            <img src="/img/mouse.png" alt="Mouse" width="50">
        </div>

        <!-- Adicionando o Bootstrap JS e jQuery (necessários para alguns recursos do Bootstrap) -->
        <script src="http://www.gstatic.com/charts/loader.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
@endsection
