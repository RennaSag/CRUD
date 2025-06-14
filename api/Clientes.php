<?php
require_once 'controllers/ClienteController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];
$controller = new ClienteController();

// roteamento simples
switch ($method) {
    case 'GET':
        if (isset($uri[3])) {
            $controller->show($uri[3]);
        } else {
            $controller->index();
        }
        break;
        
    case 'POST':
        $controller->store();
        break;
        
    case 'PUT':
        $controller->update($uri[3]);
        break;
        
    case 'DELETE':
        $controller->destroy($uri[3]);
        break;
}

?>