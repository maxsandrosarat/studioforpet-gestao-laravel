@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Categorias</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Categoria">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form action="/categorias" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nomeCategoria">Nome da Categoria</label>
                                <input type="text" class="form-control" name="nomeCategoria" id="nomeCategoria" placeholder="Digite o nome da categoria" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
            @if(count($cats)==0)
                <div class="alert alert-danger" role="alert">
                    Sem categorias cadastradas!
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
                    @foreach ($cats as $cat)
                    <tr>
                        <td>{{$cat->id}}</td>
                        <td>{{$cat->nome}}</td>
                        <td>@if($cat->ativo=='1') Sim @else Não @endif</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$cat->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            
                            <div class="modal fade" id="exampleModal{{$cat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Categoria</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/categorias/editar/{{$cat->id}}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="nomeCategoria">Nome da Categoria</label>
                                                <input type="text" class="form-control" name="nomeCategoria" id="nomeCategoria" value="{{$cat->nome}}" required>
                                                <br/>
                                                <h5>Ativo?</h5>
                                                <input type="radio" id="sim" name="ativo" value="1" @if($cat->ativo=="1") checked @endif required>
                                                <label for="sim">Sim</label>
                                                <input type="radio" id="nao" name="ativo" value="0" @if($cat->ativo=="0") checked @endif required>
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
                            <a href="/categorias/apagar/{{$cat->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
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