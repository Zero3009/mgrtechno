<?php

namespace App;

use App\PrimaryModel;

class Productos extends PrimaryModel
{
    protected $table = 'prods';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['tipo', 'marca','modelo','ean','upc','serializado','estado'];

    protected $casts = [
        'tipo'    	=> 'string',
        'marca' => 'string',
        'modelo' => 'string',
        'ean' => 'string',
        'upc' => 'string',
        'serializado' => 'string',
        'estado' => 'boolean'
    ];

	public function setTipoAttribute($value)
	{
	    $this->attributes['tipo'] = parent::toUpperOrNull($value);
	}
	public function setMarcaAttribute($value)
	{	
    	$this->attributes['marca'] = parent::toUpperOrNull($value);
	}
	public function setModeloAttribute($value)
	{	
    	$this->attributes['modelo'] = parent::toUpperOrNull($value);
	}
	public function setUpcAttribute($value)
	{	
    	$this->attributes['upc'] = parent::toUpperOrNull($value);
	}
	public function setEanAttribute($value)
	{	
		$this->attributes['ean'] = parent::toUpperOrNull($value);	
	}
}
