@extends('layouts.app', ["current"=>"vendas"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Serviços</h5>
                            <p class="card-text">
                                Gerencie suas Vendas de Serviços! 
                            </p>
                            <a href="/vendas/servicos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Produtos</h5>
                            <p class="card-text">
                                Gerencie suas Vendas de Produtos! 
                            </p>
                            <a href="/vendas/produtos" class="btn btn-primary">Gerenciar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection