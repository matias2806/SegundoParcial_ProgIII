<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Utils\Re;
use Slim\Routing\RouteContext;

class UsuariosController {

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function getId(Request $request, Response $response, $args)
    {
        $contenido = RouteContext::fromRequest($request);
        $ruta = $contenido->getRoute();
        $datoId = $ruta->getArgument('id');

        $usuario =  Usuario::find($datoId);
       
        $rta = json_encode($usuario);

        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $user = new Usuario();
        // 1cliente
        // 2 veterinario
        //3 admin
        $user->usuario =$req['usuario'];
        $user->tipo=$req['tipo'];
        $user->email=$req['email'];
        $user->clave = password_hash( $req['clave'], PASSWORD_BCRYPT);
       
       $flag = $user->where('email',$user->email)->first();
       
       if(empty($flag) && ($user->tipo == '1' || $user->tipo=='2' || $user->tipo =='3')){
            $user->save();
            $rta = Re::Respuesta(1, "Usuario Cargado" );
       }else{
          
            $rta = Re::Respuesta(0, "Mail ya registrado o tipo de usuario invalido");
       }

        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function login(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $user = new Usuario();
        $user->email=$req['email'];
        $datoClave=$req['clave'];

        $selec = $user->where('email',$user->email)->first();
        if(!empty($selec)){
            $hasheo = $selec->clave;
            if(password_verify($datoClave, $hasheo)){
                $Objeto = new \stdClass();

                $Objeto->id = $selec->id;
                $Objeto->email = $selec->email;
                $Objeto->tipo = $selec->tipo;
                $Objeto->usuario = $selec->usuario;

                $rta = Re::Respuesta(1, "Token: ".AutentificadorJWT::CrearToken($Objeto));

            }else{
                $rta = Re::Respuesta(0,"clave incorrecta");
            }

        }
        else{
            $rta = Re::Respuesta(0,"Mail no registrado");
        }


        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }



}
