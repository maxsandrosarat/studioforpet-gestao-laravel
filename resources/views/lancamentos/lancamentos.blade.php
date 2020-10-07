@extends('layouts.app', ["current"=>"lancamentos"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lançamentos</h5>
            @foreach ($saldos as $saldo)
                        Saldo Principal: R$ {{ number_format($saldo->saldo, 2, ',', '.') }} <br/>
                        Última atualização: {{ $saldo->updated_at->format('d/m/Y H:i') }}
            @endforeach
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalDep" data-toggle="tooltip" data-placement="bottom" title="Fazer Deposito">
                <i class="material-icons green md-60">add_circle</i>
            </a>
            <a type="button" class="float-button-up" data-toggle="modal" data-target="#exampleModalRet" data-toggle="tooltip" data-placement="bottom" title="Fazer Retirada">
                <i class="material-icons red md-60">remove_circle</i>
            </a>

            @if(count($lancs)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem lançamentos! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/lancamentos" class="btn btn-success">Nova Busca</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/lancamentos/filtro">
                    @csrf
                    <div class="input-group mb-3">
                        <select class="custom-select" id="tipo" name="tipo">
                            <option value="">Selecione o tipo</option>
                            <option value="deposito">Depósito</option>
                            <option value="retirada">Retirada</option>
                        </select>
                        <select class="custom-select" id="user" name="user">
                            <option value="">Selecione um usuário</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                        <label for="dataInicio">Data Início
                        <input class="form-control" type="date" name="dataInicio"></label>
                        <label for="dataFim">Data Fim
                        <input class="form-control" type="date" name="dataFim"></label>
                        <div class="input-group-append">
                          <button class="btn btn-outline-success" type="submit" id="button">Filtrar</button>
                        </div>
                      </div>
                </form>
                </div>
                <br/>
            <h5>Exibindo {{$lancs->count()}} de {{$lancs->total()}} de Lançamentos ({{$lancs->firstItem()}} a {{$lancs->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Usuário</th>
                        <th>Motivo</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lancs as $lanc)
                    @if($lanc->tipo=='deposito') <tr style="color:green;"> @else <tr style="color:red;"> @endif
                        <td>{{$lanc->id}}</td>
                        <td>@if($lanc->tipo=='deposito') Depósito @else Retirada @endif</td>
                        <td>{{ 'R$ '.number_format($lanc->valor, 2, ',', '.')}}</td>
                        <td>{{$lanc->usuario}}</td>
                        <td>{{$lanc->motivo}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($lanc->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $lancs->links() }}
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
    
    <!-- Modal -->
        <div class="modal fade" id="exampleModalDep" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Depósito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <form method="POST" action="/lancamentos/deposito">
                    @csrf
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="text" class="form-control" name="valor" id="valor" placeholder="Exemplo: 35.5" required>
                        <br/>
                        <h5>Motivo</h5>
                        <br/>
                        <textarea class="form-control" name="motivo" id="motivo" rows="1" cols="40" maxlength="30" placeholder="Escreva o motivo do lançamento"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalRet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Retirada</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form method="POST" action="/lancamentos/retirada">
                        @csrf
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" class="form-control" name="valor" id="valor" placeholder="Exemplo: 35.5" required>
                            <br/>
                            <h5>Motivo</h5>
                            <br/>
                            <textarea class="form-control" name="motivo" id="motivo" rows="1" cols="40" maxlength="30" placeholder="Escreva o motivo do lançamento"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    
@endsection