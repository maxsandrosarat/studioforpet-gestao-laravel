<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    public function raca(){
        return $this->belongsTo('App\Models\Raca');
    }

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente');
    }

    public function plano(){
        return $this->belongsTo('App\Models\Plano');
    }

    function pagamentos(){
        return $this->belongsToMany("App\Models\PagamentoPlano", "pagamento_planos")->withPivot('mes','vencimento','valorPago','created_at');
    }
}
