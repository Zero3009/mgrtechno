<?php

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
    return view('auth.login');
});
Route::middleware(['auth'])->group(function()
{
	Route::get('/admin/inicio', ['uses' => 'InicioController@Index']);
	Route::get('/ajax/productos', ['uses' => 'AjaxController@getProductos']);
	Route::post('/ajax/marcas', ['uses' => 'AjaxController@getMarcas']);
	Route::post('/ajax/codbarras', ['uses' => 'AjaxController@getCodbarras']);
	Route::post('/ajax/proveedores', ['uses' => 'AjaxController@getProveedores']);
	Route::post('/ajax/seriales', ['uses' => 'AjaxController@getSeriales']);
	Route::get('/ajax/chart', ['uses' => 'AjaxController@LineEntrada']);
	Route::get('/ajax/linesalida',['uses' => 'AjaxController@LineSalida']);
	Route::post('/ajax/tiposprods',['uses' => 'AjaxController@getTiposProductos']);
	Route::post('/ajax/check/codbarras',['uses' => 'AjaxController@checkCodbarras']);
	Route::post('/ajax/clientes',['uses' => 'AjaxController@getClientes']);
//FIN AJAX


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/stock', 'StockController@IndexStock');
//DATATABLES
Route::post('/datatables/getproveedores', ['uses' => 'DatatablesController@GetProveedores']);
Route::post('/datatables/getproductos', ['uses' => 'DatatablesController@GetProductos']);
Route::post('/datatables/getstock', ['uses' => 'DatatablesController@GetStock']);
Route::post('/datatables/getclientes', ['uses' => 'DatatablesController@GetClientes']);
Route::get('/datatables/getproveedoreseliminados', ['uses' => 'DatatablesController@GetProveedoresEliminados']);
Route::get('/datatables/getproductoseliminados',['uses' => 'DatatablesController@GetProductosEliminados']);
Route::post('/datatables/getcompras', ['uses' => 'DatatablesController@GetCompras']);
//PROVEEDORES
Route::get('/admin/proveedores', 'ProveedoresController@Index');
Route::get('/admin/clientes', 'ClientesController@Index');
Route::post('/admin/clientes/nuevo', 'ClientesController@NuevoCliente');
Route::post('/admin/clientes/editar', 'ClientesController@EditarCliente');
Route::post('/admin/proveedores/nuevo', ['uses' => 'ProveedoresController@NuevoProveedor']);
Route::post('/admin/proveedores/editar', ['uses' => 'ProveedoresController@EditarProveedor']);
Route::post('/admin/proveedores/eliminar', ['uses' => 'ProveedoresController@EliminarProveedor']);
//FIN PROVEEDORES

//PRODUCTOS
Route::get('/admin/productos', ['uses' => 'ProductosController@Index']);
Route::post('/admin/productos/nuevo', ['uses' => 'ProductosController@NuevoProducto']);
Route::post('/admin/productos/nuevo/post', ['uses' => 'ProductosController@NuevoProducto']);
Route::post('/admin/productos/editar', ['uses' => 'ProductosController@EditarProducto']);
Route::post('/admin/productos/eliminar', ['uses' => 'ProductosController@EliminarProducto']);
//FIN PRODUCTOS

//STOCK
Route::get('/admin/stock', ['uses' => 'StockController@Index'])->name('stock');
Route::post('/admin/stock/nuevo', ['uses' => 'StockController@NewStock']);
//Route::get('/admin/stock/editar/{id}', ['uses' => 'StockController@EditStockView']);
Route::post('/admin/stock/editar', ['uses' => 'StockController@EditStock']);
Route::post('/admin/stock/eliminar', ['uses' => 'StockController@EliminarStock']);
Route::post('/admin/stock/salida',['uses' => 'StockController@SalidaStock']);
//FIN STOCK

//RECUPERACION
Route::get('/admin/recuperacion/proveedores', ['uses' => 'RecuperacionController@IndexProveedores']);
Route::post('/admin/recuperacion/proveedores/post', ['uses' => 'RecuperacionController@ProveedoresRecuperacion']);
Route::get('/admin/recuperacion/productos', ['uses' => 'RecuperacionController@IndexProductos']);
Route::post('/admin/recuperacion/productos/post', ['uses' => 'RecuperacionController@ProductosRecuperacion']);
});
//->middleware('auth');
//FIN INICIO

//AJAX

//FIN RECUPERACION
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');