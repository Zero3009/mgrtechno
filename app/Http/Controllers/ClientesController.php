<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Clientes;
use DB;
use Redirect;
use View;
use Validator;

class ClientesController extends Controller
{
    public function Index(){
    	return view('clientes.clientes');
    }
    public function NuevoCliente(Request $request)
    {
        //return $request->tipo['label'];
    	DB::beginTransaction();
    	try 
        {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }
            $query = new Clientes;
                $query->nombre = $request->nombre;
                $query->apellido = $request->apellido;
                $query->documento = $request->documento;
                $query->email = $request->email;
                $query->tel = $request->tel;
                $query->domicilio = $request->domicilio;
            $query->save();
            DB::commit();
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
    public function EditarCliente(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required'
        ]);

        Clientes::find($request->id)->update([
            'nombre' => $request->nombre ?? null,
            'apellido' => $request->apellido ?? null,
            'documento' => $request->documento ?? null,
            'email' => $request->email ?? null,
            'tel' => $request->tel ?? null,
            'domicilio' => $request->domicilio ?? null
        ]);
        return 'work';
    }
}
