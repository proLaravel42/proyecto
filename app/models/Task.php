<?php
class Tarea extends Eloquent {
	/*autoriza asignacion masiva*/
	protected $fillable = ['folio',
						   'oficio_referencia',
						   'descripcion'];
						  
	public $timestamps = false;
	
    public function user()
    {
    	return $this->belongsTo('User');
    }
  
}