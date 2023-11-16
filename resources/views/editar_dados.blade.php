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

@extends('layouts.main')

@section('title', 'Editar Dados')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Dados</title>
        <!-- Adicione os links para os arquivos do Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Editar Dados</h2>
                        </div>
                        <div class="card-body">
                            <!-- No formulário para edição de dados -->
                            <form method="post" action="{{ url('/editar-dados') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="cidade">Cidade:</label>
                                    <select class="form-control" name="cidade">
                                        @foreach ($dados as $dado)
                                            <option value="{{ $dado->cidade }}">{{ $dado->cidade }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="populacao">População:</label>
                                    <input type="text" class="form-control" name="populacao" placeholder="Apenas números (exemplo: 123234829)">
                                </div>
                                <button type="submit" class="btn btn-primary">Atualizar</button>
                            </form>

                            <!-- Formulário para Excluir Dados -->
                            <form method="post" action="{{ url('/excluir-dados') }}">
                                @csrf
                                @method('delete') <!-- Adicione esta linha para indicar o método DELETE -->
                                <div class="form-group">
                                    <label for="cidade_excluir">Excluir Cidade:</label>
                                    <select class="form-control" name="cidade_excluir">
                                        @foreach ($dados as $dado)
                                            <option value="{{ $dado->cidade }}">{{ $dado->cidade }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>

                            <!-- Formulário para Adicionar Dados -->
                            <form method="post" action="{{ url('/adicionar-dados') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="nova_cidade">Nova Cidade:</label>
                                    <input type="text" class="form-control" name="nova_cidade">
                                </div>
                                <div class="form-group">
                                    <label for="nova_populacao">Nova População:</label>
                                    <input type="text" class="form-control" name="nova_populacao" placeholder="Apenas números (exemplo: 123234829)">
                                </div>
                                <button type="submit" class="btn btn-success">Adicionar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
