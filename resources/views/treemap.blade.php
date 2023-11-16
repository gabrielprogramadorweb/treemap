<?php

// Conectar ao banco de dados (substitua com suas credenciais)
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'dados';

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die('Conexão falhou: ' . $conn->connect_error);
} else {
    console.log('Conexão bem-sucedida!');
}

// Consulta SQL para obter dados da tabela 'dados'
$sql = 'SELECT cidade, populacao FROM dados';
$result = $conn->query($sql);

// Formatar os dados para o Treemap
$data = [['City', 'Parent', 'Population', 'Color']];

// Adicionar entrada para o país
$data[] = ['Global', null, 0, 0];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Adicionar cada cidade como uma linha de dados
        $data[] = [$row['cidade'], 'Global', (int) $row['populacao'], (int) $row['populacao']];
    }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Converter dados para formato JSON
$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
?>
;
@extends('layouts.main')

@section('title', 'HDC EVENTS')

@section('content')

    <!DOCTYPE html>
    <html lang="en">

    <head>


        <!-- ... (seu código existente) ... -->

        <script type="text/javascript" src="http://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['treemap']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Usar dados dinâmicos obtidos do PHP
                var data = google.visualization.arrayToDataTable(<?php echo $json_data; ?>);

                console.log(data); // Adicione esta linha para depuração

                tree = new google.visualization.TreeMap(document.getElementById('chart_div'));

                tree.draw(data, {
                    minColor: '#f00',
                    midColor: '#ddd',
                    maxColor: '#0d0',
                    headerHeight: 20, // Ajuste conforme necessário
                    fontColor: 'black',
                    showScale: true
                });

            }
        </script>
    </head>

    <body>

        <!-- Adicionando um contêiner Bootstrap -->
        <div class="container">
            <!-- Adicionando um cabeçalho com o título -->
            <header class="text-center mt-4">
                <h1>Gráfico Treemap - População de Cidades</h1>
            </header>

            <!-- Div para exibir o gráfico -->
            <script src="http://www.gstatic.com/charts/loader.js"></script>

            <div id="chart_div" style="width: 50%; height: 300px;"></div>

        </div>

        <!-- Adicionando um botão de volta -->
        <div class="d-flex justify-content-center align-items-center mt-3">
            <button onclick="voltarParaPrincipal()" class="btn btn-voltar">Voltar para a Área Principal</button>
        </div>

        <!-- Função para voltar para a área principal -->
        <script>
            function voltarParaPrincipal() {
                window.location.href = '/'; // Substitua com a rota desejada
            }
        </script>

        <!-- Adicionando o Bootstrap JS e jQuery (necessários para alguns recursos do Bootstrap) -->
        <script src="http://www.gstatic.com/charts/loader.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>

    </html>

@endsection
