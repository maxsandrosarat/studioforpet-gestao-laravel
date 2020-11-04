@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Pets</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Pet">
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
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Exemplo: Bob" required>
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
                                            <option value="TOSADO">Tosado</option>
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
                                        <label for="selectGeral">Plano?</label>
                                        <select class="custom-select" name="plano" id="selectGeral">
                                            <option value="">Selecione</option>
                                            <option value="1">SIM</option>
                                            <option value="0">NÃO</option>
                                        </select>
                                        <br/>
                                        <div id="principalSelect">
                                            <div id="1">
                                                <br/>
                                                <select class="custom-select" id="plano" name="planoId" onchange="valorPlanoPet();">
                                                    <option value="">Selecione um plano</option>
                                                    @foreach ($planos as $plano)
                                                        <option value="{{$plano->id}}" title="{{$plano->valor}}">{{$plano->nome}}</option>
                                                    @endforeach
                                                </select>
                                                <br/><br/>
                                                <label for="valorPlano">Valor do Plano: R$
                                                <input type="text" class="form-control" name="valorPlano" id="valorPlano" placeholder="Exemplo: 35.5" onblur="getValor('valorPlano')"></label>
                                            </div>
                                            <div id="0">
                                            </div>
                                        </div>
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
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem pets cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/pets" class="btn btn-success">Nova Busca</a>
                        @endif
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
                        <th>Cliente</th>
                        <th>Criação</th>
                        <th>Atualização</th>
                        <th>Plano</th>
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
                                <hr/>
                                <h3 class="font-italic">Características do {{$pet->nome}} ({{$pet->cliente->nome}})</h3>
                                <hr/>
                                <p class="font-weight-bolder">
                                Porte: @if($pet->porte==="PEQUENO") Pequeno @else @if($pet->porte==="MEDIO") Médio @else Grande @endif @endif
                                </p>
                                <hr/>
                                <p class="font-weight-bolder">
                                Pelagem: @if($pet->pelo==="CURTO") Curto @else @if($pet->pelo==="MEDIANO") Mediano @else Longo @endif @endif
                                </p>
                                <hr/>
                                <p class="font-weight-bolder">
                                Coloração do Pelo: {{$pet->cor}}
                                </p>
                                <hr/>
                                <p class="font-weight-bolder">
                                Sexo: @if($pet->sexo==="MACHO") Macho @else Fêmea @endif
                                </p>
                                <hr/>
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{$pet->nome}}</td>
                        <td>{{$pet->raca->nome}}</td>
                        <td>{{$pet->cliente->nome}}</td>
                        <td>{{ $pet->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $pet->updated_at->format('d/m/Y H:i') }}</td>
                        <td>@if($pet->temPlano=='1') <button type="button" class="badge badge-primary" data-toggle="modal" data-target="#exampleModalDesc{{$pet->id}}">Detalhes</button> @else Não @endif</td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalDesc{{$pet->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes do Plano do Pet: {{$pet->nome}} ({{$pet->cliente->nome}})</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if($pet->temPlano=='1')
                                Plano: {{$pet->plano->nome}} <br/>
                                Valor: {{'R$ '.number_format($pet->valorPlano, 2, ',', '.')}}<br/>
                                Descrição: <br/>{!!nl2br($pet->plano->descricao)!!} <br/><br/>
                                <a href="/pets/pagamentos/{{$pet->id}}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="right" title="Histórico">Histórico Pagamentos</a> 
                                <div class="modal-body">
                                    <div class="card border">
                                        <div class="card-body">
                                            <form action="/pets/pagar/{{$pet->id}}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <h3>Lançar Pagamento</h3>
                                                    <input type="hidden" name="plano" value="{{$pet->plano->id}}">
                                                    <label for="valor">Valor Pago:</label>
                                                    <input type="text" class="form-control" name="valor" id="valor" value="{{$pet->valorPlano}}" required>
                                                    <label for="formaPagamento">Forma Pagamento</label>
                                                    <select class="custom-select" id="formaPagamento" name="formaPagamento" required>
                                                        <option value="">Selecione a forma (obrigatório)</option>
                                                        <option value="Dinheiro">Dinheiro</option>
                                                        <option value="Débito">Débito</option>
                                                        <option value="Crédito à Vista">Crédito à Vista</option>
                                                        <option value="Crédito Parcelado">Crédito Parcelado</option>
                                                    </select>
                                                    <br/>
                                                    <label for="observacao">Observação</label>
                                                    <textarea class="form-control" name="observacao" id="observacao" rows="5" cols="20" maxlength="500" placeholder="Digite uma observação, caso necessário (opcional)"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success btn-sm">Pagar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="card border">
                                        <div class="card-body">
                                            <form action="/pets/trocar/{{$pet->id}}" method="POST">
                                                @csrf
                                        <h3>Trocar de Plano</h3>
                                        @if(count($planos)==1)
                                            <p>O plano atual é o único cadastrado.</p>
                                        @else
                                        <div class="form-group">
                                            <select class="custom-select" id="planoE" name="planoId" onchange="valorPlanoPetE();" required>
                                                <option value="">Selecione um plano</option>
                                                @foreach ($planos as $plano)
                                                    @if($plano->id===$pet->plano->id)
                                                    @else
                                                    <option value="{{$plano->id}}" title="{{$plano->valor}}">{{$plano->nome}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <br/><br/>
                                            <label for="valorPlano">Valor do Plano: R$
                                            <input type="text" class="form-control" name="valorPlano" id="valorPlanoE" placeholder="Exemplo: 35.5" required></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary btn-sm">Trocar</button>
                                            </div>
                                        @endif
                                    </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="card border">
                                        <div class="card-body">
                                            @if($pet->planoCancelado==1)
                                            <h3>Reativar Plano</h3>
                                            <form action="/pets/reativar/{{$pet->id}}" method="POST">
                                                @csrf
                                            <div class="form-group">
                                            <select class="custom-select" id="planoE" name="planoId" onchange="valorPlanoPetE();" required>
                                                <option value="">Selecione um plano</option>
                                                @foreach ($planos as $plano)
                                                    @if($plano->id===$pet->plano->id)
                                                    @else
                                                    <option value="{{$plano->id}}" title="{{$plano->valor}}">{{$plano->nome}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <br/><br/>
                                            <label for="valorPlano">Valor do Plano: R$
                                            <input type="text" class="form-control" name="valorPlano" id="valorPlanoE" placeholder="Exemplo: 35.5" required></label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-light btn-sm">Reativar</button>
                                            </div>
                                            </form>
                                            @else
                                            <h3>Cancelar Plano</h3>
                                                <a href="/pets/cancelar/{{$pet->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Cancelar">Cancelar</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                @endif
                            </div>
                            </div>
                        </div>
                        </div>
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
                                                <form action="/pets/editar/{{$pet->id}}" method="POST" enctype="multipart/form-data">
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
                                                            <option value="TOSADO">Tosado</option>
                                                            @else
                                                                @if($pet->pelo=="MEDIANO")
                                                                <option value="CURTO">Curto</option>
                                                                <option value="LONGO">Longo</option>
                                                                <option value="TOSADO">Tosado</option>
                                                                @else
                                                                    @if($pet->pelo=="LONGO")
                                                                    <option value="CURTO">Curto</option>
                                                                    <option value="MEDIANO">Mediano</option>
                                                                    <option value="TOSADO">Tosado</option>
                                                                    @else
                                                                        @if($pet->pelo=="TOSADO")
                                                                        <option value="CURTO">Curto</option>
                                                                        <option value="MEDIANO">Mediano</option>
                                                                        <option value="LONGO">Longo</option>
                                                                        @endif
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
                                                        @if($pet->temPlano=='0')
                                                        <br/>
                                                        <label for="selectPlano">Plano?</label>
                                                        <select class="custom-select" name="plano" id="selectPlano">
                                                            <option value="0" selected>NÃO</option>
                                                            <option value="1">SIM</option>
                                                        </select>
                                                        <br/>
                                                        <div id="principalSelectPlano">
                                                            <div id="1">
                                                                <br/>
                                                                <select class="custom-select" id="planoE" name="planoId" onchange="valorPlanoPetE();">
                                                                    <option value="">Selecione um plano</option>
                                                                    @foreach ($planos as $plano)
                                                                        <option value="{{$plano->id}}" title="{{$plano->valor}}">{{$plano->nome}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <br/><br/>
                                                                <label for="valorPlano">Valor do Plano: R$
                                                                <input type="text" class="form-control" name="valorPlano" id="valorPlanoE" placeholder="Exemplo: 35.5"></label>
                                                            </div>
                                                            <div id="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    @endif
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
            {{ $pets->links() }}
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
