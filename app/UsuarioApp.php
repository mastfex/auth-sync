<?php

require_once 'dao/UsuarioDAO.php';
require_once 'dao/PropiedadDAO.php';
require_once 'vendor/JWT.php';
require_once 'vendor/Key.php';

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class UsuarioApp{

    private $usuarioDAO;

    public function generarToken($username, $password) {
        $propiedad = new PropiedadDAO();
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscarPorUsername($username);
        $prop = $propiedad->obtenerPropiedad('PRIVATE_KEY')->getValue();

        if ($usuario && $username == $usuario->getUsername() && password_verify($password, $usuario->getPassword())) {
            $payload = [
                "iat" => time(),
                "exp" => time() + (60*60), // Expira en 1 hora
                "uid" => $usuario->getId()
            ];

            $token = JWT::encode($payload, $prop, 'HS256');
            error_log("Token generado: " . $token);

            return $token;

        } else {

            header('HTTP/1.1 401 Unauthorized');
            error_log("Error al generar token: Usuario o pass faltante");
            return json_encode(["error" => "Token no proporcionado"]);

        }
        
    }

    public function validarToken($token) {
        $propiedad = new PropiedadDAO();
        $prop = $propiedad->obtenerPropiedad('PRIVATE_KEY')->getValue();

        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key($prop, 'HS256'));
                return true;
            }catch (Firebase\JWT\ExpiredException $e) {
                header('HTTP/1.1 401 Unauthorized');
            } catch (Exception $e) {
                header('HTTP/1.1 401 Unauthorized');
                error_log("Error al validar token:".$e->getMessage());
                return json_encode(["error" => "Acceso denegado: " . $e->getMessage()]);
            }
        } else {
            header('HTTP/1.1 401 Unauthorized');
            error_log("Error al validar token: Token no proporcionado");
            return json_encode(["error" => "Token no proporcionado"]);
        }
    }
    
}