@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Controle de Estoque</h5>
            @if(count($prods)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem produtos cadastrados!
                </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/estoque/lancamentos/filtro">
                    @csrf
                    <input class="form-control mr-sm-2" type="text" placeholder="Nome do Produto" name="nome">
                    <select class="custom-select" id="categoria" name="categoria">
                        <option value="">Categoria</option>
                        @foreach ($cats as $cat)
                            <option value="{{$cat->id}}">{{$cat->nome}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select" id="tipo" name="tipo">
                        <option value="">Tipo Animal</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select" id="fase" name="fase">
                        <option value="">Fase Animal</option>
                        <option value="filhote">Filhote</option>
                        <option value="adulto">Adulto</option>
                        <option value="castrado">Castrado</option>
                        <option value="todas">Todas</option>
                    </select>
                    <select class="custom-select" id="marca" name="marca">
                        <option value="">Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{$marca->id}}">{{$marca->nome}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </form>
                </div>
                <br/>
            <h5>Exibindo {{$prods->count()}} de {{$prods->total()}} de Produtos ({{$prods->firstItem()}} a {{$prods->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Categoria</th>
                        <th>Nome</th>
                        <th>Tipo Animal</th>
                        <th>Tipo Fase</th>
                        <th>Marca</th>
                        <th>Embalagem</th>
                        <th>Estoque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prods as $prod)
                    <tr>
                        <td width="120"><button type="button" data-toggle="modal" data-target="#exampleModalFoto{{$prod->id}}">@if($prod->foto!="")<img style="margin:0px; padding:0px;" src="/storage/{{$prod->foto}}" alt="foto_produto" width="50%"> @else <i class="material-icons md-48">no_photography</i> @endif</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalFoto{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="color: black; text-align: center;">
                                @if($prod->foto!="") <img src="/storage/{{$prod->foto}}" alt="foto_produto"> @else <i class="material-icons md-60">no_photography</i> @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{$prod->categoria->nome}}</td>
                        <td>{{$prod->nome}}</td>
                        <td>{{$prod->tipo_animal->nome}}</td>
                        <td>@if($prod->tipo_fase=='filhote') Filhote @else @if($prod->tipo_fase=='adulto') Adulto @else @if($prod->tipo_fase=='castrado') Castrado @else Todas @endif @endif @endif</td>
                        <td>{{$prod->marca->nome}}</td>
                        <td>{{$prod->embalagem}}</td>
                        <td>{{$prod->estoque}}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModalEntrada{{$prod->id}}">
                                Entrada
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalEntrada{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Entrada de Produtos</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/estoque/lancamentos/entrada/{{$prod->id}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <h5>Produto Selecionado:</h5>
                                                        <h5>{{$prod->nome}} {{$prod->tipo_animal->nome}} @if($prod->tipo_fase=='filhote') Filhote @else @if($prod->tipo_fase=='adulto') Adulto @else @if($prod->tipo_fase=='castrado') Castrado @endif @endif @endif {{$prod->marca->nome}} @if($prod->embalagem!="Unidade") {{$prod->embalagem}} @endif</h5>
                                                        <h5>Quantidade atual:</h5>
                                                        <h5>{{$prod->estoque}}</h5>
                                                        <input type="hidden" name="produto" value="{{$prod->id}}">
                                                        <label for="qtd">Quantidade de Entrada
                                                        <input class="form-control" type="number" id="qtd" name="qtd" required></label>
                                                        <h5>Motivo:</h5>
                                                        <select class="custom-select" id="motivo" name="motivo" required>
                                                            <option value="">Selecione um motivo</option>
                                                            <option value="Reposição">Reposição</option>
                                                            <option value="Devolução">Devolução</option>
                                                            <option value="Outros">Outros</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalSaida{{$prod->id}}">
                                Saída
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalSaida{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Saída de Produtos</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/estoque/lancamentos/saida/{{$prod->id}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <h5>Produto Selecionado:</h5>
                                                        <h5>{{$prod->nome}} {{$prod->tipo_animal->nome}} @if($prod->tipo_fase=='filhote') Filhote @else @if($prod->tipo_fase=='adulto') Adulto @else @if($prod->tipo_fase=='castrado') Castrado @endif @endif @endif {{$prod->marca->nome}} @if($prod->embalagem!="Unidade") {{$prod->embalagem}} @endif</h5>
                                                        <h5>Quantidade atual:</h5>
                                                        <h5>{{$prod->estoque}}</h5>
                                                        <input type="hidden" name="produto" value="{{$prod->id}}">
                                                        <label for="qtd">Quantidade de Saída
                                                        <input class="form-control" type="number" id="qtd" name="qtd" required></label>
                                                        <h5>Motivo:</h5>
                                                        <select class="custom-select" id="motivo" name="motivo" required>
                                                            <option value="">Selecione um motivo</option>
                                                            <option value="Defeito">Defeito</option>
                                                            <option value="Outros">Outros</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $prods->links() }}
        </div>
    </div>
    <br>
    <a href="/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
