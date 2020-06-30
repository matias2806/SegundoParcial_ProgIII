<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Models\Mascota;
use App\Models\Turno;
use App\Utils\Re;
use Slim\Routing\RouteContext;


class TurnosController {
    

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $masc = new Mascota();
        $user =new Usuario();
        $turno = new Turno();

        $fecha = \DateTime::createFromFormat('j-m-Y H:i:s', $req['fecha']);
        

        $turno->mascota_id = $req['mascota_id'];
        $turno->fecha = $fecha;
        $turno->veterinario_id = $req['veterinario_id'];

        // $rta=$turno;

        $checkMasc = $masc
                ->where('id', $turno->mascota_id)
                ->first();

        $checkVete = $user->where('id', $turno->veterinario_id)
            ->where('tipo', '2')
            ->first();

        $checkTurn = $turno->where('fecha', '=' ,$fecha)
            ->where('veterinario_id', $turno->veterinario_id)
            ->first();


        if (!empty($checkMasc)) {
            if (!empty($checkVete)) {
                if (empty($checkTurn)) {
                    $rta = Re::Respuesta(1, "Turno cargado");
                    $turno->save();
                } else {

                    $rta = Re::Respuesta(0, "Turno no disponible con ese veterinario");
                }
            } else {
                $rta = Re::Respuesta(0, "Veterinario no cargado");

            }
        } else {
            $rta = Re::Respuesta(0, "Mascota no registrada");
        }
        
        //$rta = $checkMasc.'@'.$checkTurn.'@'.$checkVete.'@-@'.$dataToken->id;

        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }



    
    public function veoTurnos(Request $request, Response $response, $args)
    {
        $dataToken = AutentificadorJWT::ObtenerData($request->getHeader('token')[0]);
        $rta='a';
        if (isset($args['usuario_id'])) {
            $params = $args['usuario_id'];

            if ($dataToken->tipo == 'cliente') {

                $turno = Turno::join('mascotas', 'turnos.id_mascota', '=', 'mascotas.id')
                    ->select('turnos.hora', 'mascotas.nombre')
                    ->where('turnos.fecha', '=', date("Y-m-d"))
                    ->where('turnos.id_veterinario', '=', $params)
                    ->get();

                $rta = Re::Respuesta(1, $turno);
            } else {

                $turno = Turno::join('mascotas', 'turnos.id_mascota', '=', 'mascotas.id')
                    ->join('usuarios', 'usuarios.id', '=', 'mascotas.id_cliente')
                    ->select('turnos.fecha', 'mascotas.nombre')
                    ->where('usuarios.id', '=', $params)
                    ->get();
                $rta = Re::Respuesta(1, $turno);
            }
        } else {
            $rta = Re::Respuesta(0, 'Se requiere de id de usuario para realizar la busqueda');

        }

        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }
}