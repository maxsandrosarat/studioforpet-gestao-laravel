@extends('layouts.app_admin', ["current"=>"relatorios"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Por Produtos</h5>
                            <p class="card-text">
                                Veja o relatório por produtos!
                            </p>
                            <a href="/admin/relatorios/vendas/produtos" class="btn btn-primary">Verificar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Por Clientes</h5>
                            <p class="card-text">
                                Veja o relatório por clientes!
                            </p>
                            <a href="/admin/relatorios/vendas/clientes" class="btn btn-primary">Verificar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Por Clientes & Produtos</h5>
                            <p class="card-text">
                                Veja o relatório por clientes & produtos!
                            </p>
                            <a href="/admin/relatorios/vendas/clientesProdutos" class="btn btn-primary">Verificar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection