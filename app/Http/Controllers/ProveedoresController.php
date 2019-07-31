<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Proveedores;
use DB;
use Redirect;
use View;
use Validator;

class ProveedoresController extends Controller
{
    public function Index(){
    	return view('proveedores.proveedores');
    }
    public function NuevoProveedorView()
    {
        return view('proveedores.proveedores_nuevo');
    }
    public function EliminarProveedor(Request $request)
    {
        //return $request['id'];
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = Proveedores::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();
        return Redirect::to('/admin/proveedores')->with('status', 'Se ha eliminado correctamente el usuario.');
    }
    public function EditarProveedor(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $post = $request->all();
        Proveedores::find($post['id'])->update([
            'nombre' => $post['nombre'],
            'tel' => $post['tel']
        ]);
        return Redirect::to('/admin/proveedores')->with('status', 'Se ha editado correctamente la etiqueta.');
    }
    public function NuevoProveedor(Request $request){
    	DB::beginTransaction();
    	try 
        {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
            ]);
            $parameters = $request->all();
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }
            $query = Proveedores::create([
                'nombre' => $parameters['nombre'],
                'tel' => $parameters['tel'] ?? null
            ]);

            DB::commit();
            return 'work';
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            //return $e;
            if($e->getCode() == 23502)
            {
                return response()->json([
                    'status' => 'error',
                    'title'  => 'Resultado: ya existe el registro',
                    'msg'    =>  $e->getMessage(),
                    'type'   => 'error' 
                ],400);    
            }
            return response()->json([
                'status' => 'error',
                'msg'    => 'Error ' . $e->getCode() . ': ' . $e->getMessage() . 'Contacte a informática'
            ],404);
        }
    }
}
