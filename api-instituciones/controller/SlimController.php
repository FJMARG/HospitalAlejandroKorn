<?php

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	class SlimController {
		public static function startAPI(){

			$config = [
			    'settings' => [
			        'displayErrorDetails' => true,
			    ],
			];

			$app = new \Slim\App($config);

			$app->get('/',  function ($request, $response, $args) {
				FrontController::mostrar('documentacion');
			});

			$app->get('/instituciones', function ($request, $response, $args)   {
				return $response->withJson(InstitucionRepository::getInstitucion(), 200);
			});

			$app->get('/instituciones/{id}', function ($request, $response, $args) {
				return $response->withJson(InstitucionRepository::getInstitucionId($args['id']), 200);
			});

			$app->get('/instituciones/region-sanitaria/{id}', function ($request, $response, $args) {
				return $response->withJson(InstitucionRepository::getInstitucionRegion($args['id']), 200);
			});

			$app->get('/partidos', function ($request, $response, $args) {
				return $response->withJson(PartidoRepository::getPartido(), 200);
			});

			$app->run();
			
		}
	}

?>