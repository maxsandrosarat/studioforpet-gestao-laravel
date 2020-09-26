@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Pets</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo petuto">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Pet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/pets" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="foto">Foto</label>
                                        <input type="file" id="foto" name="foto" accept=".jpg,.png,jpeg">
                                        <br/>
                                        <b style="font-size: 80%;">Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b>
                                        <br/><br/>
                                        <label for="nome">Nome do Pet</label>
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Exemplo: Ração" required>
                                        <br/>
                                        <label for="raca">Raça</label>
                                        <select class="custom-select" id="raca" name="raca" required>
                                            <option value="">Selecione a raça do animal</option>
                                            @foreach ($racas as $raca)
                                                <option value="{{$raca->id}}">{{$raca->nome}}</option>
                                            @endforeach
                                        </select>
                                        <br/><br/>
                                        <label for="fase">Porte</label>
                                        <select class="custom-select" id="fase" name="fase" required>
                                            <option value="">Selecione o porte</option>
                                            <option value="PEQUENO">Pequeno</option>
                                            <option value="MEDIO">Médio</option>
                                            <option value="GRANDE">Grande</option>
                                        </select>
                                        <br/><br/>
                                        <label for="fase">Pelagem</label>
                                        <select class="custom-select" id="fase" name="fase" required>
                                            <option value="">Selecione a pelagem</option>
                                            <option value="CURTO">Curto</option>
                                            <option value="MEDIANO">Mediano</option>
                                            <option value="LONGO">Longo</option>
                                        </select>
                                        <br/>
                                        <label for="cor">Coloração</label>
                                        <input type="text" class="form-control" name="cor" id="cor" placeholder="Exemplo: Caramelo, Preto, Branco..." required>
                                        <br/>
                                        <label for="sexo">Sexo</label>
                                        <select class="custom-select" id="sexo" name="sexo" required>
                                            <option value="">Selecione o sexo</option>
                                            <option value="MACHO">Macho</option>
                                            <option value="FEMEA">Fêmea</option>
                                        </select>
                                        <br/>
                                        <label for="cliente">Cliente</label>
                                        <select class="custom-select" id="cliente" name="cliente" required>
                                            <option value="">Selecione</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{$cliente->id}}">{{$cliente->nome}}</option>
                                            @endforeach
                                        </select>
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
            @if(count($pets)==0)
                <div class="alert alert-danger" role="alert">
                    Sem pets cadastrados!
                </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/pets/filtro">
                    @csrf
                    <input class="form-control mr-sm-2" type="text" placeholder="Nome do Pet" name="nome">
                    <select class="custom-select" id="raca" name="raca">
                        <option value="">Raça</option>
                        @foreach ($racas as $raca)
                            <option value="{{$raca->id}}">{{$raca->nome}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select" id="cliente" name="cliente">
                        <option value="">Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{$cliente->id}}">{{$cliente->nome}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </form>
                </div>
                <br/>
            <h5>Exibindo {{$pets->count()}} de {{$pets->total()}} de Pets ({{$pets->firstItem()}} a {{$pets->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Raça</th>
                        <th>Porte</th>
                        <th>Pelagem</th>
                        <th>Coloração</th>
                        <th>Sexo</th>
                        <th>Cliente</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pets as $pet)
                    <tr>
                        <td>{{$pet->id}}</td>
                        <td width="100"><button type="button" data-toggle="modal" data-target="#exampleModalFoto{{$pet->id}}">@if($pet->foto!="")<img style="margin:0px; padding:0px;" src="/storage/{{$pet->foto}}" alt="foto_pet" width="50%"> @else <i class="material-icons md-48">no_photography</i> @endif</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalFoto{{$pet->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="color: black; text-align: center;">
                                @if($pet->foto!="") <img src="/storage/{{$pet->foto}}" alt="foto_petuto" style="width: 100%"> @else <i class="material-icons md-60">no_photography</i> @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{$pet->nome}}</td>
                        <td>{{$pet->raca->nome}}</td>
                        <td>@if($pet->porte==="PEQUENO") Pequeno @else @if($pet->porte==="MEDIO") Médio @else Grande @endif @endif</td>
                        <td>@if($pet->pelo==="CURTO") Curto @else @if($pet->pelo==="MEDIANO") Mediano @else Longo @endif @endif</td>
                        <td>{{$pet->cor}}</td>
                        <td>@if($pet->sexo==="MACHO") Macho @else Fêmea @endif</td>
                        <td>{{$pet->cliente->nome}}</td>
                        <td>@if($pet->ativo=='1') Sim @else Não @endif</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$pet->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$pet->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Pet</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/petutos/editar/{{$pet->id}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="foto">Foto</label>
                                                        <input type="file" id="foto" name="foto" accept=".jpg,.png,jpeg">
                                                        <br/>
                                                        <b style="font-size: 80%;">Aceito apenas Imagens JPG e PNG (".jpg" e ".png")</b>
                                                        <label for="nome">Nome do petuto</label>
                                                        <input type="text" class="form-control" name="nome" id="nome" value="{{$pet->nome}}" required>
                                                        <br/>
                                                        <label for="raca">Raça</label>
                                                        <select class="custom-select" id="raca" name="raca" required>
                                                            <option value="{{$pet->raca->id}}">{{$pet->raca->nome}}</option>
                                                            @foreach ($racas as $raca)
                                                                @if($raca->id==$pet->raca->id)
                                                                @else
                                                                <option value="{{$raca->id}}">{{$raca->nome}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <br/><br/>
                                                        <label for="porte">Porte</label>
                                                        <select class="custom-select" id="porte" name="porte">
                                                            <option value="{{$pet->porte}}">@if($pet->porte==="PEQUENO") Pequeno @else @if($pet->porte==="MEDIO") Médio @else Grande @endif @endif</option>
                                                            @if($pet->porte=="PEQUENO")
                                                            <option value="MEDIO">Médio</option>
                                                            <option value="GRANDE">Grande</option>
                                                            @else
                                                                @if($pet->porte=="MEDIO")
                                                                <option value="PEQUENO">Pequeno</option>
                                                                <option value="GRANDE">Grande</option>
                                                                @else
                                                                    @if($pet->porte=="GRANDE")
                                                                    <option value="PEQUENO">Pequeno</option>
                                                                    <option value="MEDIO">Médio</option>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </select>
                                                        <br/><br/>
                                                        <label for="pelo">Pelagem</label>
                                                        <select class="custom-select" id="pelo" name="pelo">
                                                            <option value="{{$pet->pelo}}">@if($pet->pelo==="CURTO") Curto @else @if($pet->pelo==="MEDIANO") Mediano @else Longo @endif @endif</option>
                                                            @if($pet->pelo=="CURTO")
                                                            <option value="MEDIANO">Mediano</option>
                                                            <option value="LONGO">Longo</option>
                                                            @else
                                                                @if($pet->pelo=="MEDIANO")
                                                                <option value="CURTO">Curto</option>
                                                                <option value="LONGO">Longo</option>
                                                                @else
                                                                    @if($pet->pelo=="LONGO")
                                                                    <option value="CURTO">Curto</option>
                                                                    <option value="MEDIANO">Mediano</option>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </select>
                                                        <br/><br/>
                                                        <label for="cor">Coloração</label>
                                                        <input type="text" class="form-control" name="cor" id="cor" value="{{$pet->cor}}" required>
                                                        <br/>
                                                        <label for="sexo">Sexo</label>
                                                        <select class="custom-select" id="sexo" name="sexo">
                                                            <option value="{{$pet->sexo}}">@if($pet->sexo==="MACHO") Macho @else Fêmea @endif</option>
                                                            @if($pet->sexo=="MACHO")
                                                            <option value="FEMEA">Fêmea</option>
                                                            @else
                                                                @if($pet->sexo=="FEMEA")
                                                                <option value="MACHO">Macho</option>
                                                                @endif
                                                            @endif
                                                        </select>
                                                        <br/>
                                                        <label for="cliente">Cliente</label>
                                                        <select  class="custom-select" id="cliente" name="cliente" required>
                                                            <option value="{{$pet->cliente->id}}">{{$pet->cliente->nome}}</option>
                                                            @foreach ($clientes as $cliente)
                                                                @if($cliente->id==$pet->cliente->id)
                                                                @else
                                                                <option value="{{$cliente->id}}">{{$cliente->nome}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <h5>Ativo?</h5>
                                                        <input type="radio" id="sim" name="ativo" value="1" @if($pet->ativo=="1") checked @endif required>
                                                        <label for="sim">Sim</label>
                                                        <input type="radio" id="nao" name="ativo" value="0" @if($pet->ativo=="0") checked @endif required>
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
                            <a href="/petutos/apagar/{{$pet->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $pets->links() }}
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
