<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $db = (new Database())->getConnection();
  $id = $_POST['id'];

  $sql = "SELECT
						v.vessel_name AS nave,
						v.voyage AS viaje,
						a.exporter,
						a.container,
						a.pallets_quantity,
						a.guide_number,
						a.comodity
					FROM app_outer_port a
					JOIN app_ships v ON v.ship_id = a.vessel_id
					WHERE a.vessel_id = :id AND a.origin = 1
					ORDER BY a.exporter, a.container";

  $stmt = $db->prepare($sql);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $nave  = $rows[0]['nave'] ?? 'N/A';
  $viaje = $rows[0]['viaje'] ?? 'N/A';

  if (!$rows) {
    echo "<h5>Liquidación de Nave: <strong>" . htmlspecialchars($nave) . "</strong> - Viaje: <strong>" . htmlspecialchars($viaje) . "</strong></h5>";
    echo "<p>No se ha encontrado una liquidación para la motonave consultada.</p>";

    return;
  }

  $html = "<h5>Liquidación de Nave: <strong>" . htmlspecialchars($nave) . "</strong> - Viaje: <strong>" . htmlspecialchars($viaje) . "</strong></h5>";
  $html .= "<table border='1' cellpadding='6' cellspacing='0'>
	<thead>
		<tr>
			<th>Nave</th>
			<th>Viaje</th>
			<th>Exportador</th>
			<th>N° Guía</th>
			<th>Condición</th>
			<th>Contenedor</th>
			<th>Pallets</th>
		</tr>
	</thead>
	<tbody>";

  foreach ($rows as $row) {
    $html .= "<tr>
			<td>" . htmlspecialchars($row['nave']) . "</td>
			<td>" . htmlspecialchars($row['viaje']) . "</td>
			<td>" . htmlspecialchars($row['exporter']) . "</td>
			<td>" . htmlspecialchars($row['guide_number']) . "</td>
			<td>" . htmlspecialchars($row['comodity']) . "</td>
			<td>" . htmlspecialchars($row['container']) . "</td>
			<td>" . htmlspecialchars($row['pallets_quantity']) . "</td>
		</tr>";
  }

  $html .= "</tbody>
	</table>";

  $html .= '<div style="margin-top: 1rem;">
		<a href="../controllers/exportReportPDF.php?id=' . intval($id) . '" download class="btn btn-sm btn-success">
				<i class="fa-solid fa-download"></i> Descargar PDF
		</a>
	</div>';

  echo $html;
}
