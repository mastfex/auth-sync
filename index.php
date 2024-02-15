<?php

require_once 'controller/UsuarioController.php';

$method = $_SERVER['REQUEST_METHOD'];
$url = $_GET['url'] ?? '';

$parts = explode('/', $url);
$controllerName = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';

// RUTA CITAS
if ($controllerName == 'auth') {

    $controller = new UsuarioController();

    if ($method == 'POST') {

        switch ($action) {
            case 'token':
                $body = file_get_contents('php://input');
                $controller->generarToken($body);
            break;

            case 'validar':
                $headers = getallheaders();
                $token = $headers["Authorization"] ?? '';
                $controller->validarToken($token);
            break;
        }

    }

}


