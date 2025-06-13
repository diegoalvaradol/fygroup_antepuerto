<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $db = (new Database())->getConnection();

  $id = $_POST['id'];

  $query = "SELECT * FROM app_ships JOIN app_ports AS p ON app_ships.port_discharge = p.port_id JOIN app_ship_lines AS sl ON app_ships.ship_line = sl.line_id WHERE ship_id = :id LIMIT 1";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $info) {
    $eta    = $info['eta'];
    $etd    = $info['etd'];
    $pod    = $info['city'] . ' - ' . $info['country'];
    $voyage = $info['voyage'];
    $line   = $info['name'];

    $infoVessel = '<b>ETA: </b>' . htmlspecialchars(date("d-m-Y H:i", strtotime($eta))) . ' / ' . '<b>ETD: </b>' . htmlspecialchars(date("d-m-Y H:i", strtotime($etd)));
    $infoVessel .= '</br>';
    $infoVessel .= '<b>Destino: </b>' . htmlspecialchars($pod) . ' / ' . '<b>Viaje: </b>' . htmlspecialchars($voyage) . ' / ' . '<b>Linea: </b>' . htmlspecialchars($line);
  }

  if ($id != null) {
    if ($infoVessel != null) {
      echo $infoVessel;
    } else {
      echo "Información no encontrada.";
    }
  }
}
