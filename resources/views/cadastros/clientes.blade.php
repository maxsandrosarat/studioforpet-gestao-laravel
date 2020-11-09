@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Clientes</h5>
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
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalCad" data-toggle="tooltip" data-placement="bottom" title="Cadastrar Novo Cliente">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/clientes">
                        @csrf
                            <h5>Pessoais</h5>
                            <label for="name">Nome</label>
                            <div>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nome" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Digite o nome do cliente">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <label for="cpf">CPF</label>
                            <div>
                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" autocomplete="cpf" autofocus placeholder="Digite apenas os números" onblur="formatarCpf()">
                                @error('cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <label for="nascimento">Nascimento</label>
                            <div>
                                <input id="nascimento" type="date" class="form-control @error('nascimento') is-invalid @enderror" name="nascimento" value="{{ old('nascimento') }}" autocomplete="nascimento" autofocus>
                                @error('nascimento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <br/>
                            <h5>Endereço</h5>
                            <b> <p style="font-size: 80%">Caso saiba o CEP, digite e em seguida os campos serão autocompletados</p></b>
                            <label>CEP
                            <input class="form-control" name="cep" type="text" id="cep" value="" size="10" maxlength="9"
                            onblur="pesquisacep(this.value);" /></label><br />
                            <label>Rua:
                            <input class="form-control" name="rua" type="text" id="rua" size="60"/></label><br />
                            <label>Bairro:
                            <input class="form-control" name="bairro" type="text" id="bairro" size="40"/></label><br />
                            <label>Cidade:
                            <input class="form-control" name="cidade" type="text" id="cidade" size="40" /></label><br />
                            <label>Estado:
                            <input class="form-control" name="uf" type="text" id="uf" size="2"/></label><br />
                            <input class="form-control" name="ibge" type="hidden" id="ibge" size="8" />
                            <label for="numero">Número
                            <input class="form-control" type="text" name="numero" id="numero" size="5"><br></label>
                            <label for="complemento">Complemento</label>
                            <input class="form-control" type="text" name="complemento" id="complemento" size="60">
                            
                            <br/>
                            <h5>Telefone</h5>
                            <label for="telefone">Número do Telefone</label>
                                <input name="telefone" class="form-control" id="telefone" size="60" onblur="formataNumeroTelefone()" placeholder="Número com DDD, exemplo: 67991234567" required>
                                <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                                <select class="form-control" name="tipo" id="tipo">
                                    <option value="PESSOAL">Pessoal</option>
                                    <option value="RESIDENCIAL">Residencial</option>
                                    <option value="COMERCIAL">Comercial</option>
                                    <option value="RECADO">Recado</option>
                                </select>
                            <div class="modal-footer">
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Cadastrar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            @if(count($clientes)==0)
                <div class="alert alert-dark" role="alert">
                    @if($view=="inicial")
                        Sem clientes cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                    @endif
                    @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/clientes" class="btn btn-success">Nova Busca</a>
                    @endif
                </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
                <form class="form-inline my-2 my-lg-0" method="GET" action="/clientes/filtro">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="nome" class="form-control" placeholder="Nome do Cliente" aria-label="Nome do Cliente" aria-describedby="button">
                        <div class="input-group-append">
                          <button class="btn btn-outline-success" type="submit" id="button">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
            <br/>
            <h5>Exibindo {{$clientes->count()}} de {{$clientes->total()}} de Clientes ({{$clientes->firstItem()}} a {{$clientes->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Nascimento</th>
                        <th>Endereço</th>
                        <th>Telefones</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{$cliente->id}}</td>
                        <td>{{$cliente->nome}}</td>
                        <td>{{$cliente->cpf}}</td>
                        <td>@if($cliente->nascimento!="") {{date("d/m/Y", strtotime($cliente->nascimento))}} @endif</td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEnd{{$cliente->id}}">
                            Endereço
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalEnd{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Endereços - Cliente: {{$cliente->nome}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5 style="color: blue;">Adicione ou edite o endereço no icone de "Editar"</h5>
                                            @if($cliente->rua=="")
                                                <div class="alert alert-danger" role="alert">
                                                    Sem endereço cadastrado!
                                                </div>
                                            @else
                                            <table class="table table-striped table-ordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Endereço</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{$cliente->rua}}, {{$cliente->numero}} ({{$cliente->complemento}}) - {{$cliente->bairro}} -  {{$cliente->cidade}} - {{$cliente->uf}} - {{$cliente->cep}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalTel{{$cliente->id}}">
                            Telefones
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalTel{{$cliente->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Telefones - Cliente: {{$cliente->nome}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if(count($cliente->telefones)==0)
                                                <div class="alert alert-danger" role="alert">
                                                    Sem telefones cadastrados!
                                                </div>
                                            @else
                                            <table class="table table-striped table-ordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Tipo</th>
                                                        <th>Status</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cliente->telefones as $telefone)
                                                    @if($telefone->ativo==true) <tr style="color:green;"> @else <tr style="color:red;"> @endif
                                                        <td>{{$telefone->numero}}</td>
                                                        <td>{{$telefone->tipo}}</td>
                                                        <td>@if($telefone->ativo==1) ATIVO @else INATIVO @endif</td>
                                                        <td>
                                                            @if($telefone->ativo==1)
                                                            <a href="/clientes/telefones/apagar/{{$cliente->id}}/{{$telefone->id}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Inativar"><i class="material-icons md-48">delete</i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                        <h5>Cadastrar Novo Número</h5>
                                        <div class="modal-body">
                                            <form method="post" action="/telefones/{{$cliente->id}}">
                                                @csrf
                                                <label for="telefone">Número do Telefone
                                                <input name="telefone" class="form-control" id="telefoneNovo" size="60" onblur="formataNumeroTelefoneNovo()" placeholder="Número com DDD, exemplo: 67991234567"></label>
                                                <br/>
                                                <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                                                <select class="form-control" name="tipo" id="tipo" required>
                                                    <option value="PESSOAL">Pessoal</option>
                                                    <option value="RESIDENCIAL">Residencial</option>
                                                    <option value="COMERCIAL">Comercial</option>
                                                    <option value="RECADO">Recado</option>
                                                </select>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModal{{$cliente->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-18">edit</i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$cliente->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edição de Cliente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/clientes/editar/{{$cliente->id}}">
                                                @csrf
                                                <h5>Pessoais</h5>
                                                <label for="name">Nome</label>
                                                <div>
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nome" required autocomplete="name" autofocus value="{{$cliente->nome}}">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <label for="cpf">CPF</label>
                                                <div>
                                                    <input id="cpfE" type="text" class="form-control @error('cpfE') is-invalid @enderror" name="cpf" autocomplete="cpf" autofocus @if($cliente->nascimento!="") value="{{$cliente->cpf}}" @else placeholder="Digite o CPF" @endif onblur="formatarCpfE()">
                                                    @error('cpfE')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <label for="nascimento">Nascimento</label>
                                                <div>
                                                    <input id="nascimento" type="date" class="form-control @error('nascimento') is-invalid @enderror" name="nascimento" autocomplete="nascimento" @if($cliente->nascimento!="") value="{{date("Y-m-d", strtotime($cliente->nascimento))}}" @endif autofocus>
                                                    @error('nascimento')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <br/>
                                                <h5>Endereço</h5>
                                                <b> <p style="font-size: 80%">Caso saiba o CEP, digite e em seguida os campos serão autocompletados</p></b>
                                                <label>CEP
                                                <input class="form-control" name="cep" type="text" id="cepE" value="{{$cliente->cep}}" size="10" maxlength="9"
                                                onblur="pesquisacepE(this.value);" /></label><br />
                                                <label>Rua:
                                                <input class="form-control" name="rua" type="text" id="ruaE" size="60" value="{{$cliente->rua}}"/></label><br />
                                                <label>Bairro:
                                                <input class="form-control" name="bairro" type="text" id="bairroE" size="40" value="{{$cliente->bairro}}"/></label><br />
                                                <label>Cidade:
                                                <input class="form-control" name="cidade" type="text" id="cidadeE" size="40" value="{{$cliente->cidade}}" /></label><br />
                                                <label>Estado:
                                                <input class="form-control" name="uf" type="text" id="ufE" size="2" value="{{$cliente->uf}}"/></label><br />
                                                <input class="form-control" name="ibge" type="hidden" id="ibgeE" size="8" />
                                                <label for="numero">Número</label>
                                                <input class="form-control" type="text" name="numero" id="numeroE" size="5" value="{{$cliente->numero}}"><br>
                                                <label for="complemento">Complemento</label>
                                                <input class="form-control" type="text" name="complemento" id="complementoE" size="60" value="{{$cliente->complemento}}">
                                                <br/>
                                                <h5 style="color: blue;">Para adicionar novos telefones, entre no icone de "Telefones"</h5>
                                                <div class="modal-footer">
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-6 offset-md-4">
                                                            <button type="submit" class="btn btn-primary">
                                                                {{ __('Salvar') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
            <div class="card-footer">
                {{ $clientes->links() }}
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
