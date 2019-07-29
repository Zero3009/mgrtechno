<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Stock;
use Validator;
use View;
use Redirect;

class StockController extends Controller
{
    public function Index()
    {
    	return view('stock.stock');
    }
    public function NewStock(Request $request)
    {   
        $parameters = $request->all();
    	DB::beginTransaction();
    	try 
        {
            /*$validator = Validator::make($request->all(), [
                'codbarras' => 'required',
                'proveedor' => 'required',
                'precioEntrada' => 'required',
                'fechaEntrada' => 'required',
            ]);
            if ($validator->fails()) {
                //return 'hola';
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            } */  
            //return sizeof($request->all());

                /*for ($i=0; $i < sizeof($request->all()); $i++) {
                    for($h = 0;$h < sizeof($request[$i]['seriales']);$h++){              
                        $query = new Stock;
                            $query->prods_id = $request[$i]['codbarras']['value'];
                            $query->provs_id = $request[$i]['proveedor']['value'];

                            $query->serial = $request[$i]['seriales'][$h];
                            $query->precio_entrada = $request[$i]['precioEntrada'];
                            
                            $query->fecha_entrada =$request[$i]['state'];
                        
                        $query->save();
                    }
                }*/
            if($parameters['fecha_salida'] != null)
            {
                $disp = false;
            }
            else
            {
                $disp = true;
            }
            if($parameters['seriales'])
            {
                for($i=0;$i <sizeof($parameters['seriales']);$i++)
                {
                    $query= new Stock;
                        $query->prods_id = $parameters['codbarras']['value'];
                        $query->provs_id = $parameters['proveedor']['value'];
                        $query->serial = $parameters['seriales'][$i];
                        $query->precio_entrada = $parameters['precio_entrada'];
                        $query->fecha_entrada = $parameters['fecha_entrada'];
                        $query->disponible = $disp;
                        $query->fecha_salida = $parameters['fecha_salida'] ?? null;
                        $query->precio_salida = $parameters['precio_salida'] ?? null;
                    $query->save();
                }
            }
            else
            {
                $query= new Stock;
                        $query->prods_id = $parameters['codbarras']['value'];
                        $query->provs_id = $parameters['proveedor']['value'];
                        $query->precio_entrada = $parameters['precio_entrada'];
                        $query->fecha_entrada = $parameters['fecha_entrada'];
                        $query->disponible = $disp;
                        $query->fecha_salida = $parameters['fecha_salida'] ?? null;
                        $query->precio_salida = $parameters['precio_salida'] ?? null;
                $query->save();    
            }    
                
            DB::commit();
            return response()->json([
                'status' => 'success',
                'title'  => 'Resultado:',
                'msg'    => 'Exito',
                'type'   => 'success'
            ],200);
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
    public function EditStock(Request $request)
    {
        $this->validate($request, [
            'codbarras' => 'required',
            'fecha_entrada' => 'required',
            'precio_entrada' => 'required',
            'proveedor' => 'required',
            'id' => 'required'
        ]);

        $post = $request->all();
        if($post['fecha_salida'] != null)
        {
            $disp = false;
        }
        else
        {
            $disp = true;
        }

        Stock::find($post['id'])->update([
            'codbarras' => $post['codbarras']['value'],
            'fecha_entrada' => $post['fecha_entrada'],
            'precio_entrada' => $post['precio_entrada'],
            'serial' => $post['serial'],
            'provs_id' => $post['proveedor']['value'],
            'disponible' => $disp,
            'fecha_salida' => $post['fecha_salida'] ?? null,
            'precio_salida' => $post['precio_salida'] ?? null 
        ]);
        
        return Redirect::to('/admin/stock')->with('status', 'Se ha editado correctamente el registro.');
    }
    public function EliminarStock(Request $request)
    {
        //return $request['id'];
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = Stock::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();
        return response()->json([
                'status' => 'success',
                'title'  => 'Resultado:',
                'msg'    => 'Exito',
                'type'   => 'success'
            ],200);
    }
    public function SalidaStock(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = Stock::find($request['id']);
            $queryinfo->disponible = false;
            $queryinfo->fecha_salida = $request->fechaSalida;
            if($request->precio_salida){
                $queryinfo->precio_salida = $request->precioSalida;
            }
        $queryinfo->save();
        return response()->json([
                'status' => 'success',
                'title'  => 'Resultado:',
                'msg'    => 'Exito',
                'type'   => 'success'
            ],200);

    }
}
