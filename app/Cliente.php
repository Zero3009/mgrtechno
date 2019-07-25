<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre', 'apellido','correo','documento','domicilio'];

    public function stock()
    {
        return $this->hasMany('App\Stock');
    }
}
