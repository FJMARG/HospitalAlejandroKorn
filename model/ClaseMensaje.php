<?php

/**
 * Description of FrontController
 *
 * @russo fede
 */
class ClaseMensaje
{
    # 
    private $type;
    private $msj;
    private $display;
 
	public function __construct($tipo, $msj, $display) 
	{
		
        $this->type    = $tipo;
        $this->msj     = $msj;
        $this->display = $display;   

    }

    public function getType()
    {
        return $this->type;
    }

    public function getMsj()
    {
        return $this->msj;
    }

    public function getDisplay()
    {
        return $this->display;
    }

    public function setDisplay($valor)
    {
        $this->display = $valor;
    }


}