@extends('layouts.app_admin', ["current"=>"relatorios"])

@section('body')
<div class="card border">
    <div class="card-body">
        <h5 class="card-title">Relatório de Clientes & Produtos</h5>
        @if(count($rels)==0)
                <div class="alert alert-dark" role="alert">
                    @if($view=="inicial")
                    Sem movimentos cadastrados!
                    @else @if($view=="filtro")
                    Sem resultados da busca!
                    <a href="/admin/relatorios/estoque" class="btn btn-success">Voltar</a>
                    @endif
                    @endif
                </div>
        @else
        <div class="card border">
        <h5>Filtros: </h5>
        <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/relatorios/vendas/clientesProdutos/filtro">
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
            <!--<select class="custom-select" id="produtos" name="produto">
                <option value="">Selecione um produto</option>
                @foreach ($prods as $prod)
                <option value="{{$prod->id}}">{{$prod->nome}} {{$prod->tipo_animal->nome}} @if($prod->tipo_fase=='filhote') Filhote @else @if($prod->tipo_fase=='adulto') Adulto @else @if($prod->tipo_fase=='castrado') Castrado @endif @endif @endif {{$prod->marca->nome}} @if($prod->embalagem!="Unidade") {{$prod->embalagem}} @endif</option>
                @endforeach
            </select>-->
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
                    <th>Cliente</th>
                    <th>Nº do Pedido</th>
                    <th>Status</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Data & Hora</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($rels as $pedido)
            @foreach ($pedido->pedido_produtos_itens as $pedido_produto)
            <tr>
                <td>{{ $pedido->user->name }}</td>
                <td>{{ $pedido->id }}</td>
                <td @if($pedido->status=='RESERV') style="color:orange; font-weight: bold;" @else @if($pedido->status=='FEITO') style="color:blue; font-weight: bold;" @else @if($pedido->status=='PAGO') style="color:green; font-weight: bold;" @else style="color:red; font-weight: bold;" @endif @endif @endif>@if($pedido->status=='RESERV') Reservado @else @if($pedido->status=='FEITO') Feito @else @if($pedido->status=='PAGO') Pago @else Cancelado @endif @endif @endif</td>
                <td>{{ $pedido_produto->produto->nome }} {{$pedido_produto->produto->tipo_animal->nome}} @if($pedido_produto->produto->tipo_fase=='filhote') Filhote @else @if($pedido_produto->produto->tipo_fase=='adulto') Adulto @else @if($pedido_produto->produto->tipo_fase=='castrado') Castrado @endif @endif @endif {{$pedido_produto->produto->marca->nome}} @if($pedido_produto->produto->embalagem!="Unidade") {{$pedido_produto->produto->embalagem}} @endif</td>      
                <td>R$ {{ number_format($pedido_produto->produto->preco, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($pedido_produto->desconto, 2, ',', '.') }}</td>
                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>  
            </tr>
            @endforeach
            @endforeach
            </tbody>
        </table>
        <div class="card-footer">
            {{ $rels->links() }}
        </div>
        </div>
        @endif
    </div>
</div>
@endsection