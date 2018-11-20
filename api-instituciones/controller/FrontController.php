<?php

class FrontController {
	public static function mostrar($categoria){
		$twig = TwigController::getTwig();
		echo $twig ->render($categoria.'.html.twig', array());
	}
}

?>