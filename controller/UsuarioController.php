<?php

/*
    Hecho por: Felipe Rubin
    Modificado para usar PDO
*/

require_once 'app/UsuarioApp.php';
require_once 'response/Response.php';

class UsuarioController
{
    
    public function generarToken($body){
        
       if(!empty($body)){

            $usuarioApp = new UsuarioApp();
            $json = json_decode($body);

            $token = $usuarioApp->generarToken($json->usuario, $json->password);
            
            if($token != null){
                
                $response = ([
                    "success" => true,
                    "token" => $token
                ]);

                return $this->response($response);

            }else{
                header('HTTP/1.1 401 Unauthorized');
                error_log("Error al generar token: User y pass no proporcionada");
                exit;
            }
                

       }else{
            header('HTTP/1.1 401 Unauthorized');
            exit;
       }

    }

    public function validarToken($token){

        if($token){

            $usuarioApp = new UsuarioApp();

            if($usuarioApp->validarToken($token)){
                header('HTTP/1.1 200 OK');
            }else{
                header('HTTP/1.1 401 Unauthorized');
                error_log("Error al validar token");
                exit;
            }
            
        }else{
            header('HTTP/1.1 401 Unauthorized');
            error_log("Error al validar token: Token no proporcionado");
            exit;
        }

    }


    private function response($data)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); 
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        echo json_encode($data);
    }

    private function createJson($code, $message){

        $fechaHoy = date("Y-m-d H:i:s");
        $response = new Response($code, $message, $fechaHoy);
        $responseArray = $response->toArray(); 

        return $responseArray;

    }

}