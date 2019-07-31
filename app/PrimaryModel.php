<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryModel extends Model
{
	protected function toUpperOrNull($value)
	{
		if(is_string($value))
		{
			return strtoupper($value);	
		}
		else
		{
			return $value;
		}
	}
	protected function toLowerOrNull($value)
	{
		if(is_string($value))
		{
			return strtolower($value);	
		}
		else
		{
			return $value;
		}
	}
}