<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Proveedores;
use App\Productos;
use App\Stock;
use Response;
use DB;
use Vuetable;

class DatatablesController extends Controller
{
	public function GetProveedores(Request $request)
	{
		$parameters = $request->all();
		$retornar = Proveedores::select(['provs.id', 'provs.nombre', 'provs.tel'])
						->where('estado','=', true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('provs.tel','ilike',"%$filtro%");
								$retornar->orWhere('provs.nombre','ilike',"%$filtro%");
								/*if(is_numeric($filtro))
								{
									$retornar->orWhere('prods.codbarras','ilike',"%$filtro%");
								}*/
						});
		}
		return Response::json($retornar->paginate($parameters['itemsPerPage']));
	}
	public function GetProductos(Request $request)
	{
		$parameters = $request->all();
		//return $parameters['search'];
		$retornar = Productos::select(['prods.id', 'prods.tipo','prods.marca','prods.modelo','prods.codbarras','prods.serializado'])
						->where('estado','=', true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('prods.tipo','ilike',"%$filtro%");
								$retornar->orWhere('prods.marca','ilike',"%$filtro%");
								$retornar->orWhere('prods.modelo','ilike',"%$filtro%");
								if(is_numeric($filtro))
								{
									$retornar->orWhere('prods.codbarras','ilike',"%$filtro%");
								}
						});
		}
		if(sizeof($parameters['sortDesc'])> 0 && sizeof($parameters['sortBy'])> 0)
		{
			if($parameters['sortDesc'][0] == true)
			{
				$retornar->orderBy($parameters['sortBy'][0], 'desc');	
			}
			else
			{
				$retornar->orderBy($parameters['sortBy'][0], 'asc');
			}
			
		}

		return Response::json($retornar->paginate($parameters['itemsPerPage']));
	}
	public function getStock(Request $request)
	{
		$parameters = $request->all();
		//$ordenar = explode('|', $request->sort);
		$retornar = Stock::select(['stock.id','stock.provs_id','prods_id','prods.codbarras','prods.tipo','prods.marca','prods.modelo','stock.serial','stock.fecha_entrada','stock.fecha_salida','stock.precio_entrada','stock.precio_salida','provs.nombre as proveedor','prods.serializado'])
							->join('prods','stock.prods_id','=','prods.id')
							->join('provs','stock.provs_id','=','provs.id')
							->leftjoin('clientes','stock.clientes_id','=','clientes.id')
							->where('stock.estado','=',true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('prods.tipo','ilike',"%$filtro%");
								$retornar->orWhere('prods.marca','ilike',"%$filtro%");
								$retornar->orWhere('prods.modelo','ilike',"%$filtro%");
								$retornar->orWhere('stock.serial','ilike',"%$filtro%");
								$retornar->orWhere('stock.fecha_salida','ilike',"%$filtro%");
								$retornar->orWhere('stock.fecha_entrada','ilike',"%$filtro%");
								if(is_numeric($filtro))
								{
									$retornar->orWhere('stock.precio_entrada','ilike',"%$filtro%");
									$retornar->orWhere('stock.precio_salida','ilike',"%$filtro%");
									$retornar->orWhere('prods.codbarras','ilike',"%$filtro%");
								}
						});
		}
		if(sizeof($parameters['sortDesc'])> 0 && sizeof($parameters['sortBy'])> 0)
		{
			if($parameters['sortDesc'][0] == true)
			{
				$retornar->orderBy($parameters['sortBy'][0], 'desc');	
			}
			else
			{
				$retornar->orderBy($parameters['sortBy'][0], 'asc');
			}
			
		}

		//					->orderBy("$ordenar[0]", "$ordenar[1]")
		//					->paginate(15);
		return Response::json($retornar->paginate($parameters['itemsPerPage']));
		/*$datatables = app('datatables')
						->of($retornar)->addColumn('action', function($retornar){
							return '<a href="/admin/stock/editar/'.$retornar->id.'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="btn btn-xs btn-danger delete" data-id="'.$retornar->id.'"><i class="glyphicon glyphicon-trash"></i></a><a href="#" class="btn btn-xs btn-success out" data-id="'.$retornar->id.'"><i class="glyphicon glyphicon-check"></i></a>';
						});
		return $datatables->make(true);*/
	}
	public function getProveedoresEliminados()
	{
		$retornar = Proveedores::select(['provs.id', 'provs.nombre', 'provs.tel'])
					->where('estado','=', false);
		$datatables = app('datatables')
						->of($retornar)
						->addColumn('action', function($retornar){
							return '<a href="#" class="btn btn-xs btn-primary recuperar" data-id="'.$retornar->id.'"><i class="glyphicon glyphicon-repeat"></i></a>';
						});
		return $datatables->make(true); 
	}
	public function getProductosEliminados()
	{
		$retornar = Productos::select(['prods.id', 'prods.tipo','prods.marca', 'prods.modelo'])
					->where('estado','=', false);
		$datatables = app('datatables')
						->of($retornar)
						->addColumn('action', function($retornar){
							return '<a href="#" class="btn btn-xs btn-primary recuperar" data-id="'.$retornar->id.'"><i class="glyphicon glyphicon-repeat"></i></a>';
						});
		return $datatables->make(true); 
	}
}
