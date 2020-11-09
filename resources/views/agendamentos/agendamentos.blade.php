@extends('layouts.app', ["current"=>"agendamentos"])

@section('body')
<div class="card border">
    <div class="card-body">
        <h5 class="card-title">Agendamentos</h5>
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
        <div class="card border">
            <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/agendamentos/filtro">
                @csrf
                <label for="data">A partir de
                <input class="form-control" type="date" name="data"></label>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
        </div>
        <br/>
        <div class="table-responsive-xl">
            <table class="table table-bordered" style="text-align: center;">
                <thead class="thead-dark thead-bordered">
                    <tr>
                        <th>Horários</th>
                        @for ($i = 0; $i < 7; $i++)
                        @php
                            $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + '.$i.' days'));
                            $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($dataSemana)); 
                        @endphp
                        <th>{{date("d/m/Y", strtotime($dataSemana))}} <br/>({{$diasemana[$diasemana_numero]}})</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 11; $i++)
                    <tr>
                        @php
                            $horaInicio = "00:00:00";
                            $hora = gmdate('H:i:s', strtotime( "$horaInicio" ) + strtotime( "$i:00:00" ) );
                        @endphp
                        <td id="primeiraColuna">{{date('H:i', strtotime($hora))}}</td>
                        @for ($j = 0; $j < 7; $j++)
                            @php
                                $qtdAged = 0;
                                $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + '.$j.' days'));
                            @endphp
                            <td id="celulas">
                            <!-- Modal -->
                                 <div class="modal fade" id="exampleModalQtd{{$i}}{{$j}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                 <div class="modal-dialog">
                                     <div class="modal-content">
                                     <div class="modal-header">
                                         <h5 class="modal-title" id="exampleModalLabel">Agendamento - Dia: {{date("d/m/Y", strtotime($dataSemana))}} - Hora: {{date('H:i', strtotime($hora))}}</h5>
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                         </button>
                                     </div>
                                     <div class="modal-body">
                                @foreach ($agends as $agend)
                                @if($agend->data==$dataSemana && $agend->hora==$hora)
                                 @php
                                    if($agend->status!="CANCELADO"){
                                        $qtdAged++;
                                    }
                                 @endphp
                                 <div class="card">
                                    <div class="card-header font-weight-bolder">
                                        @if($agend->pet_cadastrado==1) Pet: {{$agend->pet->nome}} ({{$agend->pet->cliente->nome}}) @else Pet: {{$agend->nome_pet}} ({{$agend->nome_cliente}}) @endif
                                    </div>
                                    <div class="card-body">
                                        <p class="font-weight-bolder">
                                            Serviço: {{$agend->servico->nome}} <br/>
                                            Valor: {{ 'R$ '.number_format($agend->valor, 2, ',', '.')}} <br/>
                                            @if($agend->pet_cadastrado==1)
                                                Buscar: @if($agend->buscar==1) Sim @else Não @endif <br/>
                                                @if($agend->buscar==1)
                                                Endereço: {{$agend->pet->cliente->rua}}, {{$agend->pet->cliente->numero}} ({{$agend->pet->cliente->complemento}}) - {{$agend->pet->cliente->bairro}}
                                                @endif
                                            @else
                                            Telefone: {{$agend->telefone}}<br/>
                                            Buscar: @if($agend->buscar==1) Sim @else Não @endif <br/>
                                            @if($agend->buscar==1)
                                            Endereço: {{$agend->rua}}, {{$agend->numero}} ({{$agend->complemento}}) - {{$agend->bairro}}
                                            @endif
                                            @endif
                                            @if($agend->observacao!="")
                                            <br/> Observação: {{$agend->observacao}}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        @if($agend->status=="CANCELADO")
                                        <div class="alert alert-danger" role="alert">
                                            CANCELADO
                                        </div>
                                        @else
                                            @if($agend->status=="ATENDIDO")
                                            <div class="alert alert-success" role="alert">
                                                ATENDIDO
                                            </div>
                                            @else
                                            <a href="/agendamentos/atendido/{{$agend->id}}" class="badge badge-success" data-toggle="tooltip" data-placement="right" title="Marcar Como Atendido">Atendido</a>
                                            <a href="/agendamentos/cancelar/{{$agend->id}}" class="badge badge-danger" data-toggle="tooltip" data-placement="right" title="Marcar Como Cancelado">Cancelar</a>
                                            @endif
                                        @endif
                                    </div>
                                  </div>
                                  <br/>
                                @endif
                                @endforeach
                                
                                <a href="/agendamentos/novo/{{$dataSemana}}/{{$hora}}" class="badge badge-primary" data-toggle="tooltip" data-placement="right" title="Novo Agendamento">Agendar</a>
                                </div>
                                </div>
                            </div>
                            </div>
                            
                                <button type="button" class="btn @if($qtdAged<=0) btn-success @else @if($qtdAged>=1 && $qtdAged<=2) btn-warning @else btn-danger @endif @endif btn-sm" data-toggle="modal" data-target="#exampleModalQtd{{$i}}{{$j}}">
                                    <span class="badge badge-pill badge-light">{{$qtdAged}}</span>
                                </button>
                            </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection