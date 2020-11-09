<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/cadastros', 'UserController@cadastros');
Route::post('/telefones/{id}', 'UserController@cadastrarTelefone');

Route::group(['prefix' => 'categorias'], function() {
    Route::get('/', 'UserController@indexCategorias');
    Route::post('/', 'UserController@cadastrarCategoria');
    Route::post('/editar/{id}', 'UserController@editarCategoria');
    Route::get('/apagar/{id}', 'UserController@apagarCategoria');
});

Route::group(['prefix' => 'tiposAnimais'], function() {
    Route::get('/', 'UserController@indexTiposAnimal');
    Route::post('/', 'UserController@cadastrarTipoAnimal');
    Route::post('/editar/{id}', 'UserController@editarTipoAnimal');
    Route::get('/apagar/{id}', 'UserController@apagarTipoAnimal');
});

Route::group(['prefix' => 'marcas'], function() {
    Route::get('/', 'UserController@indexMarcas');
    Route::post('/', 'UserController@cadastrarMarca');
    Route::post('/editar/{id}', 'UserController@editarMarca');
    Route::get('/apagar/{id}', 'UserController@apagarMarca');
});

Route::group(['prefix' => 'produtos'], function() {
    Route::get('/', 'UserController@indexProdutos');
    Route::post('/', 'UserController@cadastrarProduto');
    Route::post('/editar/{id}', 'UserController@editarProduto');
    Route::get('/apagar/{id}', 'UserController@apagarProduto');
    Route::get('/filtro', 'UserController@filtroProduto');
});

Route::group(['prefix' => 'estoque'], function() {
    Route::get('/', 'UserController@estoque');

    Route::group(['prefix' => 'lancamentos'], function() {
        Route::get('/', 'UserController@indexEstoque');
        Route::get('/filtro', 'UserController@filtroEstoque');
        Route::post('/entrada/{id}', 'UserController@entradaEstoque');
        Route::post('/saida/{id}', 'UserController@saidaEstoque');
    });

    Route::group(['prefix' => 'historicos'], function() {
        Route::get('/', 'UserController@indexEntradaSaidas');
        Route::get('/filtro', 'UserController@filtroEntradaSaidas');
    });
});

Route::group(['prefix' => 'clientes'], function() {
    Route::get('/', 'UserController@indexClientes');
    Route::post('/', 'UserController@cadastrarCliente');
    Route::post('/editar/{id}', 'UserController@editarCliente');
    Route::get('/filtro', 'UserController@filtroCliente');
    Route::get('/telefones/apagar/{c}/{t}', 'UserController@apagarTelefone');
});

Route::group(['prefix' => 'servicos'], function() {
    Route::get('/', 'UserController@indexServicos');
    Route::post('/', 'UserController@cadastrarServico');
    Route::post('/editar/{id}', 'UserController@editarServico');
    Route::get('/apagar/{id}', 'UserController@apagarServico');
});

Route::group(['prefix' => 'racas'], function() {
    Route::get('/', 'UserController@indexRacas');
    Route::post('/', 'UserController@cadastrarRaca');
    Route::post('/editar/{id}', 'UserController@editarRaca');
});

Route::group(['prefix' => 'planos'], function() {
    Route::get('/', 'UserController@indexPlanos');
    Route::post('/', 'UserController@cadastrarPlano');
    Route::post('/editar/{id}', 'UserController@editarPlano');
    Route::get('/apagar/{id}', 'UserController@apagarPlano');
});

Route::group(['prefix' => 'pets'], function() {
    Route::get('/', 'UserController@indexPets');
    Route::post('/', 'UserController@cadastrarPet');
    Route::post('/editar/{id}', 'UserController@editarPet');
    Route::get('/filtro', 'UserController@filtroPet');
    Route::get('/pagamentos/{id}', 'UserController@pagamentosPlano');
    Route::post('/pagar/{id}', 'UserController@pagarPlano');
    Route::post('/trocar/{id}', 'UserController@trocarPlano');
    Route::post('/reativar/{id}', 'UserController@reativarPlano');
    Route::get('/cancelar/{id}', 'UserController@cancelarPlano');
});

Route::group(['prefix' => 'vendas'], function() {
    Route::get('/', 'UserController@vendas');

    Route::group(['prefix' => 'servicos'], function() {
        Route::get('/', 'UserController@indexVendaServicos');
        Route::get('/dia', 'UserController@indexVendaServicosDia');
        Route::get('/diaAnterior', 'UserController@indexVendaServicosDiaAnterior');
        Route::post('/', 'UserController@cadastrarVendaServico');
        Route::get('/apagar/{id}', 'UserController@apagarVendaServico');
        Route::get('/filtro', 'UserController@filtroVendaServico');
    });
    
    Route::group(['prefix' => 'produtos'], function() {
        Route::get('/', 'UserController@indexVendaProdutos');
        Route::get('/dia', 'UserController@indexVendaProdutosDia');
        Route::get('/diaAnterior', 'UserController@indexVendaProdutosDiaAnterior');
        Route::post('/', 'UserController@cadastrarVendaProduto');
        Route::get('/apagar/{id}', 'UserController@apagarVendaProduto');
        Route::get('/filtro', 'UserController@filtroVendaProduto');
    });
});

Route::group(['prefix' => 'lancamentos'], function() {
    Route::get('/', 'UserController@indexLancamentos');
    Route::post('/deposito', 'UserController@depositoLancamento');
    Route::post('/retirada', 'UserController@retiradaLancamento');
    Route::get('/filtro', 'UserController@filtroLancamento');
});

Route::group(['prefix' => 'agendamentos'], function() {
    Route::get('/', 'UserController@indexAgendamentos');
    Route::get('/novo/{d}/{ho}', 'UserController@novoAgendamento');
    Route::post('/', 'UserController@cadastrarAgendamento');
    Route::get('/atendido/{id}', 'UserController@atendidoAgendamento');
    Route::get('/cancelar/{id}', 'UserController@cancelarAgendamento');
    Route::get('/filtro', 'UserController@filtroAgendamento');
});

Route::group(['prefix' => 'historicos'], function() {
    Route::get('/', 'UserController@historicos');
    Route::get('/filtro', 'UserController@filtroHistoricos');
});

Route::group(['prefix' => 'despesas'], function() {
    Route::get('/', 'UserController@despesas');

    Route::group(['prefix' => 'lancamentos'], function() {
        Route::get('/dia', 'UserController@indexDespesasDia');
        Route::get('/mes', 'UserController@indexDespesasMes');
        Route::get('/', 'UserController@indexDespesas');
        Route::post('/', 'UserController@cadastrarDespesa');
        Route::post('/pagar/{id}', 'UserController@pagarDespesa');
        Route::post('/editar/{id}', 'UserController@editarDespesa');
        Route::get('/apagar/{id}', 'UserController@apagarDespesa');
        Route::get('/filtro', 'UserController@filtroDespesa');
    });
});

Route::group(['prefix' => 'usuarios'], function() {
    Route::get('/', 'UserController@indexUsuarios');
    Route::post('/', 'UserController@cadastrarUsuario');
    Route::get('/filtro', 'UserController@filtroUsuario');
    Route::post('/editar/{id}', 'UserController@editarUsuario');
    Route::get('/apagar/{id}', 'UserController@apagarUsuario');
});


