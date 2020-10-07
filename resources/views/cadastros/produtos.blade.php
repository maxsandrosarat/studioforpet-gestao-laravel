@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Produtos</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Produto">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/produtos" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="foto">Foto</label>
                                        <input type="file" id="foto" name="foto" accept=".jpg,.png,jpeg">
                                        <br/>
                                        <b style="font-size: 80%;">Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b>
                                        <br/><br/>
                                        <label for="categoria">Categoria</label>
                                        <select class="custom-select" id="categoria" name="categoria" required>
                                            <option value="">Selecione</option>
                                            @foreach ($cats as $cat)
                                                <option value="{{$cat->id}}">{{$cat->nome}}</option>
                                            @endforeach
                                        </select>
                                        <label for="nome">Nome do Produto</label>
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Exemplo: Ração" required>
                                        <br/>
                                        <label for="tipo">Tipo de Animal</label>
                                        <select class="custom-select" id="tipo" name="tipo" required>
                                            <option value="">Selecione o tipo do animal</option>
                                            @foreach ($tipos as $tipo)
                                                <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                                            @endforeach
                                        </select>
                                        <br/><br/>
                                        <label for="fase">Fase do Animal:</label>
                                        <select class="custom-select" id="fase" name="fase" required>
                                            <option value="">Selecione a fase do animal</option>
                                            <option value="Filhote">Filhote</option>
                                            <option value="Adulto">Adulto</option>
                                            <option value="Todas">Todas</option>
                                        </select>
                                        <br/><br/>
                                        <label for="marca">Marca</label>
                                        <select class="custom-select" id="marca" name="marca" required>
                                            <option value="">Selecione a marca do produto</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{$marca->id}}">{{$marca->nome}}</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="embalagem">Embalagem do Produto</label>
                                        <input type="text" class="form-control" name="embalagem" id="embalagem" placeholder="Exemplo: 10 KG" required>
                                        <br/>
                                        <label for="preco">Preço do Produto</label>
                                        <input type="text" class="form-control" name="preco" id="preco" placeholder="Exemplo: 10.5" required>
                                        <br/>
                                        <label for="estoque">Estoque do Produto</label>
                                        <input type="number" class="form-control" name="estoque" id="estoque" placeholder="Exemplo: 100" required>
                                        <br/>
                                        <h5>Ativo?</h5>
                                        <input type="radio" id="sim" name="ativo" value="1" required>
                                        <label for="sim">Sim</label>
                                        <input type="radio" id="nao" name="ativo" value="0" required>
                                        <label for="nao">Não</label>
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
            @if(count($prods)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem produtos cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/produtos" class="btn btn-success">Nova Busca</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/produtos/filtro">
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
                        <option value="Filhote">Filhote</option>
                        <option value="Adulto">Adulto</option>
                        <option value="Todas">Todas</option>
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
                        <th>Código</th>
                        <th>Foto</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Categoria</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prods as $prod)
                    <tr>
                        <td>{{$prod->id}}</td>
                        <td width="100"><button type="button" data-toggle="modal" data-target="#exampleModalFoto{{$prod->id}}">@if($prod->foto!="")<img style="margin:0px; padding:0px;" src="/storage/{{$prod->foto}}" alt="foto_produto" width="50%"> @else <i class="material-icons md-48">no_photography</i> @endif</button></td>
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
                                @if($prod->foto!="") <img src="/storage/{{$prod->foto}}" alt="foto_produto" style="width: 100%"> @else <i class="material-icons md-60">no_photography</i> @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{$prod->categoria->nome}} {{$prod->nome}} {{$prod->tipo_animal->nome}} @if($prod->tipo_fase=='filhote') Filhote @else @if($prod->tipo_fase=='adulto') Adulto @else @if($prod->tipo_fase=='castrado') Castrado @else Todas @endif @endif @endif {{$prod->marca->nome}} {{$prod->embalagem}}</td>
                        <td width="78-">{{ 'R$ '.number_format($prod->preco, 2, ',', '.')}}</td>
                        <td>{{$prod->estoque}}</td>
                        <td>{{$prod->categoria->nome}}</td>
                        <td>@if($prod->ativo=='1') Sim @else Não @endif</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$prod->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Produto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/produtos/editar/{{$prod->id}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="foto">Foto</label>
                                                        <input type="file" id="foto" name="foto" accept=".jpg,.png,jpeg">
                                                        <br/>
                                                        <b style="font-size: 80%;">Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b>
                                                        <label for="categoria">Categoria</label>
                                                        <select  class="custom-select" id="categoria" name="categoria" required>
                                                            <option value="{{$prod->categoria->id}}">{{$prod->categoria->nome}}</option>
                                                            @foreach ($cats as $cat)
                                                                @if($cat->id==$prod->categoria->id)
                                                                @else
                                                                <option value="{{$cat->id}}">{{$cat->nome}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <label for="nome">Nome do Produto</label>
                                                        <input type="text" class="form-control" name="nome" id="nome" value="{{$prod->nome}}" required>
                                                        <br/>
                                                        <label for="tipo">Tipo de Animal</label>
                                                        <select class="custom-select" id="tipo" name="tipo" required>
                                                            <option value="{{$prod->tipo_animal->id}}">{{$prod->tipo_animal->nome}}</option>
                                                            @foreach ($tipos as $tipo)
                                                                @if($tipo->id==$prod->tipo_animal->id)
                                                                @else
                                                                <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <br/><br/>
                                                        <label for="fase">Fase do Animal:</label>
                                                        <select class="custom-select" id="fase" name="fase">
                                                            <option value="{{$prod->tipo_fase}}">{{$prod->tipo_fase}}</option>
                                                            @if($prod->tipo_fase=="Filhote")
                                                            <option value="Adulto">Adulto</option>
                                                            <option value="Todas">Todas</option>
                                                            @else
                                                                @if($prod->tipo_fase=="Adulto")
                                                                <option value="Filhote">Filhote</option>
                                                                <option value="Todas">Todas</option>
                                                                @else
                                                                    @if($prod->tipo_fase=="Todas")
                                                                    <option value="Filhote">Filhote</option>
                                                                    <option value="Adulto">Adulto</option>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </select>
                                                        <br/><br/>
                                                        <label for="marca">Marca</label>
                                                        <select class="custom-select" id="marca" name="marca" required>
                                                            <option value="{{$prod->marca->id}}">{{$prod->marca->nome}}</option>
                                                            @foreach ($marcas as $marca)
                                                                @if($marca->id==$prod->marca->id)
                                                                @else
                                                                <option value="{{$marca->id}}">{{$marca->nome}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <br/><br/>
                                                        <label for="embalagem">Embalagem do Produto</label>
                                                        <input type="text" class="form-control" name="embalagem" id="embalagem" value="{{$prod->embalagem}}" required>
                                                        <br/>
                                                        <label for="preco">Preço do Produto</label>
                                                        <input type="text" class="form-control" name="preco" id="preco" value="{{$prod->preco}}" required>
                                                        <br/>
                                                        <!--<label for="estoque">Estoque do Produto</label>
                                                        <input type="number" class="form-control" name="estoque" id="estoque" value="{{$prod->estoque}}" required>
                                                        <br><br/>-->
                                                        <h5>Ativo?</h5>
                                                        <input type="radio" id="sim" name="ativo" value="1" @if($prod->ativo=="1") checked @endif required>
                                                        <label for="sim">Sim</label>
                                                        <input type="radio" id="nao" name="ativo" value="0" @if($prod->ativo=="0") checked @endif required>
                                                        <label for="nao">Não</label>
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
                            <a href="/produtos/apagar/{{$prod->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
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
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
