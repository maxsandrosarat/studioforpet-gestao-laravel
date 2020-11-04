@extends('layouts.app', ["current"=>"vendas"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Vendas de Serviços</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Lançar Nova Venda">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lançamento de Venda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/vendas/servicos" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="pet">Pet</label>
                                        <select class="custom-select" id="pet" name="pet">
                                            <option value="">Selecione o pet (opcional)</option>
                                            @foreach ($pets as $pet)
                                                <option value="{{$pet->id}}">{{$pet->nome}} ({{$pet->cliente->nome}})</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="servico">Serviço</label>
                                        <select class="custom-select" id="servico" name="servico" onchange="valorServico();" required>
                                            <option value="">Selecione o serviço (obrigatório)</option>
                                            @foreach ($servs as $servico)
                                                <option value="{{$servico->id}}" title="{{$servico->preco}}">{{$servico->nome}}</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="valor">Valor: R$
                                        <input type="text" class="form-control" name="valor" id="valor" placeholder="Exemplo: 35.5" onblur="getValor('valor')" required></label>
                                        <br/>
                                        <label for="desconto">Desconto</label>
                                        <input type="text" class="form-control" name="desconto" id="desconto" placeholder="Exemplo: 35.5 (opcional)" onblur="getValor('desconto')">
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
                                        <textarea class="form-control" name="observacao" id="observacao" rows="10" cols="40" maxlength="500" placeholder="Digite uma observação, caso necessário (opcional)"></textarea> 
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
            @if(count($vendaServs)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem vendas lançadas! Faça novo lançamento no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/vendaServs" class="btn btn-success">Nova Busca</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/vendas/servicos/filtro">
                    @csrf
                    <select class="custom-select" id="servico" name="servico">
                        <option value="">Serviço</option>
                        @foreach ($servs as $servico)
                            <option value="{{$servico->id}}">{{$servico->nome}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select" id="pet" name="pet">
                        <option value="">Pet</option>
                        @foreach ($pets as $pet)
                            <option value="{{$pet->id}}">{{$pet->nome}} ({{$pet->cliente->nome}})</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </form>
                </div>
                <br/>
            <h5>Exibindo {{$vendaServs->count()}} de {{$vendaServs->total()}} de Vendas ({{$vendaServs->firstItem()}} a {{$vendaServs->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Serviço</th>
                        <th>Pet</th>
                        <th>Valor</th>
                        <th>Desconto</th>
                        <th>Forma Pagamento</th>
                        <th>Observação</th>
                        <th>Data & Hora</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendaServs as $vendaServ)
                    <tr>
                        <td>{{$vendaServ->id}}</td>
                        <td>{{$vendaServ->servico->nome}}</td>
                        <td>@if($vendaServ->pet=="") Não informado @else {{$vendaServ->pet->nome}} @endif</td>
                        <td>R$ {{ number_format($vendaServ->valor, 2, ',', '.') }}</td>
                        <td>@if($vendaServ->desconto=="") R$ 0,00 @else R$ {{ number_format($vendaServ->desconto, 2, ',', '.') }} @endif</td>
                        <td>{{$vendaServ->forma_pagamento}}</td>
                        <td>@if($vendaServ->observacao=="") Sem Observação @else
                        <button type="button" class="badge badge-primary" data-toggle="modal" data-target="#exampleModalDesc{{$vendaServ->id}}">Observação</button></td>@endif
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalDesc{{$vendaServ->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{$vendaServ->observacao}}
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{ $vendaServ->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="/vendas/servicos/apagar/{{$vendaServ->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $vendaServs->links() }}
        </div>
    </div>
    <br>
    <a href="/vendas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
