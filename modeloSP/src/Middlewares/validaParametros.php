<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;

use Exception;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Utils\Re;


class  validaParametros{

    public function valParamUsu(Request $request, RequestHandler $handler): Response
    {
        try{

            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['tipo']) && isset($req['email']) && isset($req['clave'])  && isset($req['usuario'])){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros tipo, mail, clave y usuario";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"Erroorr !"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function valParamLogin(Request $request, RequestHandler $handler): Response
    {
        try{

            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['email']) && isset($req['clave']) ){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros email y clave";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"Erroorr !"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function valParamAddTipoMascota(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['tipo'])  ){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros tipo en el body";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"error = >".$e->getMessage()));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function valParamAddMascota(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['nombre']) && isset($req['fecha_nacimiento']) && isset($req['cliente_id']) && isset($req['tipo_mascota_id'])){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros tipo en el body";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"error = >".$e->getMessage()));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }



    public function valParamAddTurno(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['veterinario_id']) && isset($req['mascota_id']) && isset($req['fecha']) ){
                $tiempo = explode(":", $req['fecha']);
                // 1=hora 2 =min 3=seg
                // $rta = $tiempo[3];//.'@'.$tiempo[1].'@'.$tiempo[2].'@'.$tiempo[3];//.'-'.$tiempo[4].'-'.$tiempo[5].'-'.$tiempo[6];
                // $response->getBody()->write( Re::Respuesta(0,$rta));
                 if (($tiempo[1] >= 9 || $tiempo[1] <= 17) /*&& ($tiempo[2] == 00 || $tiempo[2] == 30)*/ ) {

                    $response = $handler->handle($request);
                    
                }else{
                    $response->getBody()->write(Re::Respuesta(0, "Los turnos son de 9 a 17 y tienen un periodo de 30 minutos cada uno"));
                }
                $existingContent = (string) $response->getBody();
                $response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros id_mascota, fecha, hora y id_veterinario en el body";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"error = >".$e->getMessage()));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

}