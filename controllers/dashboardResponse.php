<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/includes.php';

function dashboardResponse($callback)
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        if (!isset($_SESSION['user']) ||!isset($_SESSION['user']['run'])) {
            http_response_code(401);
            throw new Exception('Sesión no válida.');
        }

        $db = Database::get();
        $data = $callback($db);

        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        if (http_response_code() < 400) {
            http_response_code(500);
        }

        echo json_encode(array(
            'error' => $e->getMessage()
        ), JSON_UNESCAPED_UNICODE);
    }

    exit;
}
