<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

if (($_POST['action'] ?? '') !== 'generate') {
    http_response_code(400);
    exit('Acción inválida.');
}

set_time_limit(0);

try {
    $db = Database::get();

    $archivo = 'Backup_FYGroup_DB_' . date('Y-m-d_H-i-s') . '.sql';

    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $archivo . '"');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');

    echo "-- Backup Sistema Integral FYGroup\n";
    echo "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

    $tablas = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tablas as $tabla) {
        // El nombre viene desde SHOW TABLES, pero se escapa por seguridad.
        $tablaSegura = str_replace('`', '``', $tabla);

        echo "-- Tabla: `{$tablaSegura}`\n\n";
        $create = $db->query("SHOW CREATE TABLE `{$tablaSegura}`") ->fetch(PDO::FETCH_ASSOC);
        $estructura = $create['Create Table'] ?? null;

        if ($estructura === null) {
            continue;
        }

        echo "DROP TABLE IF EXISTS `{$tablaSegura}`;\n";
        echo $estructura . ";\n\n";

        $datos = $db->query("SELECT * FROM `{$tablaSegura}`");

        while ($fila = $datos->fetch(PDO::FETCH_ASSOC)) {
            $columnas = array_map(
                fn($columna) => '`' . str_replace('`', '``', $columna) . '`',
                array_keys($fila)
            );

            $valores = array_map(
                function ($valor) use ($db) {
                    return $valor === null ? 'NULL' : $db->quote((string) $valor);
                },
                array_values($fila)
            );

            echo "INSERT INTO `{$tablaSegura}` (";
            echo implode(', ', $columnas);
            echo ') VALUES (';
            echo implode(', ', $valores);
            echo ");\n";
        }

        echo "\n";
    }

    echo "SET FOREIGN_KEY_CHECKS=1;\n";
} catch (PDOException $e) {
    http_response_code(500);

    // No mostrar detalles internos de la base de datos al usuario.
    exit('No fue posible generar el respaldo.');
}
