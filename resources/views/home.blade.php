@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
            <div class="card-deck">
                @foreach ($saldos as $saldo)
                @if($saldo->nome==="principal")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <b><div class="card-header">
                                Saldo <br/>Principal
                            </div></b>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                                <a href="/lancamentos" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                @if($saldo->nome==="diaAnteriorServ")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Saldo Dia Anterior
                                <br/>(Serviços)
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                                <a href="/vendas/servicos/diaAnterior" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                @if($saldo->nome==="diaAnteriorProd")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Saldo Dia Anterior
                                <br/>(Produtos)
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                                <a href="/vendas/produtos/diaAnterior" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                @if($saldo->nome==="diaServ")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Saldo Dia
                                <br/>(Serviços)
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                                <a href="/vendas/servicos/dia" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                @if($saldo->nome==="diaProd")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Saldo Dia
                                <br/>(Produtos)
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                                <a href="/vendas/produtos/dia" class="btn btn-sm btn-primary">Detalhes</a>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                @if($saldo->nome==="mes")
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 300px;">
                            <div class="card-header">
                                Saldo <br/>Mês (Serviços & Produtos)
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">R$ {{ number_format($saldo->saldo, 2, ',', '.') }}</h3>
                            </div>
                            <div class="card-footer text-muted">
                                Última atualização: <br/>{{ $saldo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @endif
                @endif
                @endif
                @endif
                @endif
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
