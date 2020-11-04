<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoPlano extends Model
{
    use HasFactory;

    public function pet(){
        return $this->belongsTo('App\Models\Pet');
    }

    public function plano(){
        return $this->belongsTo('App\Models\Plano');
    }
}
