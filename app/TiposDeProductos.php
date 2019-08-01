<?php

namespace App;

use App\PrimaryModel;

class TiposDeProductos extends PrimaryModel
{
    protected $table = 'tipos_de_productos';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre','estado'];

    public function setNombreAttribute($value)
	{
	    $this->attributes['nombre'] = parent::toUpperOrNull($value);
	}
}
