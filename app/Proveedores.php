<?php

namespace App;

use App\PrimaryModel;

class Proveedores extends PrimaryModel
{
    protected $table = 'provs';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre', 'tel','estado'];

    protected $casts = [
        'nombre'  => 'string',
        'tel' => 'string'
    ];
    public function setNombreAttribute($value)
	{
	    $this->attributes['nombre'] = parent::toUpperOrNull($value);
	}
	public function setTelAttribute($value)
	{
	    $this->attributes['tel'] = parent::toUpperOrNull($value);
	}
}
