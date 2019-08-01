<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Productos;
use App\TiposDeProductos;
use App\Marcas;
use DB;
use Redirect;
use View;
use Validator;

class ProductosController extends Controller
{
    public function Index() 
    {
    	return view('productos.productos');
    }
    public function NuevoProductoView()
    {
    	return view('productos.productos_nuevo');
    }
    public function EditarProductoView($id)
    {
        $producto = Productos::select('*')->where('id','=', $id)->first();
        return View::make('productos.productos_editar')->with('producto', $producto); 
    }
    public function NuevoProducto(Request $request)
    {
        $parameters = $request->all();
        if(is_string($request->marca))
        {
            $marca = Marcas::create([
                'nombre' => $request->marca,
            ]);
            $request->marca = Marcas::where('nombre','ilike',$request->marca)->first();
        }
        if(is_string($request->tipo))
        {
            $tipo = TiposDeProductos::create([
                'nombre' => $request->tipo
            ]);
            $request->tipo = TiposDeProductos::where('nombre','ilike',$request->tipo)->first();
        }
    	try 
        {
            $validator = Validator::make($request->all(), [
                'tipo' => 'required',
                'modelo' => 'required',
                'marca' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }
            $query = Productos::create([
                'modelo' => $request->modelo,
                'marcas_id' => $request->marca['id'],
                'tipos_id' => $request->tipo['id'] ,
                'ean' => $request->ean ?? null,
                'upc' => $request->upc ?? null,
                'serializado' => $request->serializado
            ]);
            return 'work';
        }
        catch(Exception $e)
        {
        	DB::rollback();
        	return redirect()
                ->back()
                ->withErrors('Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }
    public function EditarProducto(Request $request)
    {
        $this->validate($request, [
            'tipo' => 'required',
            'modelo' => 'required',
            'marca' => 'required'
        ]);
        if(is_string($request->marca))
        {
            $marca = Marcas::create([
                'nombre' => $request->marca,
            ]);
            $request->marca = Marcas::where('nombre','ilike',$request->marca)->first();
        }
        if(is_string($request->tipo))
        {
            $tipo = TiposDeProductos::create([
                'nombre' => $request->tipo
            ]);
            $request->tipo = TiposDeProductos::where('nombre','ilike',$request->tipo)->first();
        }
        Productos::find($request->id)->update([
            'tipos_id' => $request->tipo['id'],
            'modelo' => $request->modelo,
            'marcas_id' => $request->marca['id'],
            'ean' => $request->ean ?? null,
            'upc' => $request->upc ?? null,
            'serializado' => $request->serializado
        ]);
        return 'work';
    }
    public function EliminarProducto(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = Productos::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();
    }
}
