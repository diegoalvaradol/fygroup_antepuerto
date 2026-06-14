<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$port = new port();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search = "%{$searchForm}%";

$sql = 'SELECT * FROM app_ports WHERE city LIKE :search GROUP BY city LIMIT 20';
$list = $port->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    'id' => '-',
    'text' => 'Seleccione un puerto...',
  ],
];

foreach ($list->getCollection() as $info) {
    $data[] = [
      'id' => $info['port_id'],
      'text' => $info['city'] . ' - ' . $info['country'],
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
