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
				return $response->withStatus(200)->withJson(InstitucionRepository::getInstitucion(), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/instituciones/{id}', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(InstitucionRepository::getInstitucionId($args['id']), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/instituciones/region-sanitaria/{id}', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(InstitucionRepository::getInstitucionRegion($args['id']), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/partidos', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(PartidoRepository::getPartido(), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/instituciones/tipo-institucion/{id}', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(InstitucionRepository::getInstitucionPorTipo($args['id']), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/regiones-sanitarias', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(RegionSanitariaRepository::getRegionSanitaria(), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->get('/tipos-instituciones', function ($request, $response, $args) {
				return $response->withStatus(200)->withJson(TipoInstitucionRepository::getTipoInstitucion(), null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
			});

			$app->run();
			
		}
	}

?>