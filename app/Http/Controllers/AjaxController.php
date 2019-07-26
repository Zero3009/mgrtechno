<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;
use App\Proveedores;
use App\Stock;

use DB;
use Response;

class AjaxController extends Controller
{
	public function getProductos()
	{
			$ajax = Productos::select('prods.modelo as label','prods.id as value')
							->where('prods.estado','=',true)
							->get();
	    return Response::json($ajax);
	}
	public function getMarcas(Request $request)
	{
		$ajax = Productos::select('prods.marca as value')
						->where('prods.estado','=',true)
						->groupBy('prods.marca')
						->pluck('value');
		
		return Response::json($ajax);
	}
	public function getCodbarras(){
		$ajax = Productos::select('prods.codbarras as text','prods.id as value', 'prods.modelo')
							->where('prods.estado','=', true)
							->get();

		return Response::json($ajax);
	}
	public function getProveedores(){
		$ajax = Proveedores::select('provs.nombre as text','provs.id as value')
								->where('provs.estado','=', true)
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
	public function getTiposProds()
	{
		$ajax = Productos::select('prods.tipo as value')
			->groupBy('prods.tipo')
			->pluck('value');

		return Response::json($ajax);
	}
	public function checkCodbarras(Request $request)
	{
		$ajax = Productos::select('prods.codbarras as check')
				->where('prods.codbarras','=',$request->codbarras)
				->get();
		return Response::json($ajax);
	}
}
