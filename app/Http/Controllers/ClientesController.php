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

class ClientesController extends Controller
{
    public function Index(){
    	return view('clientes.clientes');
    }
}
