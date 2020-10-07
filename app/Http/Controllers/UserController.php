<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ClienteTelefone;
use App\Models\EntradaSaida;
use App\Models\Historico;
use App\Models\Lancamento;
use App\Models\Marca;
use App\Models\Pet;
use App\Models\Produto;
use App\Models\Raca;
use App\Models\Saldo;
use App\Models\Servico;
use App\Models\Telefone;
use App\Models\TipoAnimal;
use App\Models\User;
use App\Models\VendaProduto;
use App\Models\VendaServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


    //HISTÓRICOS
    public function historicos(){
        $view = "inicial";
        $users = User::orderBy('name')->get();
        $hists = Historico::orderBy('created_at', 'desc')->paginate(20);
        return view('historico.historico',compact('view','users','hists'));
    }

    public function filtroHistoricos(Request $request)
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
                        $hists = Historico::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $hists = Historico::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")." 23:59"])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $hists = Historico::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $hists = Historico::where('tipo','like',"%$tipo%")->where('usuario','like',"$usuario")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $hists = Historico::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $hists = Historico::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio"." 00:00", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $hists = Historico::where('tipo','like',"%$tipo%")->whereBetween('created_at',["", "$dataFim"." 23:59"])->paginate(100);
                    } else {
                        $hists = Historico::where('tipo','like',"%$tipo%")->paginate(100);
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
                        return redirect('/Historicos');
                    }
                }
            }
        }
        $view = "filtro";
        $users = User::orderBy('name')->get();
        return view('historico.historico',compact('view','users','hists'));
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
        $hist->acao = "Cadastrou Nova Categoria";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function editarCategoria(Request $request, $id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->nome = $request->input('nomeCategoria');
            $cat->ativo = $request->input('ativo');
            $cat->save();
        }
        $hist = new Historico();
        $hist->acao = "Alterou uma Categoria";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function apagarCategoria($id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->ativo = false;
            $cat->save();
        }
        $hist = new Historico();
        $hist->acao = "Inativou uma Categoria";
        $hist->usuario = Auth::user()->name;
        $hist->save();
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
        $hist->acao = "Cadastrou Novo Tipo de Animal";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function editarTipoAnimal(Request $request, $id)
    {
        $tipo = TipoAnimal::find($id);
        if(isset($tipo)){
            $tipo->nome = $request->input('nome');
            $tipo->ativo = $request->input('ativo');
            $tipo->save();
        }
        $hist = new Historico();
        $hist->acao = "Alterou um Tipo de Animal";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function apagarTipoAnimal($id)
    {
        $tipo = TipoAnimal::find($id);
        if(isset($tipo)){
            $tipo->ativo = false;
            $tipo->save();
        }
        $hist = new Historico();
        $hist->acao = "Inativou um Tipo de Animal";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
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
        $hist->acao = "Cadastrou Nova Marca";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function editarMarca(Request $request, $id)
    {
        $marca = Marca::find($id);
        if(isset($marca)){
            $marca->nome = $request->input('nome');
            $marca->ativo = $request->input('ativo');
            $marca->save();
        }
        $hist = new Historico();
        $hist->acao = "Alterou uma Marca";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function apagarMarca($id)
    {
        $marca = Marca::find($id);
        if(isset($marca)){
            $marca->ativo = false;
            $marca->save();
        }
        $hist = new Historico();
        $hist->acao = "Inativou uma Marca";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }


    //PRODUTO
    public function indexProdutos()
    {
        $view = "inicial";
        $prods = Produto::paginate(20);
        $tipos = TipoAnimal::where('ativo',true)->orderBy('nome')->get();
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
        $hist->acao = "Cadastrou Novo Produto";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function editarProduto(Request $request, $id)
    {
        $prod = Produto::find($id);
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
        $hist = new Historico();
        $hist->acao = "Alterou um Produto";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function apagarProduto($id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            Storage::disk('public')->delete($prod->foto);
            $prod->ativo = false;
            $prod->save();
        }
        $hist = new Historico();
        $hist->acao = "Inativou um Produto";
        $hist->usuario = Auth::user()->name;
        $hist->save();
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
                            return redirect('/produtos');
                        }
                    }
                }
            }
        }
        
        $view = "filtro";
        $tipos = TipoAnimal::where('ativo',true)->orderBy('nome')->get();
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
        $hist = new Historico();
        $hist->acao = "Fez Entrada no Estoque";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function saidaEstoque(Request $request, $id)
    {
        $prod = Produto::find($id);
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
        $hist = new Historico();
        $hist->acao = "Fez Saída no Estoque";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
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
        $hist->acao = "Cadastrou Novo Cliente";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
    }

    public function editarCliente(Request $request, $id)
    {
        $aluno = Aluno::find($id);
        if(isset($aluno)){
            $aluno->name =$request->input('name');
            $aluno->email =$request->input('email');
            if($request->input('password')!=""){
            $aluno->password = Hash::make($request->input('password'));
            }
            $aluno->turma_id = $request->input('turma');
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($aluno->foto);
                $path = $request->file('foto')->store('fotos_perfil','public');
                $aluno->foto = $path;
            }
            $aluno->save();
        }
        $hist = new Historico();
        $hist->acao = "Alterou um Cliente";
        $hist->usuario = Auth::user()->name;
        $hist->save();
        return back();
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

        $hist = new Historico();
        $hist->acao = "Cadastrou um Telefone de Cliente";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function apagarTelefone($id)
    {
        $telefone = Telefone::find($id);
        if(isset($telefone)){
            $telefone->ativo = false;
            $telefone->save();
        }
        $hist = new Historico();
        $hist->acao = "Inativou um Telefone de Cliente";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
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
        $hist->acao = "Cadastrou Novo Serviço";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function editarServico(Request $request, $id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            $serv->nome = $request->input('nome');
            $serv->preco = $request->input('preco');
            $serv->save();
        }

        $hist = new Historico();
        $hist->acao = "Alterou um Serviço";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function apagarServico($id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            $serv->ativo = false;
            $serv->save();
        }

        $hist = new Historico();
        $hist->acao = "Inativou um Serviço";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
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
        $hist->acao = "Cadastrou Nova Raça";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function editarRaca(Request $request, $id)
    {
        $raca = Raca::find($id);
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

        $hist = new Historico();
        $hist->acao = "Alterou uma Raça";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }


    //PET
    public function indexPets()
    {
        $view = "inicial";
        $pets = Pet::paginate(20);
        $racas = Raca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('cadastros.pets',compact('view','pets','racas','clientes'));
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
        if($request->input('ativo')!=""){
            $pet->ativo = $request->input('ativo');
        }
        if($request->input('cliente')!=""){
            $pet->cliente_id = $request->input('cliente');
        }
        $pet->save();

        $hist = new Historico();
        $hist->acao = "Cadastrou Novo Pet";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function editarPet(Request $request, $id)
    {
        $pet = Pet::find($id);
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
            if($request->input('ativo')!=""){
                $pet->ativo = $request->input('ativo');
            }
            if($request->input('cliente')!=""){
                $pet->cliente_id = $request->input('cliente');
            }
            $pet->save();
        }

        $hist = new Historico();
        $hist->acao = "Alterou um Pet";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function apagarPet($id)
    {
        $pet = Pet::find($id);
        if(isset($pet)){
            Storage::disk('public')->delete($pet->foto);
            $pet->ativo = false;
            $pet->save();
        }

        $hist = new Historico();
        $hist->acao = "Inativou um Pet";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
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
        $racas = Raca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('cadastros.pets',compact('view','pets','racas','clientes'));
    }


    //VENDA DE SERVIÇO
    public function indexVendaServicos()
    {
        $view = "inicial";
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        $vendaServs = VendaServico::orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_servicos',compact('view','pets','servs','vendaServs'));
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
        $hist->acao = "Cadastrou Nova Venda de Serviço";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function apagarVendaServico($id)
    {
        $vendaServ = VendaServico::find($id);
        if(isset($vendaServ)){
            $saldo = Saldo::find(1);
            $saldo->saldo -= $vendaServ->valor - $vendaServ->desconto;
            $saldo->save();
            $vendaServ->delete();
        }

        $hist = new Historico();
        $hist->acao = "Apagou uma Venda de Serviço";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function filtroVendaServico(Request $request)
    {
        $servico = $request->input('servico');
        $pet = $request->input('pet');
        $formaPagamento = $request->input('formaPagamento');
        if(isset($formaPagamento)){
            if(isset($servico)){
                if(isset($pet)){
                    $vendaServs = VendaServico::where('nome','like',"%$formaPagamento%")->where('servico_id',"$servico")->where('pet_id',"$pet")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaServs = VendaServico::where('nome','like',"%$formaPagamento%")->where('servico_id',"$servico")->orderBy('created_at', 'desc')->paginate(50); 
                }
            } else {
                if(isset($pet)){
                    $vendaServs = VendaServico::where('nome','like',"%$formaPagamento%")->where('pet_id',"$pet")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaServs = VendaServico::where('nome','like',"%$formaPagamento%")->orderBy('created_at', 'desc')->paginate(50);
                }
            }
        } else {
            if(isset($servico)){
                if(isset($pet)){
                    $vendaServs = VendaServico::where('servico_id',"$servico")->where('pet_id',"$pet")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaServs = VendaServico::where('servico_id',"$servico")->orderBy('created_at', 'desc')->paginate(50); 
                }
            } else {
                if(isset($pet)){
                    $vendaServs = VendaServico::where('pet_id',"$pet")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    return redirect('/vendas/servicos');
                }
            }
        }
        $view = "filtro";
        $pets = Pet::orderBy('nome')->get();
        $servs = Servico::orderBy('nome')->get();
        return view('vendas.venda_servicos',compact('view','vendaServs','pets','servs'));
    }

    
    //VENDA DE PRODUTO
    public function indexVendaProdutos()
    {
        $view = "inicial";
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $vendaProds = VendaProduto::orderBy('created_at', 'desc')->paginate(20);
        return view('vendas.venda_produtos',compact('view','clientes','produtos','vendaProds'));
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
        $hist->acao = "Cadastrou Nova Venda de Produto";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function apagarVendaProduto($id)
    {
        $vendaProd = VendaProduto::find($id);
        if(isset($vendaProd)){
            $saldo = Saldo::find(1);
            $saldo->saldo -= $vendaProd->valor - $vendaProd->desconto;
            $saldo->save();
            $vendaProd->delete();
        }

        $hist = new Historico();
        $hist->acao = "Apagou uma Venda de Produto";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
    }

    public function filtroVendaProduto(Request $request)
    {
        $produto = $request->input('produto');
        $cliente = $request->input('cliente');
        $formaPagamento = $request->input('formaPagamento');
        if(isset($formaPagamento)){
            if(isset($produto)){
                if(isset($cliente)){
                    $vendaProds = VendaProduto::where('nome','like',"%$formaPagamento%")->where('produto_id',"$produto")->where('cliente_id',"$cliente")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaProds = VendaProduto::where('nome','like',"%$formaPagamento%")->where('produto_id',"$produto")->orderBy('created_at', 'desc')->paginate(50); 
                }
            } else {
                if(isset($cliente)){
                    $vendaProds = VendaProduto::where('nome','like',"%$formaPagamento%")->where('cliente_id',"$cliente")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaProds = VendaProduto::where('nome','like',"%$formaPagamento%")->orderBy('created_at', 'desc')->paginate(50);
                }
            }
        } else {
            if(isset($produto)){
                if(isset($cliente)){
                    $vendaProds = VendaProduto::where('produto_id',"$produto")->where('cliente_id',"$cliente")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    $vendaProds = VendaProduto::where('produto_id',"$produto")->orderBy('created_at', 'desc')->paginate(50); 
                }
            } else {
                if(isset($cliente)){
                    $vendaProds = VendaProduto::where('cliente_id',"$cliente")->orderBy('created_at', 'desc')->paginate(50);
                } else {
                    return redirect('/vendas/produtos');
                }
            }
        }
        $view = "filtro";
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        return view('vendas.venda_produtos',compact('view','vendaProds','clientes','produtos'));
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
        $hist->acao = "Fez um Depósito";
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
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
        $hist->usuario = Auth::user()->name;
        $hist->save();

        return back();
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

}
