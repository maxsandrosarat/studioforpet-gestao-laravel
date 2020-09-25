@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Clientes</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalCad" data-toggle="tooltip" data-placement="bottom" title="Cadastrar Novo Cliente">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if(count($clientes)==0)
                <div class="alert alert-danger" role="alert">
                    Sem clientes cadastrados!
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{$cliente->id}}</td>
                        <td>{{$cliente->nome}}</td>
                        <td>{{$cliente->cpf}}</td>
                        <td>{{date("d/m/Y", strtotime($cliente->nascimento))}}</td>
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
                            <div class="modal fade" id="exampleModalTel{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cliente->telefones as $telefone)
                                            @if($telefone->ativo==true) <tr style="color:green;"> @else <tr style="color:red;"> @endif
                                                <td>{{$telefone->numero}}</td>
                                                <td>{{$telefone->tipo}}</td>
                                                <td>@if($telefone->ativo==true) ATIVO @else INATIVO @endif</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                                <H5>Cadastrar Novo Número</H5>
                                <div class="modal-body">
                                    <form method="post" action="/telefones/{{$cliente->id}}">
                                        @csrf
                                            <label for="telefone">Número do Telefone
                                            <input name="telefone" class="form-control" id="telefone" size="60" onblur="formataNumeroTelefone()" placeholder="Número com DDD, exemplo: 67991234567"></label>
                                            <br/>
                                            <label for="tipo" class="col-sm-2 col-form-label">Tipo
                                            <select class="form-control" name="tipo" id="tipo" required>
                                                <option value="PESSOAL">Pessoal</option>
                                                <option value="RESIDENCIAL">Residencial</option>
                                                <option value="COMERCIAL">Comercial</option>
                                                <option value="RECADO">Recado</option>
                                            </select></label>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                </div>
                            </form>
                                </div>
                            </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $clientes->links() }}
        </div>
    </div>
    <br>
    <a href="/cadastros" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cadastro de Aluno</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form method="POST" action="/clientes" enctype="multipart/form-data">
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
                            <b> <p style="font-size: 80%">Caso saiba o CEP, digite (apenas números) e em seguida os campos serão autocompletados</p></b>
                            <label>CEP
                            <input class="form-control" name="cep" type="number" id="cep" value="" size="10" maxlength="9"
                            onblur="pesquisacep(this.value);" /></label><br />
                            <label>Rua:
                            <input class="form-control" name="rua" type="text" id="rua" size="60" required/></label><br />
                            <label>Bairro:
                            <input class="form-control" name="bairro" type="text" id="bairro" size="40" required/></label><br />
                            <label>Cidade:
                            <input class="form-control" name="cidade" type="text" id="cidade" size="40" required /></label><br />
                            <label>Estado:
                            <input class="form-control" name="uf" type="text" id="uf" size="2" required/></label><br />
                            <input class="form-control" name="ibge" type="hidden" id="ibge" size="8" />
                            <label for="numero">Número</label>
                            <input class="form-control" type="number" name="numero" id="numero" size="5"><br>
                            <label for="complemento">Complemento</label>
                            <input class="form-control" type="text" name="complemento" id="complemento" size="60">
                            
                            <br/>
                            <h5>Telefone</h5>
                            <label for="telefone">Número do Telefone</label>
                                <input name="telefone" class="form-control" id="telefone" size="60" onblur="formataNumeroTelefone()" placeholder="Número com DDD, exemplo: 67991234567">
                                <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                                <select class="form-control" name="tipo" id="tipo" required>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
