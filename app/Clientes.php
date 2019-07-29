<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre', 'apellido','correo','documento','domicilio','tel','estado'];

    public function stock()
    {
        return $this->hasMany('App\Stock');
    }
    public function setNombreAttribute($value)
	{
	    $this->attributes['nombre'] = strtoupper($value);
	}
	public function setApellidoAttribute($value)
	{	
    	$this->attributes['apellido'] = strtoupper($value);
	}
	public function setCorreoAttribute($value)
	{	
    	$this->attributes['correo'] = strtolower($value);
	}
	public function setDomicilioAttribute($value)
	{	
    	$this->attributes['domicilio'] = strtoupper($value);
	}
	public function setTelAttribute($value)
	{	
    	$this->attributes['tel'] = strtoupper($value);
	}
}
