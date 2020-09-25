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
    Route::get('/', 'UserController@indexEstoque');
    Route::get('/filtro', 'UserController@filtroEstoque');
    Route::post('/entrada/{id}', 'UserController@entradaEstoque');
    Route::post('/saida/{id}', 'UserController@saidaEstoque');
});

Route::group(['prefix' => 'clientes'], function() {
    Route::get('/', 'UserController@indexClientes');
    Route::post('/', 'UserController@cadastrarCliente');
    Route::post('/editar/{id}', 'UserController@editarCliente');
    Route::get('/filtro', 'UserController@filtroCliente');
});

Route::group(['prefix' => 'servicos'], function() {
    Route::get('/', 'UserController@indexServicos');
    Route::post('/', 'UserController@cadastrarServico');
    Route::post('/editar/{id}', 'UserController@editarServico');
    Route::get('/apagar/{id}', 'UserController@apagarServico');
});