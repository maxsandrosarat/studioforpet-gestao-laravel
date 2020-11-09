@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Novo Agendamento - Dia: {{date("d/m/Y", strtotime($data))}} - Hora: {{date('H:i', strtotime($hora))}}</h3></div>
                <div class="card-body">
                    <form method="POST" action="/agendamentos">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" class="form-control" name="data" id="data" value="{{$data}}"required>
                            <input type="hidden" class="form-control" name="hora" id="hora" value="{{$hora}}" required>
                            <label for="servico">Serviço</label>
                            <select class="custom-select" id="servico" name="servico" onchange="valorServico();" required>
                                <option value="">Selecione o serviço (obrigatório)</option>
                                @foreach ($servs as $servico)
                                    <option value="{{$servico->id}}" title="{{$servico->preco}}">{{$servico->nome}}</option>
                                @endforeach
                            </select>
                            <br/>
                            <label for="valor">Valor: R$</label>
                            <input type="text" class="form-control" name="valor" id="valor" placeholder="Exemplo: 35.5" onblur="getValor('valor')" required><br/>
                            <br/>
                            <label for="select1">Pet já Cadastrado?
                            <select class="custom-select" name="petCadastrado" id="select1" required>
                                <option value="">Selecione</option>
                                <option value="1">SIM</option>
                                <option value="0">NÃO</option>
                            </select></label>
                            <div id="principal1">
                                <div id="1">
                                    <br/>
                                    <select class="custom-select" id="pet" name="pet" onchange="enderecoCliente();" required>
                                        <option value="">Selecione um pet</option>
                                        @foreach ($pets as $pet)
                                        <option value="{{$pet->id}}" title="{{$pet->cliente->rua}}, {{$pet->cliente->numero}} ({{$pet->cliente->complemento}}) - {{$pet->cliente->bairro}} -  {{$pet->cliente->cidade}} - {{$pet->cliente->uf}} - {{$pet->cliente->cep}}">{{$pet->nome}} ({{$pet->cliente->nome}})</option>
                                        @endforeach
                                    </select>
                                    <br/><br/>
                                    <label for="select11">Buscar?
                                    <select class="custom-select" name="buscar1" id="select11" required>
                                        <option value="">Selecione</option>
                                        <option value="1">SIM</option>
                                        <option value="0">NÃO</option>
                                    </select></label>
                                    <br/>
                                    <div id="principal11">
                                        <div id="1">
                                            <br/>
                                            <h5>Endereço</h5>
                                            <p id="endereco"></p>
                                        </div>
                                        <div id="0">
                                        </div>
                                    </div>
                                </div>
                                <div id="0">
                                    <label for="nomeCliente">Nome do Cliente</label>
                                    <input id="nomeCliente" type="text" class="form-control" name="nomeCliente" placeholder="Digite o nome do cliente" required>
                                    <br/>
                                    <label for="telefone">Telefone</label>
                                    <input name="telefone" class="form-control" id="telefone" size="60" onblur="formataNumeroTelefone()" placeholder="Número com DDD, exemplo: 67991234567" required>
                                    <br/>
                                    <label for="nomePet">Nome do Pet</label>
                                    <input id="nomePet" type="text" class="form-control" name="nomePet" placeholder="Digite o nome do pet" required>
                                    <br/>
                                    <label for="select10">Buscar?</label>
                                    <select class="custom-select" name="buscar0" id="select10" required>
                                        <option value="">Selecione</option>
                                        <option value="1">SIM</option>
                                        <option value="0">NÃO</option>
                                    </select>
                                    <br/>
                                    <div id="principal10">
                                        <div id="1">
                                            <br/>
                                            <h5>Endereço</h5>
                                            <b> <p style="font-size: 80%">Caso saiba o CEP, digite e em seguida os campos serão autocompletados</p></b>
                                            <label>CEP
                                            <input class="form-control" name="cep" type="text" id="cep" value="" size="10" maxlength="9"
                                            onblur="pesquisacep(this.value);" /></label><br />
                                            <label>Rua:
                                            <input class="form-control" name="rua" type="text" id="rua" size="60" required/></label><br />
                                            <label>Bairro:
                                            <input class="form-control" name="bairro" type="text" id="bairro" size="40" required/></label><br />
                                            <label>Cidade:
                                            <input class="form-control" name="cidade" type="text" id="cidade" size="40"/></label><br />
                                            <label>Estado:
                                            <input class="form-control" name="uf" type="text" id="uf" size="2"/></label><br />
                                            <input class="form-control" name="ibge" type="hidden" id="ibge" size="8"/>
                                            <label for="numero">Número
                                            <input class="form-control" type="text" name="numero" id="numero" size="5" required><br></label>
                                            <label for="complemento">Complemento</label>
                                            <input class="form-control" type="text" name="complemento" id="complemento" size="60">
                                        </div>
                                        <div id="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                        </div>
                        <label for="observacao">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" rows="5" cols="20" maxlength="500" placeholder="Digite uma observação, caso necessário (opcional)"></textarea> 
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cadastrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
<a href="/agendamentos" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
