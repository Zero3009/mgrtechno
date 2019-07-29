<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table = 'provs';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre', 'tel','estado'];

    public function setNombreAttribute($value)
	{
	    $this->attributes['nombre'] = strtoupper($value);
	}
	public function setTelAttribute($value)
	{
	    $this->attributes['tel'] = strtoupper($value);
	}
}
