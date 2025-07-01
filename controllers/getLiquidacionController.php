<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  $db = (new Database())->getConnection();

  $sql = "SELECT
          v.vessel_name AS nave,
          v.eta AS eta,
          v.etd AS etd,
          v.voyage AS viaje,
          p.city AS ciudad,
          p.country AS pais,
          l.name AS linea,
          a.exporter,
          a.container,
          a.pallets_quantity,
          a.guide_number,
          a.comodity
        FROM app_outer_port a
        JOIN app_ships v ON v.ship_id = a.vessel_id
        JOIN app_ports p ON p.port_id = v.port_discharge
        JOIN app_ship_lines l ON l.line_id = v.ship_line
        WHERE a.vessel_id = :id AND a.origin = 1
        ORDER BY a.exporter, a.container";

  $stmt = $db->prepare($sql);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  /* Si no encuentra una liquidación de la motonave solicitada */
  if (!$rows) {
    $html = '<div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">';
    $html .= '<div>';
    $html .= '<i class="fa-solid fa-triangle-exclamation me-2"></i>';
    $html .= '<strong> Atención:</strong> No hay información disponible para generar la liquidación de esta nave.';
    $html .= '</div>';
    $html .= '</div>';

    /* Si encuentra una liquidación de la motonave consultada */
  } else {
    $html = '<div style="text-align: center; margin-bottom: 1rem;">';
    $html .= '<a href="../controllers/exportReportPDF.php?id=' . intval($id) . '" download class="btn btn-mn btn-success">';
    $html .= '<i class="fa-solid fa-file-pdf"></i> Descargar PDF de Liquidación';
    $html .= '</a>';
    $html .= '</div>';
  }

  echo $html;
}
