<?php

namespace App;

use App\PrimaryModel;

class Marcas extends PrimaryModel
{
    protected $table = 'marcas';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre','estado'];

    public function setNombreAttribute($value)
	{
	    $this->attributes['nombre'] = parent::toUpperOrNull($value);
	}
}
