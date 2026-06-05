<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$company = new company();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$fyGroup = isset($_POST['fygroup']) ? $_POST['fygroup'] : '';
$search = "%{$searchForm}%";

$sql = 'SELECT name FROM app_company WHERE name LIKE :search AND exporter = 1';
$list = $company->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    'id' => '-',
    'text' => 'Seleccione un exportador...',
  ],
];

/* Válido solamente para FYGRoup */
if ($fyGroup) {
    $data = [
        ['id' => 'EXPORTADORA UNIFRUTTI TRADERS SPA', 'text' => 'EXPORTADORA UNIFRUTTI TRADERS SPA'],
        ['id' => 'AGRICOLA EL CALVARIO S.A', 'text' => 'AGRICOLA EL CALVARIO S.A'],
        ['id' => 'FGF TRAPANI S.A', 'text' => 'FGF TRAPANI S.A'],
    ];
} else {
    foreach ($list->getCollection() as $info) {
        $data[] = [
          'id' => $info['name'],
          'text' => $info['name'],
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
