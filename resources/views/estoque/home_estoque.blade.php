@extends('layouts.app', ["current"=>"estoque"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Lançamentos</h5>
                            <p class="card-text">
                                Cadastrar entrada ou saída em produtos! 
                            </p>
                            <a href="/estoque/lancamentos" class="btn btn-primary">Cadastrar</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 255px;">
                        <div class="card-body">
                            <h5>Histórico</h5>
                            <p class="card-text">
                                Consulte o histórico das entradas e saídas de produtos! 
                            </p>
                            <a href="/estoque/historicos" class="btn btn-primary">Consultar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection