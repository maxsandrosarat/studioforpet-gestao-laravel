@extends('layouts.app', ["current"=>"historicos"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Histórico</h5>
            @if(count($hists)==0)
            <div class="alert alert-dark" role="alert">
                @if($view=="inicial")
                Sem históricos!
                @endif
                @if($view=="filtro")
                Sem resultados da busca!
                <a href="/historicos" class="btn btn-success">Nova Busca</a>
                @endif
            </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/historicos/filtro">
                    @csrf
                    <div class="input-group mb-3">
                        <select class="custom-select" id="user" name="user">
                            <option value="">Selecione um usuário</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                        <select class="custom-select" id="acao" name="acao">
                            @foreach ($acoes as $acao)
                            <option value="{{$acao->acao}}">{{$acao->acao}}</option>
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
            <h5>Exibindo {{$hists->count()}} de {{$hists->total()}} de Históricos ({{$hists->firstItem()}} a {{$hists->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Referência</th>
                        <th>Código da Referência</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hists as $hist)
                    <tr>
                        <td style="text-align: center;">{{$hist->id}}</td>
                        <td style="text-align: center;">{{$hist->usuario}}</td>
                        <td style="text-align: center;">{{$hist->acao}}</td>
                        <td style="text-align: center;">{{$hist->referencia}}</td>
                        <td style="text-align: center;">{{$hist->codigo}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($hist->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $hists->links() }}
        </div>
    </div>
    <br>
    <a href="/home" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection