<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;
use App\Proveedores;
use App\Stock;
use App\Clientes;
use App\TiposDeProductos;
use App\Marcas;
use DB;
use Response;

class AjaxController extends Controller
{
	public function getProductos()
	{
			$ajax = Productos::select('productos.modelo as label','productos.id as value')
							->where('productos.estado','=',true)
							->get();
	    return Response::json($ajax);
	}
	public function getMarcas(Request $request)
	{
		$filtro = $request->search;
		$ajax = Marcas::select('marcas.nombre as text','marcas.id')
						->where('marcas.estado','=',true)
						->where('marcas.nombre','ilike',"%$filtro%")
						->get();
		
		return Response::json($ajax);
	}
	public function getCodbarras(Request $request){
		/*$ajax = Productos::select('productos.codbarras as text','productos.id as value', 'productos.modelo','productos.serializado')
							->where('productos.estado','=', true)
							->get();*/
		$parameters = $request->all();
		$ajax = Productos::select('productos.modelo','productos.ean as ean','productos.upc as upc','productos.id','productos.serializado');
				if($parameters['search'])
				{
		
					$filtro = $parameters['search'];
					$ajax = $ajax->where(function ($ajax) use ($filtro) {
										$ajax->orWhere('productos.modelo','ilike',"%$filtro%");
										//$retornar->orWhere('proveedores.nombre','ilike',"%$filtro%");
										if(is_numeric($filtro))
										{
											$ajax->orWhere('productos.ean','ilike',"%$filtro%");
											$ajax->orWhere('productos.upc','ilike',"%$filtro%");
											//$retornar->orWhere('productos.codbarras','ilike',"%$filtro%");
										}
					});
		
				}
		return Response::json($ajax->get());
	}
	public function getProveedores(Request $request){
		$filtro = $request->search;
		$ajax = Proveedores::select('proveedores.nombre as text','proveedores.id as value')
								->where('proveedores.estado','=', true)
								->where('proveedores.nombre','ilike', "%$filtro%")
								->get();
		return Response::json($ajax);	
	}
	public function getSeriales(Request $request)
	{
		$ajax = Stock::select('stock.serial as text','stock.serial as value')
						->where('stock.estado','=',true)
						->get();
			

		return Response::json($ajax);
	}
	public function LineEntrada()
	{

		$ajax = //\DB::table('stock')
				Stock::groupBy('fechaEntrada')
					->selectRaw('sum("stock"."precioEntrada") AS y, "stock"."fechaEntrada" as t')
					->where('estado','=',true)
					->orderBy('fechaEntrada')
					->get();
		return Response::json($ajax);
	}
	public function LineSalida()
	{

		$ajax = //\DB::table('stock')
				Stock::groupBy('fechaSalida')
					->selectRaw('sum("stock"."precioSalida") AS y, "stock"."fechaSalida" as t')
					->where('estado','=',true)
					//->whereNotNull('precioSalida')
					->orderBy('fechaSalida')
					->get();
		return Response::json($ajax);
	}
	public function getTiposProductos(Request $request)
	{

		$filtro = $request->search;
		$ajax = TiposDeProductos::select('tipos_de_productos.nombre as text','tipos_de_productos.id')
								->where('tipos_de_productos.nombre','ilike',"%$filtro%")
								->get();
			//>pluck('value');

		return Response::json($ajax);
	}
	public function checkCodbarras(Request $request)
	{
		$ajax = Productos::select('productos.codbarras as check')
				->where('productos.codbarras','=',$request->codbarras)
				->get();
		return Response::json($ajax);
	}
	public function getClientes(Request $request)
	{
		$ajax = Clientes::select('clientes.id','clientes.nombre', 'clientes.apellido', 'clientes.documento','clientes.email')
				->where('clientes.estado','=',true);
				if($request->search)
				{
		
					$filtro = $request->search;
					$ajax = $ajax->where(function ($ajax) use ($filtro) {
										$ajax->orWhere('clientes.nombre','ilike',"%$filtro%");
										$ajax->orWhere('clientes.email','ilike',"%$filtro%");
										//$retornar->orWhere('proveedores.nombre','ilike',"%$filtro%");
										$ajax->orWhere('clientes.apellido','ilike',"%$filtro%");
										if(is_numeric($filtro))
										{
											$ajax->orWhere('clientes.documento','ilike',"%$filtro%");
											//$retornar->orWhere('productos.codbarras','ilike',"%$filtro%");
										}
					});
		
				}
		return Response::json($ajax->get());
	}
}
