@extends('layouts.app', ["current"=>"despesas"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <b><div class="card-header">
                                Despesas do 
                                <br/>Dia
                            </div></b>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($despesas['despesaDia'], 2, ',', '.') }}</h3>
                            </div>
                            <div class="card-footer text-muted">
                                <a href="/despesas/lancamentos/dia" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Despesas do 
                                <br/>Mês
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($despesas['despesaMes'], 2, ',', '.') }}</h3>
                            </div>
                            <div class="card-footer text-muted">
                                <a href="/despesas/lancamentos/mes" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Despesas em
                                <br/>Aberto
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($despesas['despesaAberto'], 2, ',', '.') }}</h3>
                            </div>
                            <div class="card-footer text-muted">
                                <a href="/despesas/lancamentos" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
