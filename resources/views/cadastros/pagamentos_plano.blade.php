@extends('layouts.app', ["current"=>"cadastros"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Histórico de Pagamento - Pet: {{$pet->nome}} ({{$pet->raca->nome}}) - Cliente: {{$pet->cliente->nome}}</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Lançar Pagamento">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lançamento de Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border">
                            <div class="card-body">
                                <form action="/pets/pagar/{{$pet->id}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <p>Plano Atual: {{$pet->plano->nome}}</p>
                                        <input type="hidden" name="plano" value="{{$pet->plano->id}}">
                                        <label for="valor">Valor Pago:</label>
                                        <input type="text" class="form-control" name="valor" id="valor" value="{{$pet->valorPlano}}" required>
                                        <label for="formaPagamento">Forma Pagamento</label>
                                        <select class="custom-select" id="formaPagamento" name="formaPagamento" required>
                                            <option value="">Selecione a forma (obrigatório)</option>
                                            <option value="Dinheiro">Dinheiro</option>
                                            <option value="Débito">Débito</option>
                                            <option value="Crédito à Vista">Crédito à Vista</option>
                                            <option value="Crédito Parcelado">Crédito Parcelado</option>
                                        </select>
                                        <br/>
                                        <label for="observacao">Observação</label>
                                        <textarea class="form-control" name="observacao" id="observacao" rows="5" cols="20" maxlength="500" placeholder="Digite uma observação, caso necessário (opcional)"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @if(count($pgtos)==0)
                <div class="alert alert-danger" role="alert">
                    Sem históricos para exibir!
                </div>
            @else
            <h5>Exibindo {{$pgtos->count()}} de {{$pgtos->total()}} de pgtos ({{$pgtos->firstItem()}} a {{$pgtos->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Plano</th>
                        <th>Data Pagamento</th>
                        <th>Valor Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pgtos as $pgto)
                    <tr>
                        <td>{{$pgto->id}}</td>
                        <td>{{$pgto->plano->nome}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($pgto->created_at))}}</td>
                        <td>{{ 'R$ '.number_format($pgto->valorPago, 2, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $pgtos->links() }}
        </div>
    </div>
    <br>
    <a href="/pets" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
