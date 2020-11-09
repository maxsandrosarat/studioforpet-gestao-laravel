<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ClienteTelefone;
use App\Models\Despesa;
use App\Models\EntradaSaida;
use App\Models\Historico;
use App\Models\Lancamento;
use App\Models\Marca;
use App\Models\PagamentoPlano;
use App\Models\Pet;
use App\Models\Plano;
use App\Models\Produto;
use App\Models\Raca;
use App\Models\Saldo;
use App\Models\Servico;
use App\Models\Telefone;
use App\Models\TipoAnimal;
use App\Models\User;
use App\Models\VendaProduto;
use App\Models\VendaServico;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cadastros(){
        return view('cadastros.home_cadastros');
    }

    public function vendas(){
        return view('vendas.home_vendas');
    }

    public function estoque(){
        return view('estoque.home_estoque');
    }

    public function despesas(){
        $data = date("Y-m-d");
        $mesAtual = date("m");
        $ultimoDiaMes = date("t", mktime(0,0,0,$mesAtual,'01',date("Y")));
        $totalDia = 0;
        $totalMes = 0;
        $totalAberto = 0;

        $despsDia = Despesa::where('vencimento',"$data")->get();
        foreach ($despsDia as $desp) {
            if($desp->parcelado==1){
                $totalDia += $desp->valorParcela;
            } else {
                $totalDia += $desp->valorTotal;
            }
        }

        $despsMes = Despesa::whereBetween('vencimento',[date("Y")."-"."$mesAtual"."-01", date("Y")."-"."$mesAtual"."-"."$ultimoDiaMes"])->get();
        foreach ($despsMes as $desp) {
            if($desp->parcelado==1){
                $totalMes += $desp->valorParcela;
            } else {
                $totalMes += $desp->valorTotal;
            }
        }

        $despsAberto = Despesa::where('pago',false)->get();
        foreach ($despsAberto as $desp) {
            if($desp->parcelado==1){
                $totalAberto += $desp->valorParcela;
            } else {
                $totalAberto += $desp->valorTotal;
            }
        }
        $despesas = [
            "despesaDia" => $totalDia,
            "despesaMes"  => $totalMes,
            "despesaAberto" => $totalAberto,
        ];
        return view('despesas.home_despesas',compact('despesas'));
    }


    //HISTÓRICOS
    public function historicos(){
        $view = "inicial";
        $users = User::orderBy('name')->get();
        $acoes = DB::table('historicos')->select(DB::raw("acao"))->groupBy('acao')->get();
        $referencias = DB::table('historicos')->select(DB::raw("referencia"))->groupBy('referencia')->get();
        $hists = Historico::orderBy('created_at', 'desc')->paginate(20);
        return view('historico.historico',compact('view','users','acoes','referencias','hists'));
    }

    public function filtroHistoricos(Request $request)
    {
        $acao = $request->input('acao');
        $referencia = $request->input('referencia');
        if($request->input('user')!=""){
            $userId = $request->input('user');
            $user = User::find($userId);
            $usuario = $user->name;
        }
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($referencia)){
            if(isset($acao)){
                if(isset($user)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('acao','like',"%$acao%")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($user)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->where('usuario','like',"$usuario")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('referencia','like',"%$referencia%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('referencia','like',"%$referencia%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            return redirect('/historicos');
                        }
                    }
                }
            }
        } else {
            if(isset($acao)){
                if(isset($user)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('acao','like',"%$acao%")->where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('acao','like',"%$acao%")->where('usuario','like',"$usuario")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('acao','like',"%$acao%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('acao','like',"%$acao%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('acao','like',"%$acao%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('acao','like',"%$acao%")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($user)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::where('usuario','like',"$usuario")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $hists = Historico::whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $hists = Historico::whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $hists = Historico::whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            return redirect('/historicos');
                        }
                    }
                }
            }
        }
        $view = "filtro";
        $users = User::orderBy('name')->get();
        $acoes = DB::table('historicos')->select(DB::raw("acao"))->groupBy('acao')->get();
        $referencias = DB::table('historicos')->select(DB::raw("referencia"))->groupBy('referencia')->get();
        return view('historico.historico',compact('view','users','acoes','referencias','hists'));
    }

    //CATEGORIA
    public function indexCategorias()
    {
        $cats = Categoria::all();
        return view('cadastros.categorias',compact('cats'));
    }

    public function cadastrarCategoria(Request $request)
    {
        $cat = new Categoria();
        $cat->nome = $request->input('nomeCategoria');
        $cat->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Categoria";
        $hist->codigo = $cat->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back()->with('mensagem', 'Categoria Cadastrada com Sucesso!');
    }

    public function editarCategoria(Request $request, $id)
    {
        $cat = Categoria::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Categoria";
        $hist->codigo = $cat->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($cat)){
            $cat->nome = $request->input('nomeCategoria');
            $cat->save();
        }
        
        return back()->with('mensagem', 'Categoria Alterada com Sucesso!');
    }

    public function apagarCategoria($id)
    {
        $cat = Categoria::find($id);

        if(isset($cat)){
            if($cat->ativo==1){
                $cat->ativo = false;
                $cat->save();

                $hist = new Historico();
                $hist->acao = "Inativou";
                $hist->referencia = "Categoria";
                $hist->codigo = $cat->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Categoria Inativada com Sucesso!');
            } else {
                $cat->ativo = true;
                $cat->save();

                $hist = new Historico();
                $hist->acao = "Ativou";
                $hist->referencia = "Categoria";
                $hist->codigo = $cat->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Categoria Ativada com Sucesso!');
            }
        }
        
        return back();
    }


    //TIPO ANIMAL
    public function indexTiposAnimal()
    {
        $tipos = TipoAnimal::all();
        return view('cadastros.tipos_animais',compact('tipos'));
    }

    public function cadastrarTipoAnimal(Request $request)
    {
        $tipo = new TipoAnimal();
        $tipo->nome = $request->input('nome');
        $tipo->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Tipo de Animal";
        $hist->codigo = $tipo->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Tipo de Animal Cadastrado com Sucesso!');
    }

    public function editarTipoAnimal(Request $request, $id)
    {
        $tipo = TipoAnimal::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Tipo de Animal";
        $hist->codigo = $tipo->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($tipo)){
            $tipo->nome = $request->input('nome');
            $tipo->save();
        }

        return back()->with('mensagem', 'Tipo de Animal Alterado com Sucesso!');
    }


    //MARCA
    public function indexMarcas()
    {
        $marcas = Marca::all();
        return view('cadastros.marcas',compact('marcas'));
    }

    public function cadastrarMarca(Request $request)
    {
        $marca = new Marca();
        $marca->nome = $request->input('nome');
        $marca->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Marca";
        $hist->codigo = $marca->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Marca Cadastrada com Sucesso!');
    }

    public function editarMarca(Request $request, $id)
    {
        $marca = Marca::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Marca";
        $hist->codigo = $marca->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($marca)){
            $marca->nome = $request->input('nome');
            $marca->save();
        }
        
        return back()->with('mensagem', 'Marca Alterada com Sucesso!');
    }

    public function apagarMarca($id)
    {
        $marca = Marca::find($id);

        if(isset($marca)){
            if($marca->ativo==1){
                $marca->ativo = false;
                $marca->save();

                $hist = new Historico();
                $hist->acao = "Inativou";
                $hist->referencia = "Marca";
                $hist->codigo = $marca->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Marca Inativada com Sucesso!');
            } else {
                $marca->ativo = true;
                $marca->save();

                $hist = new Historico();
                $hist->acao = "Ativou";
                $hist->referencia = "Marca";
                $hist->codigo = $marca->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Marca Ativada com Sucesso!');
            }
            
        }
        return back();
    }


    //PRODUTO
    public function indexProdutos()
    {
        $view = "inicial";
        $prods = Produto::paginate(20);
        $tipos = TipoAnimal::orderBy('nome')->get();
        $marcas = Marca::where('ativo',true)->orderBy('nome')->get();
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('view','prods','tipos','marcas','cats'));
    }

    public function cadastrarProduto(Request $request)
    {
        $prod = new Produto();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_produtos','public');
            $prod->foto = $path;
        }
        if($request->input('nome')!=""){
            $prod->nome = $request->input('nome');
        }
        if($request->input('tipo')!=""){
        $prod->tipo_animal_id = $request->input('tipo');
        }
        if($request->input('fase')!=""){
        $prod->tipo_fase = $request->input('fase');
        }
        if($request->input('marca')!=""){
        $prod->marca_id = $request->input('marca');
        }
        if($request->input('embalagem')!=""){
        $prod->embalagem = $request->input('embalagem');
        }
        if($request->input('preco')!=""){
        $prod->preco = $request->input('preco');
        }
        if($request->input('estoque')!=""){
        $prod->estoque = $request->input('estoque');
        }
        if($request->input('categoria')!=""){
            $prod->categoria_id = $request->input('categoria');
        }
        if($request->input('ativo')!=""){
            $prod->ativo = $request->input('ativo');
        }
        $prod->save();
        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Produto";
        $hist->codigo = $prod->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back()->with('mensagem', 'Produto Cadastrado com Sucesso!');
    }

    public function editarProduto(Request $request, $id)
    {
        $prod = Produto::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Produto";
        $hist->codigo = $prod->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($prod)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($prod->foto);
                $path = $request->file('foto')->store('fotos_produtos','public');
                $prod->foto = $path;
            }
            if($request->input('nome')!=""){
                $prod->nome = $request->input('nome');
            }
            if($request->input('tipo')!=""){
            $prod->tipo_animal_id = $request->input('tipo');
            }
            if($request->input('fase')!=""){
            $prod->tipo_fase = $request->input('fase');
            }
            if($request->input('marca')!=""){
            $prod->marca_id = $request->input('marca');
            }
            if($request->input('embalagem')!=""){
            $prod->embalagem = $request->input('embalagem');
            }
            if($request->input('preco')!=""){
            $prod->preco = $request->input('preco');
            }
            if($request->input('estoque')!=""){
            $prod->estoque = $request->input('estoque');
            }
            if($request->input('categoria')!=""){
                $prod->categoria_id = $request->input('categoria');
            }
            if($request->input('ativo')!=""){
                $prod->ativo = $request->input('ativo');
            }
            $prod->save();
        }
        
        return back()->with('mensagem', 'Produto Alterado com Sucesso!');
    }

    public function apagarProduto($id)
    {
        $prod = Produto::find($id);

        if(isset($prod)){
            if($prod->ativo==1){
                $prod->ativo = false;
                $prod->save();

                $hist = new Historico();
                $hist->acao = "Inativou";
                $hist->referencia = "Produto";
                $hist->codigo = $prod->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Produto Inativado com Sucesso!');
            } else {
                $prod->ativo = true;
                $prod->save();

                $hist = new Historico();
                $hist->acao = "Ativou";
                $hist->referencia = "Produto";
                $hist->codigo = $prod->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Produto Ativado com Sucesso!');
            }
        }
        
        return back();
    }

    public function filtroProduto(Request $request)
    {
        $nome = $request->input('nome');
        $cat = $request->input('categoria');
        $tipo = $request->input('tipo');
        $fase = $request->input('fase');
        $marca = $request->input('marca');
        if(isset($nome)){
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(100);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_animal_id',"$tipo")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100); 
                        }
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(100); 
                        }
                    }
                }
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100); 
                        }
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(100); 
                        }
                    }
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100); 
                        }
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            return redirect('/produtos');
                        }
                    }
                }
            }
        }
        
        $view = "filtro";
        $tipos = TipoAnimal::orderBy('nome')->get();
        $marcas = Marca::where('ativo',true)->orderBy('nome')->get();
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('view','prods','tipos','marcas','cats'));
    }


    //ESTOQUE
    public function indexEstoque()
    {
        $view = "inicial";
        $prods = Produto::paginate(20);
        $tipos = TipoAnimal::all();
        $marcas = Marca::all();
        $cats = Categoria::all();
        return view('estoque.estoque_produtos',compact('view','prods','tipos','marcas','cats'));
    }

    public function entradaEstoque(Request $request, $id)
    {
        $prod = Produto::find($id);

        $hist = new Historico();
        $hist->acao = "Fez Entrada no Estoque";
        $hist->referencia = "Produto";
        $hist->codigo = $prod->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($prod)){
            if($request->input('qtd')!=""){
                $user = Auth::user();
                $tipo = "entrada";
                $id = $request->input('produto');
                $qtd = $request->input('qtd');
                $es = new EntradaSaida();
                $es->tipo = $tipo;
                $es->produto_id = $id;
                $es->quantidade = $qtd;
                $es->usuario = $user->name;
                $es->motivo = $request->input('motivo');
                $es->save();
                $prod->estoque += $request->input('qtd');
                $prod->save();
            }
        }
        
        return back()->with('mensagem', 'Entrada no Produto efetuada com Sucesso!');
    }

    public function saidaEstoque(Request $request, $id)
    {
        $prod = Produto::find($id);

        $hist = new Historico();
        $hist->acao = "Fez Saída no Estoque";
        $hist->referencia = "Produto";
        $hist->codigo = $prod->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($prod)){
            if($request->input('qtd')!=""){
                $user = Auth::user();
                $tipo = "saida";
                $id = $request->input('produto');
                $qtd = $request->input('qtd');
                $es = new EntradaSaida();
                $es->tipo = $tipo;
                $es->produto_id = $id;
                $es->quantidade = $qtd;
                $es->usuario = $user->name;
                $es->motivo = $request->input('motivo');
                $es->save();
                $prod->estoque -= $request->input('qtd');
                $prod->save();
            }
        }
        
        return back()->with('mensagem', 'Saída no Produto efetuada com Sucesso!');
    }

    public function filtroEstoque(Request $request)
    {
        $nome = $request->input('nome');
        $cat = $request->input('categoria');
        $tipo = $request->input('tipo');
        $fase = $request->input('fase');
        $marca = $request->input('marca');
        if(isset($nome)){
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(100);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(100);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(100);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(100);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(100); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(100);
                        } else {
                            return redirect('/estoque/lancamentos');
                        }
                    }
                }
            }
        }
        $view = "filtro";
        $tipos = TipoAnimal::all();
        $marcas = Marca::all();
        $cats = Categoria::all();
        return view('estoque.estoque_produtos',compact('view','prods','tipos','marcas','cats'));
    }

    //ENTRADAS & SAIDAS
    public function indexEntradaSaidas()
    {
        $rels = EntradaSaida::orderBy('created_at', 'desc')->paginate(20);
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "inicial";
        return view('estoque.entrada_saida', compact('view','rels','prods'));
    }

    public function filtroEntradaSaidas(Request $request)
    {
        $tipo = $request->input('tipo');
        $produto = $request->input('produto');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->paginate(100);
                    }
                }
            }
        } else {
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        return redirect('/estoque/historicos');
                    }
                }
            }
        }
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('estoque.entrada_saida', compact('view','rels','prods'));
    }


    //CLIENTE
    public function indexClientes()
    {
        $view = "inicial";
        $clientes = Cliente::paginate(20);
        return view('cadastros.clientes',compact('view','clientes'));
    }

    public function cadastrarCliente(Request $request)
    {
        $request->validate([
            'nome' => 'unique:clientes',
            'cpf' => 'unique:clientes',
        ], $mensagens =[
            'nome.unique' => 'Já existe um cliente com esse nome!',
            'cpf.unique' => 'Já existe um cliente com esse cpf!',
        ]);

        $cliente = new Cliente();
        $cliente->nome = $request->input('nome');
        $cliente->cpf = $request->input('cpf');
        $cliente->nascimento = $request->input('nascimento');
        $cliente->cep = $request->input('cep');
        $cliente->rua = $request->input('rua');
        $cliente->numero = $request->input('numero');
        $cliente->complemento = $request->input('complemento');
        $cliente->bairro = $request->input('bairro');
        $cliente->cidade = $request->input('cidade');
        $cliente->uf = $request->input('uf');
        $cliente->save();
        $telefone = new Telefone();
        $telefone->numero = $request->input('telefone');
        $telefone->tipo = $request->input('tipo');
        $telefone->save();
        $cliTel = new ClienteTelefone();
        $cliTel->cliente_id = $cliente->id;
        $cliTel->telefone_id = $telefone->id;
        $cliTel->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Cliente";
        $hist->codigo = $cliente->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back()->with('mensagem', 'Cliente Cadastrado com Sucesso!');
    }

    public function editarCliente(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if(isset($cliente)){
            $cliente->nome = $request->input('nome');
            $cliente->cpf = $request->input('cpf');
            $cliente->nascimento = $request->input('nascimento');
            $cliente->cep = $request->input('cep');
            $cliente->rua = $request->input('rua');
            $cliente->numero = $request->input('numero');
            $cliente->complemento = $request->input('complemento');
            $cliente->bairro = $request->input('bairro');
            $cliente->cidade = $request->input('cidade');
            $cliente->uf = $request->input('uf');
            $cliente->save();

            $hist = new Historico();
            $hist->acao = "Alterou";
            $hist->referencia = "Cliente";
            $hist->codigo = $cliente->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }
        
        return back()->with('mensagem', 'Cliente Alterado com Sucesso!');
    }

    public function filtroCliente(Request $request)
    {
        $nome = $request->input('nome');
        if(isset($nome)){
            $clientes = Cliente::where('nome','like',"%$nome%")->paginate(100);
        } else {
            return redirect('/clientes');
        }
        $view = "filtro";
        return view('cadastros.clientes',compact('view','clientes'));
    }

    public function cadastrarTelefone(Request $request, $id)
    {
        $telefone = new Telefone();
        $telefone->numero = $request->input('telefone');
        $telefone->tipo = $request->input('tipo');
        $telefone->save();

        $clienteTelefone = new ClienteTelefone();
        $clienteTelefone->cliente_id = $id;
        $clienteTelefone->telefone_id = $telefone->id;
        $clienteTelefone->save();

        $cliente = Cliente::find($id);

        $hist = new Historico();
        $hist->acao = "Cadastrou Telefone";
        $hist->referencia = "Cliente";
        $hist->codigo = $cliente->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Telefone Cadastrado com Sucesso!');
    }

    public function apagarTelefone($idCli, $idTel)
    {
        $telefone = Telefone::find($idTel);
        if(isset($telefone)){
            $telefone->ativo = false;
            $telefone->save();
        }

        $hist = new Historico();
        $hist->acao = "Inativou Telefone";
        $hist->referencia = "Cliente";
        $hist->codigo = $idCli;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Telefone Inativado com Sucesso!');
    }


    //SERVIÇO
    public function indexServicos()
    {
        $servs = Servico::all();
        return view('cadastros.servicos',compact('servs'));
    }

    public function cadastrarServico(Request $request)
    {
        $serv = new Servico();
        $serv->nome = $request->input('nome');
        $serv->preco = $request->input('preco');
        $serv->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Serviço";
        $hist->codigo = $serv->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Serviço Cadastrado com Sucesso!');
    }

    public function editarServico(Request $request, $id)
    {
        $serv = Servico::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Serviço";
        $hist->codigo = $serv->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($serv)){
            $serv->nome = $request->input('nome');
            $serv->preco = $request->input('preco');
            $serv->save();
        }

        return back()->with('mensagem', 'Serviço Alterado com Sucesso!');
    }


    //RAÇA
    public function indexRacas()
    {
        $racas = Raca::orderBy('nome')->paginate(20);
        return view('cadastros.racas',compact('racas'));
    }

    public function cadastrarRaca(Request $request)
    {
        $raca = new Raca();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_racas','public');
            $raca->foto = $path;
        }
        $raca->nome = $request->input('nome');
        $raca->descricao = $request->input('descricao');
        $raca->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Raça";
        $hist->codigo = $raca->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Raça Cadastrada com Sucesso!');
    }

    public function editarRaca(Request $request, $id)
    {
        $raca = Raca::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Raça";
        $hist->codigo = $raca->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($raca)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($raca->foto);
                $path = $request->file('foto')->store('fotos_racas','public');
                $raca->foto = $path;
            }
            $raca->nome = $request->input('nome');
            $raca->descricao = $request->input('descricao');
            $raca->save();
        }

        return back()->with('mensagem', 'Raça Alterada com Sucesso!');
    }


    //PLANOS
    public function indexPlanos()
    {
        $planos = Plano::paginate(20);
        return view('cadastros.planos',compact('planos'));
    }

    public function cadastrarPlano(Request $request)
    {
        $plano = new Plano();
        $plano->nome = $request->input('nome');
        $plano->descricao = $request->input('descricao');
        $plano->valor = $request->input('valor');
        $plano->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Plano";
        $hist->codigo = $plano->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Plano Cadastrado com Sucesso!');
    }

    public function editarPlano(Request $request, $id)
    {
        $plano = Plano::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Plano";
        $hist->codigo = $plano->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($plano)){
            $plano->nome = $request->input('nome');
            $plano->descricao = $request->input('descricao');
            $plano->valor = $request->input('valor');
            $plano->save();
        }

        return back()->with('mensagem', 'Plano Alterado com Sucesso!');
    }

    public function apagarPlano($id)
    {
        $plano = Plano::find($id);

        if(isset($plano)){
            if($plano->ativo==1){
                $plano->ativo = false;
                $plano->save();

                $hist = new Historico();
                $hist->acao = "Inativou";
                $hist->referencia = "Plano";
                $hist->codigo = $plano->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Plano Inativado com Sucesso!');
            } else {
                $plano->ativo = true;
                $plano->save();

                $hist = new Historico();
                $hist->acao = "Ativou";
                $hist->referencia = "Plano";
                $hist->codigo = $plano->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Plano Ativado com Sucesso!');
            }
        }

        return back();
    }



    //PET
    public function indexPets()
    {
        $view = "inicial";
        $planos = Plano::where('ativo',true)->get();
        $pets = Pet::paginate(20);
        $racas = Raca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('cadastros.pets',compact('view','planos','pets','racas','clientes'));
    }

    public function pagamentosPlano($id)
    {
        $pgtos = PagamentoPlano::where('pet_id',"$id")->paginate(12);
        $pet = Pet::find("$id");
        return view('cadastros.pagamentos_plano',compact('pgtos','pet'));
    }

    public function pagarPlano(Request $request, $id)
    {
        $pgto = new PagamentoPlano();
        $pgto->pet_id = $id;
        $pgto->plano_id = $request->input('plano');
        $pgto->forma_pagamento = $request->input('formaPagamento');
        $pgto->valorPago = $request->input('valor');
        $pgto->observacao = $request->input('observacao');
        $pgto->save();

        $vendaServ = new VendaServico();
        if($request->input('valor')!=""){
            $vendaServ->valor = $request->input('valor');
        }
        if($request->input('observacao')!=""){
            $vendaServ->observacao = $request->input('observacao');
        }
        if($request->input('formaPagamento')!=""){
            $vendaServ->forma_pagamento = $request->input('formaPagamento');
        }
        $vendaServ->servico_id = 1;
        $vendaServ->pet_id = $id;
        $vendaServ->save();

        $pet = Pet::find($id);
        $lanc = new Lancamento();
        $lanc->tipo = "deposito";
        $lanc->valor = $request->input('valor');
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = "Pagamento Plano Pet: ".$pet->nome;
        $lanc->save();

        $saldo = Saldo::find(1);
        $saldo->saldo += $request->input('valor');
        $saldo->save();

        $hist = new Historico();
        $hist->acao = "Recebeu Pagamento Plano";
        $hist->referencia = "Pet";
        $hist->codigo = $pet->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Plano Pago com Sucesso!');
    }

    public function trocarPlano(Request $request, $id)
    {
        $pet = Pet::find($id);
        $pet->plano_id = $request->input('planoId');
        $pet->valorPlano = $request->input('valorPlano');
        $pet->save();

        $hist = new Historico();
        $hist->acao = "Mudou Plano";
        $hist->referencia = "Pet";
        $hist->codigo = $pet->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Plano Alterado com Sucesso!');
    }

    public function reativarPlano(Request $request, $id)
    {
        $pet = Pet::find($id);
        $pet->temPlano = true;
        $pet->plano_id = $request->input('planoId');
        $pet->valorPlano = $request->input('valorPlano');
        $pet->planoCancelado = false;
        $pet->save();

        $hist = new Historico();
        $hist->acao = "Reativou Plano";
        $hist->referencia = "Pet";
        $hist->codigo = $pet->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Plano Reativado com Sucesso!');
    }

    public function cancelarPlano($id)
    {
        $pet = Pet::find($id);

        if(isset($pet)){
            $pet->temPlano = false;
            $pet->plano_id = NULL;
            $pet->valorPlano = 0;
            $pet->planoCancelado = true;
            $pet->save();

            $hist = new Historico();
            $hist->acao = "Cancelou Plano";
            $hist->referencia = "Pet";
            $hist->codigo = $pet->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }

        return back()->with('mensagem', 'Plano Cancelado com Sucesso!');
    }

    public function cadastrarPet(Request $request)
    {
        $pet = new Pet();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_pets','public');
            $pet->foto = $path;
        }
        if($request->input('nome')!=""){
            $pet->nome = $request->input('nome');
        }
        if($request->input('raca')!=""){
            $pet->raca_id = $request->input('raca');
        }
        if($request->input('porte')!=""){
            $pet->porte = $request->input('porte');
        }
        if($request->input('pelo')!=""){
            $pet->pelo = $request->input('pelo');
        }
        if($request->input('cor')!=""){
            $pet->cor = $request->input('cor');
        }
        if($request->input('sexo')!=""){
            $pet->sexo = $request->input('sexo');
        }
        if($request->input('cliente')!=""){
            $pet->cliente_id = $request->input('cliente');
        }
        if($request->input('plano')==1){
            $pet->temPlano = $request->input('plano');
            if($request->input('planoId')!=""){
                $pet->plano_id = $request->input('planoId');
            }
            if($request->input('valorPlano')!=""){
                $pet->valorPlano = $request->input('valorPlano');
            }
        }
        $pet->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Pet";
        $hist->codigo = $pet->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Pet Cadastrado com Sucesso!');
    }

    public function editarPet(Request $request, $id)
    {
        $pet = Pet::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Pet";
        $hist->codigo = $pet->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($pet)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($pet->foto);
                $path = $request->file('foto')->store('fotos_pets','public');
                $pet->foto = $path;
            }
            if($request->input('nome')!=""){
                $pet->nome = $request->input('nome');
            }
            if($request->input('raca')!=""){
                $pet->raca_id = $request->input('raca');
            }
            if($request->input('porte')!=""){
                $pet->porte = $request->input('porte');
            }
            if($request->input('pelo')!=""){
                $pet->pelo = $request->input('pelo');
            }
            if($request->input('cor')!=""){
                $pet->cor = $request->input('cor');
            }
            if($request->input('sexo')!=""){
                $pet->sexo = $request->input('sexo');
            }
            if($request->input('cliente')!=""){
                $pet->cliente_id = $request->input('cliente');
            }
            if($request->input('plano')==1){
                $pet->temPlano = $request->input('plano');
                if($request->input('planoId')!=""){
                    $pet->plano_id = $request->input('planoId');
                }
                if($request->input('valorPlano')!=""){
                    $pet->valorPlano = $request->input('valorPlano');
                }
            } else {
                $pet->plano = 0;
                $pet->plano_id = NULL;
                $pet->valorPlano = 0;
            }
            $pet->save();
        }

        return back()->with('mensagem', 'Pet Alterado com Sucesso!');
    }

    public function filtroPet(Request $request)
    {
        $nome = $request->input('nome');
        $raca = $request->input('raca');
        $cliente = $request->input('cliente');
        if(isset($nome)){
            if(isset($raca)){
                if(isset($cliente)){
                    $pets = Pet::where('nome','like',"%$nome%")->where('raca_id',"$raca")->where('cliente_id',"$cliente")->orderBy('nome')->paginate(100);
                } else {
                    $pets = Pet::where('nome','like',"%$nome%")->where('raca_id',"$raca")->orderBy('nome')->paginate(100); 
                }
            } else {
                if(isset($cliente)){
                    $pets = Pet::where('nome','like',"%$nome%")->where('cliente_id',"$cliente")->orderBy('nome')->paginate(100);
                } else {
                    $pets = Pet::where('nome','like',"%$nome%")->orderBy('nome')->paginate(100);
                }
            }
        } else {
            if(isset($raca)){
                if(isset($cliente)){
                    $pets = Pet::where('raca_id',"$raca")->where('cliente_id',"$cliente")->orderBy('nome')->paginate(100);
                } else {
                    $pets = Pet::where('raca_id',"$raca")->orderBy('nome')->paginate(100); 
                }
            } else {
                if(isset($cliente)){
                    $pets = Pet::where('cliente_id',"$cliente")->orderBy('nome')->paginate(100);
                } else {
                    return redirect('/pets');
                }
            }
        }
        $view = "filtro";
        $planos = Plano::where('ativo',true)->get();
        $racas = Raca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('cadastros.pets',compact('view','planos','pets','racas','clientes'));
    }


    //VENDA DE SERVIÇO
    public function indexVendaServicos()
    {
        $view = "inicial";
        $formas = DB::table('venda_servicos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $vendaServs = VendaServico::orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_servicos',compact('view','formas','pets','servs','vendaServs'));
    }

    public function indexVendaServicosDia()
    {
        $data = date("Y-m-d");
        $view = "inicial";
        $formas = DB::table('venda_servicos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $vendaServs = VendaServico::whereBetween('created_at',["$data"." 00:00", "$data"." 23:59"])->orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_servicos',compact('view','formas','pets','servs','vendaServs'));
    }

    public function indexVendaServicosDiaAnterior()
    {
        $data = date("Y-m-d");
        $diaAnterior = date('Y-m-d', strtotime($data. ' - 1 days'));
        $view = "inicial";
        $formas = DB::table('venda_servicos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $vendaServs = VendaServico::whereBetween('created_at',["$diaAnterior"." 00:00", "$diaAnterior"." 23:59"])->orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_servicos',compact('view','formas','pets','servs','vendaServs'));
    }

    public function cadastrarVendaServico(Request $request)
    {
        $vendaServ = new VendaServico();
        if($request->input('valor')!=""){
            $vendaServ->valor = $request->input('valor');
        }
        if($request->input('desconto')!=""){
            $vendaServ->desconto = $request->input('desconto');
        }
        if($request->input('observacao')!=""){
            $vendaServ->observacao = $request->input('observacao');
        }
        if($request->input('formaPagamento')!=""){
            $vendaServ->forma_pagamento = $request->input('formaPagamento');
        }
        if($request->input('servico')!=""){
            $vendaServ->servico_id = $request->input('servico');
        }
        if($request->input('pet')!=""){
            $vendaServ->pet_id = $request->input('pet');
        }
        $vendaServ->save();

        $lanc = new Lancamento();
        $lanc->tipo = "deposito";
        $lanc->valor = $request->input('valor') - $request->input('desconto');
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = "Venda Serviço";
        $lanc->save();
        $saldo = Saldo::find(1);
        $saldo->saldo += $request->input('valor') - $request->input('desconto');
        $saldo->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Venda Serviço";
        $hist->codigo = $vendaServ->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Venda de Serviço Cadastrada com Sucesso!');
    }

    public function apagarVendaServico($id)
    {
        $vendaServ = VendaServico::find($id);

        $hist = new Historico();
        $hist->acao = "Excluiu";
        $hist->referencia = "Venda Serviço";
        $hist->codigo = $vendaServ->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        $lanc = new Lancamento();
        $lanc->tipo = "retirada";
        $lanc->valor = $vendaServ->valor - $vendaServ->desconto;
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = "Cancelamento Venda Serviço";
        $lanc->save();
        if(isset($vendaServ)){
            $saldo = Saldo::find(1);
            $saldo->saldo -= $vendaServ->valor - $vendaServ->desconto;
            $saldo->save();
            $vendaServ->delete();
        }

        return back()->with('mensagem', 'Venda de Serviço Excluída com Sucesso!');
    }

    public function filtroVendaServico(Request $request)
    {
        $servico = $request->input('servico');
        $pet = $request->input('pet');
        $formaPagamento = $request->input('formaPagamento');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($servico)){
            if(isset($pet)){
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('servico_id',"$servico")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('servico_id',"$servico")->paginate(100);
                        }
                    }
                }
            }
        } else {
            if(isset($pet)){
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('pet_id',"$pet")->where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('pet_id',"$pet")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('pet_id',"$pet")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('pet_id',"$pet")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('pet_id',"$pet")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('pet_id',"$pet")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaServs = VendaServico::whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaServs = VendaServico::whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            return redirect('/vendas/servicos');
                        }
                    }
                }
            }
        }
        $view = "filtro";
        $formas = DB::table('venda_servicos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $total_valor = $vendaServs->sum('valor');
        $total_desconto = $vendaServs->sum('desconto');
        $total_geral = $total_valor - $total_desconto;
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        return view('vendas.venda_servicos',compact('view','formas','total_valor','total_desconto','total_geral','vendaServs','pets','servs'));
    }

    
    //VENDA DE PRODUTO
    public function indexVendaProdutos()
    {
        $view = "inicial";
        $formas = DB::table('venda_produtos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $vendaProds = VendaProduto::orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_produtos',compact('view','formas','clientes','produtos','vendaProds'));
    }

    public function indexVendaProdutosDia()
    {
        $data = date("Y-m-d");
        $view = "inicial";
        $formas = DB::table('venda_produtos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $vendaProds = VendaProduto::whereBetween('created_at',["$data"." 00:00", "$data"." 23:59"])->orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_produtos',compact('view','formas','clientes','produtos','vendaProds'));
    }

    public function indexVendaProdutosDiaAnterior()
    {
        $data = date("Y-m-d");
        $diaAnterior = date('Y-m-d', strtotime($data. ' - 1 days'));
        $view = "inicial";
        $formas = DB::table('venda_produtos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $vendaProds = VendaProduto::whereBetween('created_at',["$diaAnterior"." 00:00", "$diaAnterior"." 23:59"])->orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_produtos',compact('view','formas','clientes','produtos','vendaProds'));
    }

    public function cadastrarVendaProduto(Request $request)
    {
        $vendaProd = new VendaProduto();
        if($request->input('valor')!=""){
            $vendaProd->valor = $request->input('valor');
        }
        if($request->input('desconto')!=""){
            $vendaProd->desconto = $request->input('desconto');
        }
        if($request->input('observacao')!=""){
            $vendaProd->observacao = $request->input('observacao');
        }
        if($request->input('formaPagamento')!=""){
            $vendaProd->forma_pagamento = $request->input('formaPagamento');
        }
        if($request->input('produto')!=""){
            $vendaProd->produto_id = $request->input('produto');
        }
        if($request->input('cliente')!=""){
            $vendaProd->cliente_id = $request->input('cliente');
        }
        $vendaProd->save();

        $prod = Produto::find($request->input('produto'));
        if(isset($prod)){
                $es = new EntradaSaida();
                $es->tipo = "saida";
                $es->produto_id = $request->input('produto');
                $es->quantidade = 1;
                $es->usuario = Auth::user()->name;
                $es->motivo = "Venda Produto";
                $es->save();
                $prod->estoque -= $request->input('qtd');
                $prod->save();
        }

        $lanc = new Lancamento();
        $lanc->tipo = "deposito";
        $lanc->valor = $request->input('valor') - $request->input('desconto');
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = "Venda Produto";
        $lanc->save();
        $saldo = Saldo::find(1);
        $saldo->saldo += $request->input('valor') - $request->input('desconto');
        $saldo->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Venda Produto";
        $hist->codigo = $vendaProd->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Venda de Produto Cadastrada com Sucesso!');
    }

    public function apagarVendaProduto($id)
    {
        $vendaProd = VendaProduto::find($id);

        $hist = new Historico();
        $hist->acao = "Excluiu";
        $hist->referencia = "Venda Produto";
        $hist->codigo = $vendaProd->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        $lanc = new Lancamento();
        $lanc->tipo = "retirada";
        $lanc->valor = $vendaProd->valor - $vendaProd->desconto;
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = "Cancelamento Venda Produto";
        $lanc->save();
        if(isset($vendaProd)){
            $saldo = Saldo::find(1);
            $saldo->saldo -= $vendaProd->valor - $vendaProd->desconto;
            $saldo->save();
            $vendaProd->delete();
        }

        return back()->with('mensagem', 'Venda de Produto Excluída com Sucesso!');
    }

    public function filtroVendaProduto(Request $request)
    {
        $produto = $request->input('produto');
        $cliente = $request->input('cliente');
        $formaPagamento = $request->input('formaPagamento');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($produto)){
            if(isset($cliente)){
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('produto_id',"$produto")->paginate(100);
                        }
                    }
                }
            }
        } else {
            if(isset($cliente)){
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('cliente_id',"$cliente")->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($formaPagamento)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::where('forma_pagamento','like',"%$formaPagamento%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::where('forma_pagamento','like',"%$formaPagamento%")->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            $vendaProds = VendaProduto::whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $vendaProds = VendaProduto::whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                        } else {
                            return redirect('/vendas/produtos');
                        }
                    }
                }
            }
        }
        $total_valor = $vendaProds->sum('valor');
        $total_desconto = $vendaProds->sum('desconto');
        $total_geral = $total_valor - $total_desconto;
        $view = "filtro";
        $formas = DB::table('venda_produtos')->select(DB::raw("forma_pagamento"))->groupBy('forma_pagamento')->get();
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        return view('vendas.venda_produtos',compact('view','formas','total_valor','total_desconto','total_geral','vendaProds','clientes','produtos'));
    }


    //LANÇAMENTOS   
    public function indexLancamentos()
    {
        $view = "inicial";
        $users = User::orderBy('name')->get();
        $lancs = Lancamento::orderBy('created_at', 'desc')->paginate(20);
        $saldos = Saldo::where('nome','principal')->get();
        return view('lancamentos.lancamentos',compact('view','users','lancs','saldos'));
    }

    public function depositoLancamento(Request $request)
    {
        $lanc = new Lancamento();
        $lanc->tipo = "deposito";
        $lanc->valor = $request->input('valor');
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = $request->input('motivo');
        $lanc->save();

        $saldo = Saldo::find(1);
        $saldo->saldo += $request->input('valor');
        $saldo->save();

        $hist = new Historico();
        $hist->acao = "Fez Depósito";
        $hist->referencia = "Lançamento";
        $hist->codigo = $lanc->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Depósito efetuado com Sucesso!');
    }

    public function retiradaLancamento(Request $request)
    {
        $lanc = new Lancamento();
        $lanc->tipo = "retirada";
        $lanc->valor = $request->input('valor');
        $lanc->usuario = Auth::user()->name;
        $lanc->motivo = $request->input('motivo');
        $lanc->save();

        $saldo = Saldo::find(1);
        $saldo->saldo -= $request->input('valor');
        $saldo->save();

        $hist = new Historico();
        $hist->acao = "Fez uma Retirada";
        $hist->referencia = "Lançamento";
        $hist->codigo = $lanc->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Retirada efetuada com Sucesso!');
    }

    public function filtroLancamento(Request $request)
    {
        $tipo = $request->input('tipo');
        if($request->input('user')!=""){
            $userId = $request->input('user');
            $user = User::find($userId);
            $usuario = $user->name;
        }
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($user)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('tipo','like',"%$tipo%")->paginate(100);
                    }
                }
            }
        } else {
            if(isset($user)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $lancs = Lancamento::where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::where('usuario','like',"$usuario")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $lancs = Lancamento::whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $lancs = Lancamento::whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $lancs = Lancamento::whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        return redirect('/lancamentos');
                    }
                }
            }
        }
        $view = "filtro";
        $users = User::orderBy('name')->get();
        $saldos = Saldo::where('nome','principal')->get();
        return view('lancamentos.lancamentos',compact('view','users','lancs','saldos'));
    }


    //DESPESAS  
    public function indexDespesas()
    {
        $valorTotal = 0;
        $view = "inicial";
        $despesas = Despesa::orderBy('vencimento')->paginate(20);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function indexDespesasDia()
    {
        $valorTotal = 0;
        $view = "filtro";
        $data = date("Y-m-d");
        $despesas = Despesa::where('vencimento',"$data")->orderBy('vencimento')->paginate(50);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function indexDespesasMes()
    {
        $valorTotal = 0;
        $view = "filtro";
        $mesAtual = date("m");
        $ultimoDiaMes = date("t", mktime(0,0,0,$mesAtual,'01',date("Y")));
        $despesas = Despesa::whereBetween('vencimento',[date("Y")."-"."$mesAtual"."-01", date("Y")."-"."$mesAtual"."-"."$ultimoDiaMes"])->paginate(50);
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    public function cadastrarDespesa(Request $request)
    {
        if($request->input('parcelado') == 1){
            for($i=1; $i<=$request->input('qtdParcelas'); $i++){
                $desp = new Despesa();
                $desp->descricao = $request->input('descricao')." - ".$i."/".$request->input('qtdParcelas');
                $dias = ($i * 30) - 30;
                $data = $request->input('vencimento');
                $desp->vencimento = date('Y-m-d', strtotime($data. ' + '.$dias.' days'));
                $desp->valorTotal = $request->input('valorTotal');
                $desp->formaPagamento = $request->input('formaPagamento');
                $desp->observacao = $request->input('observacao');
                $desp->parcelado = $request->input('parcelado');
                $desp->qtdParcelas = $request->input('qtdParcelas');
                $desp->valorParcela = $request->input('valorParcela');
                $desp->usuario = Auth::user()->name;
                $desp->save();

                $hist = new Historico();
                $hist->acao = "Cadastrou";
                $hist->referencia = "Despesa";
                $hist->codigo = $desp->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();
            }
        } else {
            $desp = new Despesa();
            $desp->descricao = $request->input('descricao');
            $desp->vencimento = $request->input('vencimento');
            $desp->valorTotal = $request->input('valorTotal');
            $desp->formaPagamento = $request->input('formaPagamento');
            $desp->observacao = $request->input('observacao');
            $desp->parcelado = $request->input('parcelado');
            $desp->usuario = Auth::user()->name;
            $desp->save();

            $hist = new Historico();
            $hist->acao = "Cadastrou";
            $hist->referencia = "Despesa";
            $hist->codigo = $desp->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }

        return back()->with('mensagem', 'Despesa Cadastrada com Sucesso!');
    }

    public function pagarDespesa(Request $request, $id)
    {
        $desp = Despesa::find($id);

        $hist = new Historico();
        $hist->acao = "Pagou";
        $hist->referencia = "Despesa";
        $hist->codigo = $desp->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($desp)){
            $desp->pago = 1;
            $desp->pagamento = $request->input('pagamento');
            $desp->save();
        }

        if($request->input('saldo')==1){
            $lanc = new Lancamento();
            $lanc->tipo = "retirada";
            $valor = 0;
            if($desp->parcelado==1){
                $valor = $desp->valorParcela;
            } else {
                $valor = $desp->valorTotal;
            }
            $lanc->valor = $valor;
            $lanc->usuario = Auth::user()->name;
            $lanc->motivo = "Pagamento de Despesa";
            $lanc->save();
            $saldo = Saldo::find(1);
            $saldo->saldo -= $valor;
            $saldo->save();
        } else {
            $hist = new Historico();
            $hist->acao = "Pagou sem usar o Saldo";
            $hist->referencia = "Despesa";
            $hist->codigo = $desp->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }

        return back()->with('mensagem', 'Despesa Paga com Sucesso!');
    }

    public function editarDespesa(Request $request, $id)
    {
        $desp = Despesa::find($id);

        $hist = new Historico();
        $hist->acao = "Alterou";
        $hist->referencia = "Despesa";
        $hist->codigo = $desp->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($desp)){
            $desp->descricao = $request->input('descricao');
            $desp->vencimento = $request->input('vencimento');
            $desp->formaPagamento = $request->input('formaPagamento');
            $desp->observacao = $request->input('observacao');
            $desp->save();
        }

        return back()->with('mensagem', 'Despesa Alterada com Sucesso!');
    }

    public function apagarDespesa($id)
    {
        $desp = Despesa::find($id);

        $hist = new Historico();
        $hist->acao = "Excluiu";
        $hist->referencia = "Despesa";
        $hist->codigo = $desp->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        if(isset($desp)){
            $desp->delete();
        }

        return back()->with('mensagem', 'Despesa Excluída com Sucesso!');
    }

    public function filtroDespesa(Request $request)
    {
        $codigo = $request->input('codigo');
        $descricao = $request->input('descricao');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($codigo)){
            if(isset($descricao)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->where('descricao','like',"%$descricao%")->orderBy('vencimento')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('id',"$codigo")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('id',"$codigo")->orderBy('vencimento')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($descricao)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::where('descricao','like',"%$descricao%")->orderBy('vencimento')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $despesas = Despesa::whereBetween('vencimento',["$dataInicio", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        $despesas = Despesa::whereBetween('vencimento',["$dataInicio", date("Y/m/d")])->orderBy('vencimento')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $despesas = Despesa::whereBetween('vencimento',["", "$dataFim"])->orderBy('vencimento')->paginate(100);
                    } else {
                        return redirect('/despesas/lancamentos');
                    }
                }
            }
        }
        $valorTotal = 0;
        foreach ($despesas as $desp) {
            if($desp->parcelado==1){
                $valorTotal += $desp->valorParcela;
            } else {
                $valorTotal += $desp->valorTotal;
            }
        }
        $view = "filtro";
        return view('despesas.lancamentos',compact('view','valorTotal','despesas'));
    }

    //AGENDAMENTOS
    public function indexAgendamentos()
    {
        $dataAtual = date("Y-m-d");
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $agends = Agendamento::whereBetween('data',["$dataAtual", "$dataSemana"])->orderBy('data')->get();
        return view('agendamentos.agendamentos',compact('dataAtual','pets','servs','agends'));
    }

    public function novoAgendamento($data, $hora)
    {
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        return view('agendamentos.novo_agendamento',compact('data','hora','pets','servs'));
    }

    public function cadastrarAgendamento(Request $request)
    {
        $agend = new Agendamento();
        if($request->input('data')!=""){
            $agend->data = $request->input('data');
        }
        if($request->input('hora')!=""){
            $agend->hora = $request->input('hora');
        }
        if($request->input('servico')!=""){
            $agend->servico_id = $request->input('servico');
        }
        if($request->input('valor')!=""){
            $agend->valor = $request->input('valor');
        }
        if($request->input('petCadastrado')!=""){
            $agend->pet_cadastrado = $request->input('petCadastrado');
        }
        if($request->input('pet')!=""){
            $agend->pet_id = $request->input('pet');
        }
        if($request->input('nomeCliente')!=""){
            $agend->nome_cliente = $request->input('nomeCliente');
        }
        if($request->input('nomePet')!=""){
            $agend->nome_pet = $request->input('nomePet');
        }
        if($request->input('telefone')!=""){
            $agend->telefone = $request->input('telefone');
        }
        if($request->input('petCadastrado')==1){
            if($request->input('buscar1')!=""){
                $agend->buscar = $request->input('buscar1');
            }
        } else {
            if($request->input('buscar0')!=""){
                $agend->buscar = $request->input('buscar0');
            }
        }
        if($request->input('cep')!=""){
            $agend->cep = $request->input('cep');
        }
        if($request->input('rua')!=""){
            $agend->rua = $request->input('rua');
        }
        if($request->input('numero')!=""){
            $agend->numero = $request->input('numero');
        }
        if($request->input('complemento')!=""){
            $agend->complemento = $request->input('complemento');
        }
        if($request->input('bairro')!=""){
            $agend->bairro = $request->input('bairro');
        }
        if($request->input('cidade')!=""){
            $agend->cidade = $request->input('cidade');
        }
        if($request->input('uf')!=""){
            $agend->uf = $request->input('uf');
        }
        $agend->status = "PENDENTE"; 
        $agend->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Agendamento";
        $hist->codigo = $agend->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return redirect("/agendamentos")->with('mensagem', 'Agendamento Cadastrado com Sucesso!');
    }

    public function atendidoAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "ATENDIDO";
            $agend->save();

            $hist = new Historico();
            $hist->acao = "Atendeu";
            $hist->referencia = "Agendamento";
            $hist->codigo = $agend->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }

        return back()->with('mensagem', 'Agendamento Atendido com Sucesso!');
    }

    public function cancelarAgendamento($id)
    {
        $agend = Agendamento::find($id);

        if(isset($agend)){
            $agend->status = "CANCELADO";
            $agend->save();

            $hist = new Historico();
            $hist->acao = "Cancelou";
            $hist->referencia = "Agendamento";
            $hist->codigo = $agend->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();
        }

        return back()->with('mensagem', 'Agendamento Cancelado com Sucesso!');
    }

    public function filtroAgendamento(Request $request)
    {
        $dataAtual = $request->input('data');
        $dataSemana = date('Y-m-d', strtotime($dataAtual. ' + 7 days'));
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $agends = Agendamento::whereBetween('data',["$dataAtual", "$dataSemana"])->orderBy('data')->get();
        return view('agendamentos.agendamentos',compact('dataAtual','pets','servs','agends'));
    }


    //USUÁRIOS
    public function indexUsuarios()
    {
        $view = "inicial";
        $users = User::orderBy('name')->paginate(10);
        return view('cadastros.usuarios', compact('view','users'));
    }

    public function cadastrarUsuario(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:8',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        if($request->file('foto')!=""){
        $path = $request->file('foto')->store('fotos_perfil','public');
            $user->foto = $path;
        }
        $user->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou";
        $hist->referencia = "Usuário";
        $hist->codigo = $user->id;
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back()->with('mensagem', 'Usuário Cadastrado com Sucesso!');
    }

    public function filtroUsuario(Request $request)
    {
        $nome = $request->input('nome');
        $turma = $request->input('turma');
        if(isset($nome)){
            if(isset($turma)){
                $users = User::where('ativo',true)->where('name','like',"%$nome%")->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                $users = User::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(50);
            }
        } else {
            if(isset($turma)){
                $users = User::where('ativo',true)->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                return redirect('/usuarios');
            }
        }
        $view = "filtro";
        return view('cadastros.usuarios', compact('view','users'));
    }

    public function editarUsuario(Request $request, $id)
    {
        $user = User::find($id);
        if(isset($user)){
            $user->name =$request->input('name');
            $user->email =$request->input('email');
            if($request->input('password')!=""){
            $user->password = Hash::make($request->input('password'));
            }
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($user->foto);
                $path = $request->file('foto')->store('fotos_perfil','public');
                $user->foto = $path;
            }
            $user->save();

            $hist = new Historico();
            $hist->acao = "Alterou";
            $hist->referencia = "Usuário";
            $hist->codigo = $user->id;
            $hist->usuario = Auth::user()->name;
            $hist->save();

            return back()->with('mensagem', 'Usuário Alterado com Sucesso!');
        }
        return back();
    }

    public function apagarUsuario($id)
    {
        $user = User::find($id);

        if(isset($user)){
            if($user->ativo==1){
                $user->ativo = false;
                $user->save();

                $hist = new Historico();
                $hist->acao = "Inativou";
                $hist->referencia = "Usuário";
                $hist->codigo = $user->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Usuário Inativado com Sucesso!');
            } else {
                $user->ativo = true;
                $user->save();

                $hist = new Historico();
                $hist->acao = "Ativou";
                $hist->referencia = "Usuário";
                $hist->codigo = $user->id;
                $hist->usuario = Auth::user()->name;
                $hist->save();

                return back()->with('mensagem', 'Usuário Ativado com Sucesso!');
            }
        }
        
        return back();
    }

}
