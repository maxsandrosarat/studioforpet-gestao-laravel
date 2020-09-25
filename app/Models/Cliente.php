<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    function telefones(){
        return $this->belongsToMany("App\Models\Telefone", "cliente_telefones");
    }
}
