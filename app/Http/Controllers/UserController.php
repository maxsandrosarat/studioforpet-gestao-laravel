<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ClienteTelefone;
use App\Models\Marca;
use App\Models\Pet;
use App\Models\Produto;
use App\Models\Raca;
use App\Models\Servico;
use App\Models\Telefone;
use App\Models\TipoAnimal;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cadastros(){
        return view('cadastros.home_cadastros');
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
        return back();
    }

    public function apagarCategoria($id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->ativo = false;
            $cat->save();
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
        return back();
    }

    public function apagarTipoAnimal($id)
    {
        $tipo = TipoAnimal::find($id);
        if(isset($tipo)){
            $tipo->ativo = false;
            $tipo->save();
        }
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
        return back();
    }

    public function apagarMarca($id)
    {
        $marca = Marca::find($id);
        if(isset($marca)){
            $marca->ativo = false;
            $marca->save();
        }
        return back();
    }


    //PRODUTO
    public function indexProdutos()
    {
        $prods = Produto::paginate(20);
        $tipos = TipoAnimal::where('ativo',true)->orderBy('nome')->get();
        $marcas = Marca::where('ativo',true)->orderBy('nome')->get();
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('prods','tipos','marcas','cats'));
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
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            return redirect('/admin/produtos');
                        }
                    }
                }
            }
        }
        
        $tipos = TipoAnimal::where('ativo',true)->orderBy('nome')->get();
        $marcas = Marca::where('ativo',true)->orderBy('nome')->get();
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('prods','tipos','marcas','cats'));
    }


    //ESTOQUE
    public function indexEstoque()
    {
        $prods = Produto::paginate(20);
        $tipos = TipoAnimal::all();
        $marcas = Marca::all();
        $cats = Categoria::all();
        return view('estoque.estoque_produtos',compact('prods','tipos','marcas','cats'));
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
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            return redirect('/admin/estoque');
                        }
                    }
                }
            }
        }
        
        $tipos = TipoAnimal::all();
        $marcas = Marca::all();
        $cats = Categoria::all();
        return view('estoque.estoque_produtos',compact('prods','tipos','marcas','cats'));
    }


    //CLIENTE
    public function indexClientes()
    {
        $clientes = Cliente::paginate(20);
        return view('cadastros.clientes',compact('clientes'));
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
        return back();
    }

    public function filtroCliente(Request $request)
    {
        $nome = $request->input('nome');
        $email = $request->input('email');
        if(isset($nome)){
            if(isset($email)){
                $clientes = Cliente::where('name','like',"%$nome%")->where('email','like',"%$email%")->paginate(100);
            } else {
                $clientes = Cliente::where('name','like',"%$nome%")->paginate(100);
            }
        } else {
            if(isset($email)){
                $clientes = Cliente::where('email','like',"%$email%")->paginate(100);
            } else {
                return redirect('/clientes');
            }
        }
        
        return view('cadastros.clientes',compact('clientes'));
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

        return back();
    }

    public function apagarTelefone($id)
    {
        $telefone = Telefone::find($id);
        if(isset($telefone)){
            $telefone->ativo = false;
            $telefone->save();
        }
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
        return back();
    }

    public function apagarServico($id)
    {
        $serv = Servico::find($id);
        if(isset($serv)){
            $serv->ativo = false;
            $serv->save();
        }
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
        return back();
    }


    //PET
    public function indexPets()
    {
        $pets = Pet::paginate(20);
        $racas = Raca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('cadastros.pets',compact('pets','racas','clientes'));
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
        return back();
    }

    public function apagarPet($id)
    {
        $pet = Pet::find($id);
        if(isset($prod)){
            Storage::disk('public')->delete($pet->foto);
            $pet->ativo = false;
            $pet->save();
        }
        return back();
    }

    public function filtroPet(Request $request)
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
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            return redirect('/admin/produtos');
                        }
                    }
                }
            }
        }
        
        $tipos = TipoAnimal::where('ativo',true)->orderBy('nome')->get();
        $marcas = Marca::where('ativo',true)->orderBy('nome')->get();
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('prods','tipos','marcas','cats'));
    }



}
