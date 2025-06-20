<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Capturar metodo http
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    $controller = new ClienteController();
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;
            
        case 'POST':
            $controller->store();
            break;
            
        case 'PUT':
            if ($id) {
                $controller->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "ID é obrigatório para atualização"]);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                $controller->destroy($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "ID é obrigatório para exclusão"]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(["message" => "Método não permitido"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erro interno do servidor: " . $e->getMessage()]);
}
?>