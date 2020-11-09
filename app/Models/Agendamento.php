<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    public function servico(){
        return $this->belongsTo('App\Models\Servico');
    }

    public function pet(){
        return $this->belongsTo('App\Models\Pet');
    }
    
}
