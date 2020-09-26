@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Clientes</h5>
                            <p class="card-text">
                                Gerencie seus clientes! 
                            </p>
                            <a href="/clientes" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Pets</h5>
                            <p class="card-text">
                                Gerencie seus pets! 
                            </p>
                            <a href="/pets" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Serviços</h5>
                            <p class="card-text">
                                Gerencie seus Serviços de Estética! 
                            </p>
                            <a href="/servicos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Produtos</h5>
                            <p class="card-text">
                                Gerencie seus Produtos!
                            </p>
                            <a href="/produtos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Categorias</h5>
                            <p class="card-text">
                                Gerencie suas Categorias!
                            </p>
                            <a href="/categorias" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Marcas</h5>
                            <p class="card-text">
                                Gerencie suas Marcas! 
                            </p>
                            <a href="/marcas" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Tipos de Animais</h5>
                            <p class="card-text">
                                Gerencie seus Tipos de Animais! 
                            </p>
                            <a href="/tiposAnimais" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Raças</h5>
                            <p class="card-text">
                                Gerencie suas raças!
                            </p>
                            <a href="/racas" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection