@extends('layouts.app', ["current"=>"historicos"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Histórico</h5>
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
                        <select class="custom-select" id="user" name="user">
                            <option value="">Selecione um usuário</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                        <select class="custom-select" id="acao" name="acao">
                            <option value="">Selecione uma ação</option>
                            @foreach ($acoes as $acao)
                            <option value="{{$acao->acao}}">{{$acao->acao}}</option>
                            @endforeach
                        </select>
                        <select class="custom-select" id="referencia" name="referencia">
                            <option value="">Selecione uma referência</option>
                            @foreach ($referencias as $referencia)
                            <option value="{{$referencia->referencia}}">{{$referencia->referencia}}</option>
                            @endforeach
                        </select>
                        <label for="dataInicio">Início
                        <input class="form-control" type="date" name="dataInicio"></label>
                        <label for="dataFim">Fim
                        <input class="form-control" type="date" name="dataFim"></label>
                        <button class="btn btn-outline-success" type="submit" id="button">Filtrar</button>
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