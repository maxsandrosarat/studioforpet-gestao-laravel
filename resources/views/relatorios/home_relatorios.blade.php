@extends('layouts.app_admin', ["current"=>"relatorios"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Estoque</h5>
                            <p class="card-text">
                                Veja suas entradas e saídas!
                            </p>
                            <a href="/admin/relatorios/estoque" class="btn btn-primary">Verificar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Vendas</h5>
                            <p class="card-text">
                                Veja o andamento das suas vendas!
                            </p>
                            <a href="/admin/relatorios/vendas" class="btn btn-primary">Verificar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection