<?php

class Mensaje {

    private $tipo;
    private $titulo;
    private $cuerpo;

 
   	function __construct($tipo,$titulo,$cuerpo)
   	{ 
      	$this->tipo   = $tipo; 
      	$this->titulo = $titulo; 
      	$this->cuerpo = $cuerpo; 
   	} 

   	public function getTipo()
   	{
       return $this->tipo;
   	}

   	public function getTitulo()
   	{
   	   return $this->titulo;
   	} 

   	public function getCuerpo()
   	{
   	   return $this->cuerpo;
   	}  

}