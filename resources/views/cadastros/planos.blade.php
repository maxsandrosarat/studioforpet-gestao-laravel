@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Planos</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Plano">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Plano</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/planos" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nome">Nome do Plano</label>
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Exemplo: Básico" required>
                                        <br/>
                                        <h5>Descrição do Plano</h5>
                                        <br/>
                                        <textarea class="form-control" name="descricao" id="descricao" rows="10" cols="40" maxlength="500" placeholder="Escreva o serviços inclusos no plano"></textarea>
                                        <br/>
                                        <label for="valor">Valor do Plano</label>
                                        <input type="text" class="form-control" name="valor" id="valor" placeholder="Exemplo: 35.5" onblur="getValor('valor')" required>
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
            @if(count($planos)==0)
                <div class="alert alert-danger" role="alert">
                    Sem planos cadastrados!
                </div>
            @else
            <h5>Exibindo {{$planos->count()}} de {{$planos->total()}} de Planos ({{$planos->firstItem()}} a {{$planos->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Ativo</th>
                        <th>Descrição</th>
                        <th>Criação</th>
                        <th>Última Atualização</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planos as $plano)
                    <tr>
                        <td>{{$plano->id}}</td>
                        <td>{{$plano->nome}}</td>
                        <td>{{ 'R$ '.number_format($plano->valor, 2, ',', '.')}}</td>
                        <td>
                            @if($plano->ativo==1)
                                <b><i class="material-icons green">check_circle</i></b>
                            @else
                                <b><i class="material-icons red">highlight_off</i></b>
                            @endif
                        </td>
                        <td><button type="button" class="badge badge-primary" data-toggle="modal" data-target="#exampleModalDesc{{$plano->id}}">Descrição</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalDesc{{$plano->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Descrição do Plano: {{$plano->nome}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {!!nl2br($plano->descricao)!!}
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>{{ $plano->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $plano->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$plano->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$plano->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Raça</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/planos/editar/{{$plano->id}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="nome">Nome do Plano</label>
                                                        <input type="text" class="form-control" name="nome" id="nome" value="{{$plano->nome}}" required>
                                                        <br/>
                                                        <h5>Descrição do Plano</h5>
                                                        <br/>
                                                        <textarea class="form-control" name="descricao" id="descricao" rows="10" cols="40" maxlength="500">{{$plano->descricao}}</textarea>
                                                        <br/>
                                                        <label for="valor">Valor do Plano</label>
                                                        <input type="text" class="form-control" name="valor" id="valorE" value="{{$plano->valor}}" onblur="getValor('valorE')" required>
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
                            @if($plano->ativo==1)
                                <a href="/planos/apagar/{{$plano->id}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48 red">disabled_by_default</i></a>
                            @else
                                <a href="/planos/apagar/{{$plano->id}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="right" title="Ativar"><i class="material-icons md-48 green">check_box</i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $planos->links() }}
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
