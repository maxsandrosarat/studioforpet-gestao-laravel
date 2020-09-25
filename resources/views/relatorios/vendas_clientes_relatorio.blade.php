@extends('layouts.app_admin', ["current"=>"relatorios"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Relatório de Vendas por Clientes</h5>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/relatorios/vendas/clientes" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/relatorios/vendas/clientes/filtro">
                @csrf
                <select class="custom-select" id="status" name="status">
                    <option value="">Selecione o status</option>
                    <option value="RESERV" style="color:orange; font-weight: bold;">Reservado</option>
                    <option value="FEITO" style="color:blue; font-weight: bold;">Feito</option>
                    <option value="PAGO"style="color:green; font-weight: bold;">Pago</option>
                    <option value="CANCEL" style="color:red; font-weight: bold;">Cancelado</option>
                </select>
                <select class="custom-select" id="cliente" name="cliente">
                    <option value="">Selecione um cliente</option>
                    @foreach ($clientes as $cliente)
                    <option value="{{$cliente->id}}">{{$cliente->name}}</option>
                    @endforeach
                </select>
                <label for="dataInicio">Data Início
                <input class="form-control mr-sm-2" type="date" name="dataInicio"></label>
                <label for="dataFim">Data Fim
                <input class="form-control mr-sm-2" type="date" name="dataFim"></label>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
            </div>
            <br/>
            <div class="table-responsive-xl">
            <table id="yesprint" class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Pedido</th>
                        <th>Nome Cliente</th>
                        <th>Status</th>
                        <th>Total Pedido</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->id}}</td>
                        <td>{{$rel->user->name}}</td>
                        <td @if($rel->status=='RESERV') style="color:orange; font-weight: bold;" @else @if($rel->status=='FEITO') style="color:blue; font-weight: bold;" @else @if($rel->status=='PAGO') style="color:green; font-weight: bold;" @else style="color:red; font-weight: bold;" @endif @endif @endif>@if($rel->status=='RESERV') Reservado @else @if($rel->status=='FEITO') Feito @else @if($rel->status=='PAGO') Pago @else Cancelado @endif @endif @endif</td>
                        <td>{{ 'R$ '.number_format($rel->total, 2, ',', '.')}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
                    @if($view=="filtro")
                    <tr>
                        <td colspan="3">TOTAL GERAL:</td>
                        <td colspan="2">{{ 'R$ '.number_format($total_valor, 2, ',', '.')}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $rels->links() }}
        </div>
    </div>
    <br/>
    <a href="/admin/relatorios/vendas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection