<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
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
