<?php

declare(strict_types=1);

if (!isset($_GET['url'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$url = $_GET['url'];

// Obtener solo los headers para verificar existencia
$headers = @get_headers($url);

if ($headers && strpos($headers[0], '200') !== false) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}
