<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$tracking = new tracking();
$id = $_POST['container'] ?? '';

$tracking->getTableTracking($id);
