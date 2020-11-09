@extends('layouts.app', ["current"=>"despesas"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lançamentos de Despesas</h5>
            @if(session('mensagem'))
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <p>{{session('mensagem')}}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalDep" data-toggle="tooltip" data-placement="bottom" title="Lançar Nova Despesa">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModalDep" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lançamento de Despesa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <form method="POST" action="/despesas/lancamentos">
                        @csrf
                        <div class="form-group">
                            <label for="vencimento">Data de Vencimento
                            <input type="date" class="form-control" name="vencimento" id="vencimento" required></label>
                            <br/>
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Exemplo: Aluguel" required>
                            <br/>
                            <label for="preco">Valor Total</label>
                            <input type="text" class="form-control" name="valorTotal" id="valorTotal" placeholder="Exemplo: 100.5" required>
                            <label for="fase">Forma de Pagamento</label>
                            <select class="custom-select" id="formaPagamento" name="formaPagamento" required>
                                <option value="">Selecione a forma de pagamento</option>
                                <option value="Boleto">Boleto</option>
                                <option value="Depósito">Depósito</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Débito em Conta">Débito em Conta</option>
                                <option value="Outra">Outra (Especificar na observação)</option>
                            </select>
                            <br/>
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" rows="5" cols="20" maxlength="500" placeholder="Digite uma observação, caso necessário (opcional)"></textarea>
                            <label for="selectGeral">Parcelado?</label>
                            <select class="custom-select" name="parcelado" id="selectGeral">
                                <option value="">Selecione</option>
                                <option value="1">SIM</option>
                                <option value="0">NÃO</option>
                            </select>
                            <br/>
                            <div id="principalSelect">
                                <div id="1">
                                <label for="valorParcela">Quantidade de Parcelas
                                <input type="number" class="form-control" name="qtdParcelas" id="qtdParcelas" placeholder="Exemplo: 2" required></label>
                                <br/>
                                <label for="valorParcela">Valor da Parcela: R$
                                <input type="text" class="form-control" name="valorParcela" id="valorParcela" placeholder="Exemplo: 35.5" onblur="getValor('valorParcela')" required></label>
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
            @if(count($despesas)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem lançamentos! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/despesas/lancamentos" class="btn btn-success">Nova Busca</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/despesas/lancamentos/filtro">
                    @csrf
                        <input class="form-control" type="number" placeholder="Código" name="codigo">
                        <input class="form-control" type="text" placeholder="Descrição" name="descricao">
                        <label for="dataInicio">Início
                        <input class="form-control" type="date" name="dataInicio"></label>
                        <label for="dataFim">Fim
                        <input class="form-control" type="date" name="dataFim"></label>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                </form>
                </div>
            <hr/>
            <b><h5 class="font-italic">Exibindo {{$despesas->count()}} de {{$despesas->total()}} de Despesas ({{$despesas->firstItem()}} a {{$despesas->lastItem()}}) - <u>Total de Valores: {{ 'R$ '.number_format($valorTotal, 2, ',', '.')}}</u></h5></b>
            <hr/>
            <div class="table-responsive-xl">
                @foreach ($despesas as $despesa)
                @php
                    $data = date("Y-m-d");
                @endphp
                    <a class="fill-div" data-toggle="modal" data-target="#exampleModal{{$despesa->id}}"><div id="my-div" class="bd-callout @if($despesa->pago==1) bd-callout-success @else @if($despesa->vencimento==$data) bd-callout-warning @else @if($despesa->vencimento<$data) bd-callout-danger @else bd-callout-info @endif @endif @endif">
                        <h4>{{date("d/m/Y", strtotime($despesa->vencimento))}} - Despesa Nº: {{$despesa->id}} - {{$despesa->descricao}}</h4>
                        <p>@if($despesa->parcelado==1)
                            {{ 'R$ '.number_format($despesa->valorParcela, 2, ',', '.')}}
                            @else
                            {{ 'R$ '.number_format($despesa->valorTotal, 2, ',', '.')}}
                            @endif
                        </p>
                    </div></a>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{$despesa->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{date("d/m/Y", strtotime($despesa->vencimento))}} - Despesa Nº: {{$despesa->id}} - {{$despesa->descricao}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="font-weight-bolder">
                                Código: {{$despesa->id}} <br/> <hr/>
                                Data de Vencimento: {{date("d/m/Y", strtotime($despesa->vencimento))}} <br/> <hr/>
                                Descrição: {{$despesa->descricao}} <br/> <hr/>
                                Valor Total: {{ 'R$ '.number_format($despesa->valorTotal, 2, ',', '.')}} <br/> <hr/>
                                Forma de Pagamento: {{$despesa->formaPagamento}} <br/> <hr/>
                                Parcelado: @if($despesa->parcelado=='1') Sim @else Não @endif <br/> <hr/>
                                @if($despesa->parcelado==1)
                                Quantidade de Parcelas: {{$despesa->qtdParcelas}} <br/> <hr/>
                                Valor Parcela: {{ 'R$ '.number_format($despesa->valorParcela, 2, ',', '.')}} <br/> <hr/>
                                @endif
                                Pago: @if($despesa->pago=='1') Sim @else Não @endif <br/> <hr/>
                                @if($despesa->pago==1)
                                Data de Pagamento: {{date("d/m/Y", strtotime($despesa->pagamento))}}<br/> <hr/>
                                @endif
                                @if($despesa->observacao!="")
                                Observação: {{$despesa->observacao}} <br/> <hr/>
                                @endif
                                Criado por: {{$despesa->usuario}}<br/>
                                Data da Criação: {{date("d/m/Y H:i", strtotime($despesa->created_at))}}<br/>
                                Última Alteração: {{date("d/m/Y H:i", strtotime($despesa->updated_at))}}
                            </p>
                        </div>
                        <div class="modal-footer">
                            @if($despesa->pago==0)
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalPagar{{$despesa->id}}" data-toggle="tooltip" data-placement="bottom" title="Pagar Despesa">
                                Pagar
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalPagar{{$despesa->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pagar Despesa: {{date("d/m/Y", strtotime($despesa->vencimento))}} - Despesa Nº: {{$despesa->id}} - {{$despesa->descricao}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/despesas/lancamentos/pagar/{{$despesa->id}}">
                                        @csrf
                                        <div class="form-group">
                                        <label for="vencimento">Data de Pagamento
                                        <input type="date" class="form-control" name="pagamento" id="pagamento" value="{{date("Y-m-d")}}" required></label>
                                        </div>
                                        <h4>Usar o Saldo do Sistema para Pagamento?</h4>
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="saldo" id="saldo" value="1" checked>
                                        <label class="form-check-label" for="saldo">Sim</label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="saldo" id="saldo" value="0">
                                        <label class="form-check-label" for="saldo">Não</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-sn">Confirmar Pagamento</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModalEdit{{$despesa->id}}" data-toggle="tooltip" data-placement="bottom" title="Excluir Despesa">
                                <i class="material-icons md-18 white">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalEdit{{$despesa->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar - Despesa: {{date("d/m/Y", strtotime($despesa->vencimento))}} - Despesa Nº: {{$despesa->id}} - {{$despesa->descricao}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/despesas/lancamentos/editar/{{$despesa->id}}">
                                            @csrf
                                            <div class="form-group">
                                            <label for="vencimento">Data de Vencimento
                                            <input type="date" class="form-control" name="vencimento" id="vencimento" value="{{date("Y-m-d", strtotime($despesa->vencimento))}}" required></label>
                                            <br/>
                                            <label for="descricao">Descrição</label>
                                            <input type="text" class="form-control" name="descricao" id="descricao" value="{{$despesa->descricao}}" required>
                                            <br/>
                                            <label for="fase">Forma de Pagamento</label>
                                            <select class="custom-select" id="formaPagamento" name="formaPagamento" required>
                                                <option value="{{$despesa->formaPagamento}}">{{$despesa->formaPagamento}}</option>
                                                <option value="Boleto">Boleto</option>
                                                <option value="Depósito">Depósito</option>
                                                <option value="Presencial">Presencial</option>
                                                <option value="Débito em Conta">Débito em Conta</option>
                                                <option value="Outra">Outra (Especificar na observação)</option>
                                            </select>
                                            <br/>
                                            <label for="observacao">Observação</label>
                                            <textarea class="form-control" name="observacao" id="observacao" rows="5" cols="20" maxlength="500">{{$despesa->observacao}}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModalDelete{{$despesa->id}}" data-toggle="tooltip" data-placement="bottom" title="Excluir Despesa">
                                <i class="material-icons md-18 white">delete</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalDelete{{$despesa->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Excluir - Despesa: {{date("d/m/Y", strtotime($despesa->vencimento))}} - Despesa Nº: {{$despesa->id}} - {{$despesa->descricao}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>Tem certeza que deseja excluir essa despesa?</h5>
                                </div>
                                <div class="modal-footer">
                                    <form method="GET" action="/despesas/lancamentos/apagar/{{$despesa->id}}">
                                        @csrf
                                    <button type="submit" class="btn btn-primary">Sim, Excluir</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                {{ $despesas->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
<br>
    <a href="/despesas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
