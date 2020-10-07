@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Relatório de Entradas/Saídas</h5>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/estoque/historicos/" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/estoque/historicos/filtro">
                @csrf
                <label for="tipo">Tipo</label>
                <select class="custom-select" id="tipo" name="tipo">
                    <option value="">Selecione o tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
                <label for="produto">Produto
                <select class="custom-select" id="produto" name="produto">
                    <option value="">Selecione</option>
                    @foreach ($prods as $prod)
                        <option value="{{$prod->id}}">{{$prod->nome}}</option>
                    @endforeach
                </select></label>
                <label for="dataInicio">Data Início
                <input class="form-control" type="date" name="dataInicio"></label>
                <label for="dataFim">Data Fim
                <input class="form-control" type="date" name="dataFim"></label>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
            </div>
            <div class="table-responsive-xl">
            <table id="yesprint" class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Movimento</th>
                        <th>Tipo Movimento</th>
                        <th>Nome Produto</th>
                        <th>Quantidade</th>
                        <th>Requisitante</th>
                        <th>Usuário</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    @if($rel->tipo=='entrada') <tr style="color:blue;"> @else <tr style="color:green;"> @endif
                        <td>{{$rel->id}}</td>
                        <td>@if($rel->tipo=='entrada') Entrada @else Saída @endif</td>
                        <td>{{$rel->produto_nome}}</td>
                        <td>{{$rel->quantidade}}</td>
                        <td>{{$rel->requisitante}}</td>
                        <td>{{$rel->usuario}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
			<div class="card-footer">
            {{ $rels->links() }}
			</div>
            @endif
        </div>
        
        </div>
    </div>
    <br/>
    <a href="/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection