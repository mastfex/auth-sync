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
        $exp = $propiedad->obtenerPropiedad('EXP_TOKEN')->getValue();

        if ($usuario && $username == $usuario->getUsername() && password_verify($password, $usuario->getPassword())) {
            $payload = [
                "iat" => time(),
                "exp" => time() + ($exp), 
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

        error_log("Token que llega: " . $token);

        $token = preg_replace('/^Bearer\s/', '', $token);
        
        if (empty($token)) {
            header('HTTP/1.1 401 Unauthorized');
            error_log("Error al validar token: Token no proporcionado");
            return json_encode(["error" => "Token no proporcionado"]);
        }
    
        $propiedad = new PropiedadDAO();
        $prop = $propiedad->obtenerPropiedad('PRIVATE_KEY')->getValue();
    
        try {
            $decoded = JWT::decode($token, new Key($prop, 'HS256'));
            return true;
        } catch (Firebase\JWT\ExpiredException $e) {
            header('HTTP/1.1 401 Unauthorized');
            error_log("Token expirado: " . $e->getMessage());
            return false;
        } catch (\UnexpectedValueException $e) {
            header('HTTP/1.1 401 Unauthorized');
            error_log("Token inválido: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            header('HTTP/1.1 401 Unauthorized');
            error_log("Error al validar token: " . $e->getMessage());
            return false;
        }
    }
    
}