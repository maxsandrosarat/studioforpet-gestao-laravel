@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Marcas</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Marca">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form action="/marcas" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nome">Nome da Categoria</label>
                                <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome da marca" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
            @if(count($marcas)==0)
                <div class="alert alert-danger" role="alert">
                    Sem marcas cadastradas!
                </div>
            @else
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($marcas as $marca)
                    <tr>
                        <td style="text-align: center;">{{$marca->id}}</td>
                        <td style="text-align: center;">{{$marca->nome}}</td>
                        <td>@if($marca->ativo=='1') Sim @else Não @endif</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$marca->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            
                            <div class="modal fade" id="exampleModal{{$marca->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Marca</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/marcas/editar/{{$marca->id}}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="nome">Nome da Marca</label>
                                                <input type="text" class="form-control" name="nome" id="nome" value="{{$marca->nome}}" required>
                                                <br/>
                                                <h5>Ativo?</h5>
                                                <input type="radio" id="sim" name="ativo" value="1" @if($marca->ativo=="1") checked @endif required>
                                                <label for="sim">Sim</label>
                                                <input type="radio" id="nao" name="ativo" value="0" @if($marca->ativo=="0") checked @endif required>
                                                <label for="nao">Não</label>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                            <a href="/marcas/apagar/{{$marca->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>

    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection