<?php

require_once 'dao/UsuarioDAO.php';
require_once 'vendor/JWT.php';
require_once 'vendor/KEY.php';

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class UsuarioApp{

    private $usuarioDAO;
    private $key = "49Zwrga0NdsWodqFTncoVcLeGJC3F+SCH7mwNUUgRAY=";

    public function generarToken($username, $password) {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscarPorUsername($username);
        if ($usuario && password_verify($password, $usuario->getPassword())) {
            $payload = [
                "iss" => "tu_issuer",
                "aud" => "tu_audience",
                "iat" => time(),
                "exp" => time() + (60*60), // Expira en 1 hora
                "uid" => $usuario->getId()
            ];

            return JWT::encode($payload, $this->key, 'HS256');
        } else {
            throw new Exception("Credenciales inválidas.");
        }
    }


    public function validarToken($token) {
        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
                http_response_code(200);
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(["error" => "Acceso denegado: " . $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Token no proporcionado"]);
        }
    }
    
}