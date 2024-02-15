<?php

/*
    Hecho por: Felipe Rubin
    Modificado para usar PDO
*/

require_once 'app/UsuarioApp.php';

class UsuarioController
{
    
    public function generarToken($body){
        
       if($body){
            $usuarioApp = new UsuarioApp();
            $json = json_decode($body);
            $usuarioApp->generarToken($json->user, $json->password);

       }else{
            header('HTTP/1.1 401 Unauthorized');
            exit;
       }

    }

    public function validarToken($token){

        if($token){
            $usuarioApp = new UsuarioApp();
            $usuarioApp->validarToken($token);
        }else{
            header('HTTP/1.1 401 Unauthorized');
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

}