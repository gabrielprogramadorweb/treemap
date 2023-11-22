@extends('layouts.main')

@section('title', 'Editar Dados')

@section('content')
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
                                @if (count($dados) > 0)
                                    <div class="form-group">
                                        <label for="cidade">Cidade:</label>
                                        <select class="form-control" name="cidade">
                                            @foreach ($dados as $dado)
                                                <option value="{{ $dado->cidade }}">{{ $dado->cidade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="populacao">População Atual:</label>
                                        <input type="text" class="form-control" name="populacao"
                                            placeholder="Apenas números (exemplo: 123234829)">
                                    </div>
                                    <div class="form-group">
                                        <label for="populacao_ano_passado">População do Ano Passado:</label>
                                        <input type="text" class="form-control" name="populacao_ano_passado"
                                            placeholder="Apenas números (exemplo: 123234829)">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Atualizar</button>
                                @else
                                    <p>Não há dados disponíveis para edição.</p>
                                @endif
                            </form>

                            <!-- Após o formulário para edição de dados -->
                            @if (session('editStatus'))
                                <div class="alert alert-success mt-3">
                                    {{ session('editStatus') }}
                                </div>
                            @endif

                            <!-- Formulário para Excluir Dados -->
                            <form method="post" action="{{ url('/excluir-dados') }}">
                                @csrf
                                @method('delete') <!-- Adicione esta linha para indicar o método DELETE -->
                                @if (count($dados) > 0)
                                    <div class="form-group">
                                        <label for="cidade_excluir">Excluir Cidade:</label>
                                        <select class="form-control" name="cidade_excluir">
                                            @foreach ($dados as $dado)
                                                <option value="{{ $dado->cidade }}">{{ $dado->cidade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                @else
                                    <p>Não há dados disponíveis para exclusão.</p>
                                @endif
                            </form>

                            <!-- Formulário para Adicionar Dados -->
                            <form method="post" action="{{ url('/adicionar-dados') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="nova_cidade">Nova Cidade:</label>
                                    <input type="text" class="form-control" name="nova_cidade" required>
                                </div>
                                <div class="form-group">
                                    <label for="nova_populacao">População Atual:</label>
                                    <input type="text" class="form-control" name="nova_populacao"
                                        placeholder="Apenas números (exemplo: 123234829)" required>
                                </div>
                                <div class="form-group">
                                    <label for="populacao_ano_passado">População do Ano Passado:</label>
                                    <input type="text" class="form-control" name="populacao_ano_passado"
                                        placeholder="Apenas números (exemplo: 123234829)" required>
                                </div>

                                <button type="submit" class="btn btn-success">Adicionar</button>
                                @if (session('addStatus'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('addStatus') }}
                                    </div>
                                @endif
                            </form>
                            <!-- Após o formulário para adição de dados -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
