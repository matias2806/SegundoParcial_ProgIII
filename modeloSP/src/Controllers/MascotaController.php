<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Models\Mascota;
use App\Utils\Re;


class MascotaController {

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $masc = new Mascota();
    
        
        $masc->nombre=$req['nombre'];
        $masc->fecha_nacimiento = \DateTime::createFromFormat('j-m-Y', $req['fecha_nacimiento']);
        $masc->cliente_id=$req['cliente_id'];
        $masc->tipo_mascota_id=$req['tipo_mascota_id'];

        if ( !empty($masc->nombre) && !empty( $masc->cliente_id) && !empty($masc->tipo_mascota_id) ) {
            $masc->save();
            $rta = Re::Respuesta(1, "Mascota registrada exitosamente");

        }else{
            $rta = Re::Respuesta(0, "La mascota no fue registrada por que sus campos estan vacios");

        }

        $response->getBody()->write($rta);
       //$response->getBody()->write($dataToken[0]);

        return $response
        ->withHeader('Content-Type','application/json');
    }
}