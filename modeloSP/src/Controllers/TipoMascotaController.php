<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Utils\Re;
use App\Models\Tipo_mascota;
use Slim\Routing\RouteContext;

class TipoMascotaController {

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $TipoMasc = new tipo_mascota();

        $dataToken = AutentificadorJWT::ObtenerData($request->getHeader('token')[0]);
        
        $TipoMasc->tipo=$req['tipo'];
       

        $check = $TipoMasc->where('tipo', $TipoMasc->tipo)
                ->first();
            if (empty($check)) {
                $TipoMasc->save();
                $rta = Re::Respuesta(1, "Tipo_Mascota registrada exitosamente");
            } else {
                $rta = Re::Respuesta(0, "Tipo_Mascota ya registrada");
            }

        $response->getBody()->write($rta);
       //$response->getBody()->write($dataToken[0]);

        return $response
        ->withHeader('Content-Type','application/json');
    }
}