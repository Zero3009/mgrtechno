<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Proveedores;
use App\Productos;
use App\Stock;
use App\Clientes;
use Response;
use DB;
use Vuetable;

class DatatablesController extends Controller
{
	public function GetProveedores(Request $request)
	{
		$parameters = $request->all();
		$retornar = Proveedores::select(['proveedores.id', 'proveedores.nombre', 'proveedores.tel'])
						->where('estado','=', true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('proveedores.tel','ilike',"%$filtro%");
								$retornar->orWhere('proveedores.nombre','ilike',"%$filtro%");
								/*if(is_numeric($filtro))
								{
									$retornar->orWhere('productos.codbarras','ilike',"%$filtro%");
								}*/
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
	public function GetProductos(Request $request)
	{
		$parameters = $request->all();
		//return $parameters['search'];
		$retornar = Productos::select(['productos.id', 'tipos_de_productos.nombre as tipo','marcas.nombre as marca','productos.modelo','productos.ean','productos.upc','productos.serializado'])
					->join('marcas','productos.marcas_id','=','marcas.id')
					->join('tipos_de_productos','tipos_de_productos.id','=','productos.tipos_id')
						->where('productos.estado','=', true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('tipo','ilike',"%$filtro%");
								$retornar->orWhere('marca','ilike',"%$filtro%");
								$retornar->orWhere('productos.modelo','ilike',"%$filtro%");
								/*if(is_numeric($filtro))
								{*/
									$retornar->orWhere('productos.ean','ilike',"%$filtro%");
									$retornar->orWhere('productos.upc','ilike',"%$filtro%");
								//}
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
		$retornar = Stock::select(['stock.id','stock.proveedores_id','productos_id','productos.ean','productos.upc','tipos_de_productos.nombre as tipo','marcas.nombre as marca','productos.modelo','stock.serial','stock.fecha_entrada','stock.fecha_salida','stock.precio_entrada','stock.precio_salida','proveedores.nombre as proveedor','productos.serializado','clientes.nombre','clientes.apellido','clientes.id as clienteid','clientes.email','clientes.documento','clientes.domicilio'])
							->join('productos','stock.productos_id','=','productos.id')
							->join('proveedores','stock.proveedores_id','=','proveedores.id')
							->join('marcas','productos.marcas_id','=','marcas.id')
							->join('tipos_de_productos','productos.tipos_id','=','tipos_de_productos.id')
							->leftjoin('clientes','stock.clientes_id','=','clientes.id')
							->where('stock.estado','=',true);
		if($parameters['datafilter'] == 'disponible')
		{
			$retornar->where('disponible','=',true);
		}
		else if ($parameters['datafilter'] == 'nodisponible') 
		{
			$retornar->where('disponible','=',false);
		}
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('productos.tipo','ilike',"%$filtro%");
								$retornar->orWhere('productos.marca','ilike',"%$filtro%");
								$retornar->orWhere('productos.modelo','ilike',"%$filtro%");
								$retornar->orWhere('stock.serial','ilike',"%$filtro%");
								$retornar->orWhere('stock.fecha_salida','ilike',"%$filtro%");
								$retornar->orWhere('productos.ean','ilike',"%$filtro%");
								$retornar->orWhere('productos.upc','ilike',"%$filtro%");
								$retornar->orWhere('stock.fecha_entrada','ilike',"%$filtro%");
								if(is_numeric($filtro))
								{
									$retornar->orWhere('stock.precio_entrada','ilike',"%$filtro%");
									$retornar->orWhere('stock.precio_salida','ilike',"%$filtro%");
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
		$retornar = Proveedores::select(['proveedores.id', 'proveedores.nombre', 'proveedores.tel'])
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
		$retornar = Productos::select(['productos.id', 'productos.tipo','productos.marca', 'productos.modelo'])
					->where('estado','=', false);
		$datatables = app('datatables')
						->of($retornar)
						->addColumn('action', function($retornar){
							return '<a href="#" class="btn btn-xs btn-primary recuperar" data-id="'.$retornar->id.'"><i class="glyphicon glyphicon-repeat"></i></a>';
						});
		return $datatables->make(true); 
	}
	public function getClientes(Request $request)
	{

		$parameters = $request->all();
		$retornar = Clientes::select('id','nombre', 'apellido','email','documento','domicilio','tel')
							->where('estado','=', true);
		if($parameters['search'] != null)
		{
			$filtro = $parameters['search'];
			$retornar = $retornar->where(function ($retornar) use ($filtro) {
								$retornar->orWhere('nombre','ilike',"%$filtro%");
								$retornar->orWhere('apellido','ilike',"%$filtro%");
								$retornar->orWhere('email','ilike',"%$filtro%");
								$retornar->orWhere('domicilio','ilike',"%$filtro%");
								if(is_numeric($filtro))
								{
									$retornar->orWhere('documento','ilike',"%$filtro%");
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
	}

}
