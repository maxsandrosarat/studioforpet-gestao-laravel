<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Raca;
use App\Models\Saldo;
use App\Models\Servico;
use App\Models\TipoAnimal;
use App\Models\VendaProduto;
use App\Models\VendaServico;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $validador = 0;

        $validador = Servico::count();
        if($validador == 0){
            $serv1 = new Servico();
            $serv1->nome = "Banho";
            $serv1->preco = 35;
            $serv1->save();
            $serv2 = new Servico();
            $serv2->nome = "Banho & Tosa";
            $serv2->preco = 45;
            $serv2->save();
        }
        
        $validador = 0;

        $validador = Raca::count();
        if($validador == 0){
            $raca = new Raca();
            $raca->nome = "Persa";
            $raca->save();
            $raca1 = new Raca();
            $raca1->nome = "Mestiço (Vira Lata)";
            $raca1->save();
            $raca2 = new Raca();
            $raca2->nome = "Lhasa Apso";
            $raca2->save();
            $raca3 = new Raca();
            $raca3->nome = "Shih-tzu";
            $raca3->save();
            $raca4 = new Raca();
            $raca4->nome = "Poodle";
            $raca4->save();
            $raca5 = new Raca();
            $raca5->nome = "Dachshund (Salsicha)";
            $raca5->save();
        }

        $validador = 0;

        $validador = TipoAnimal::count();
        if($validador == 0){
            $tipo1 = new TipoAnimal();
            $tipo1->nome = "Cachorro";
            $tipo1->save();
            $tipo2 = new TipoAnimal();
            $tipo2->nome = "Gato";
            $tipo2->save();
        }

        $validador = 0;

        $validador = Categoria::count();
        if($validador == 0){
            $cat1 = new Categoria();
            $cat1->nome = "Shampoo";
            $cat1->save();
            $cat2 = new Categoria();
            $cat2->nome = "Condicionador";
            $cat2->save();
            $cat3 = new Categoria();
            $cat3->nome = "Perfume";
            $cat3->save();
            $cat4 = new Categoria();
            $cat4->nome = "Acessório";
            $cat4->save();
        }

        $validador = 0;
        
        $validador = Saldo::count();
        if($validador == 0){
            $saldoPrincipal = new Saldo();
            $saldoPrincipal->nome = "principal";
            $saldoPrincipal->save();
            $saldoDiaServ = new Saldo();
            $saldoDiaServ->nome = "diaServ";
            $saldoDiaServ->save();
            $saldoDiaProd = new Saldo();
            $saldoDiaProd->nome = "diaProd";
            $saldoDiaProd->save();
            $saldoMes = new Saldo();
            $saldoMes->nome = "mes";
            $saldoMes->save();
            $saldoDiaAnteriorServ = new Saldo();
            $saldoDiaAnteriorServ->nome = "diaAnteriorServ";
            $saldoDiaAnteriorServ->save();
            $saldoDiaAnteriorProd = new Saldo();
            $saldoDiaAnteriorProd->nome = "diaAnteriorProd";
            $saldoDiaAnteriorProd->save();
        }
        $dataAtual = date("Y-m-d");
        $dataAnterior = gmdate("Y-m-d", time()-(3600*27));
        $mesAtual = date("m");
        $ultimoDiaMes = date("t", mktime(0,0,0,$mesAtual,'01',date("Y")));

        $vendServDia = VendaServico::whereBetween('created_at',["$dataAtual"." 00:00", "$dataAtual"." 23:59"])->sum('valor') - VendaServico::whereBetween('created_at',["$dataAtual"." 00:00", "$dataAtual"." 23:59"])->sum('desconto');
        $saldoDiaServ = Saldo::find(2);
        $saldoDiaServ->saldo = $vendServDia;
        $saldoDiaServ->save();

        $vendProdDia = VendaProduto::whereBetween('created_at',["$dataAtual"." 00:00", "$dataAtual"." 23:59"])->sum('valor') - VendaProduto::whereBetween('created_at',["$dataAtual"." 00:00", "$dataAtual"." 23:59"])->sum('desconto');
        $saldoDiaProd = Saldo::find(3);
        $saldoDiaProd->saldo = $vendProdDia;
        $saldoDiaProd->save();

        $vendServMes = VendaServico::whereBetween('created_at',["2020-"."$mesAtual"."-01"." 00:00", "2020-"."$mesAtual"."-"."$ultimoDiaMes"." 23:59"])->sum('valor') - VendaServico::whereBetween('created_at',["2020-"."$mesAtual"."-01"." 00:00", "2020-"."$mesAtual"."-"."$ultimoDiaMes"." 23:59"])->sum('desconto');
        $vendProdMes = VendaProduto::whereBetween('created_at',["2020-"."$mesAtual"."-01"." 00:00", "2020-"."$mesAtual"."-"."$ultimoDiaMes"." 23:59"])->sum('valor') - VendaProduto::whereBetween('created_at',["2020-"."$mesAtual"."-01"." 00:00", "2020-"."$mesAtual"."-"."$ultimoDiaMes"." 23:59"])->sum('desconto');
        $totalVend = $vendServMes + $vendProdMes;
        $saldoMes = Saldo::find(4);
        $saldoMes->saldo = $totalVend;
        $saldoMes->save();

        $vendServ = VendaServico::whereBetween('created_at',["$dataAnterior"." 00:00", "$dataAnterior"." 23:59"])->sum('valor') - VendaServico::whereBetween('created_at',["$dataAnterior"." 00:00", "$dataAnterior"." 23:59"])->sum('desconto');
        $saldoDiaAnteriorServ = Saldo::find(5);
        $saldoDiaAnteriorServ->saldo = $vendServ;
        $saldoDiaAnteriorServ->save();

        $vendProd = VendaProduto::whereBetween('created_at',["$dataAnterior"." 00:00", "$dataAnterior"." 23:59"])->sum('valor') - VendaProduto::whereBetween('created_at',["$dataAnterior"." 00:00", "$dataAnterior"." 23:59"])->sum('desconto');
        $saldoDiaAnteriorProd = Saldo::find(6);
        $saldoDiaAnteriorProd->saldo = $vendProd;
        $saldoDiaAnteriorProd->save();

        

        $saldos = Saldo::all();
        return view('home',compact('saldos','vendServ'));
    }
}
