<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $db = (new Database())->getConnection();
  $id = $_POST['id'];

  $query = "SELECT
    app_ships.*,
    sl.*,
    pol.city    AS pol_city,
    pol.country AS pol_country,
    pod.city    AS pod_city,
    pod.country AS pod_country
  FROM app_ships
  JOIN app_ports AS pol ON app_ships.pol = pol.port_id
  JOIN app_ports AS pod ON app_ships.pod = pod.port_id
  JOIN app_ship_lines AS sl ON app_ships.ship_line = sl.line_id
  WHERE ship_id = :id LIMIT 1";

  $stmt = $db->prepare($query);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $info) {
    $eta    = $info['eta'];
    $etd    = $info['etd'];
    $pol    = $info['pol_city'] . ' - ' . $info['pol_country'];
    $pod    = $info['pod_city'] . ' - ' . $info['pod_country'];
    $voyage = $info['voyage'];
    $line   = $info['name'];

    $infoVessel = '<b>ETA: </b>' . htmlspecialchars(date("d-m-Y H:i", strtotime($eta))) . ' / ' . '<b>ETD: </b>' . htmlspecialchars(date("d-m-Y H:i", strtotime($etd)));
    $infoVessel .= '</br>';
    $infoVessel .= '<b>POL: </b>' . htmlspecialchars($pol) . ' / ' . '<b>POD: </b>' . htmlspecialchars($pod);
    $infoVessel .= '</br>';
    $infoVessel .= '<b>Viaje: </b>' . htmlspecialchars($voyage) . ' / ' . '<b>Linea: </b>' . htmlspecialchars($line);
  }

  if ($id != null) {
    if ($infoVessel != null) {
      ?>
      <script>
        $(document).ready(function() {
          var seal = null;
          var isUpdate = $('#isUpdate').val();
          var sealNumber = $('#sealnumber');

          <?php if (stripos($line, 'MAERSK') !== false): ?>
            seal = 'MLCL';
          <?php elseif (stripos($line, 'MSC') !== false || stripos($line, 'MEDITERRANEAN') !== false): ?>
            seal = 'FX';
          <?php endif; ?>

          isUpdate == 0 ? sealNumber.val(seal) : sealNumber.val();
        });
      </script>
      <?php

      echo $infoVessel;
    } else {
      echo "Información no encontrada.";
    }
  } else {
    echo "No se ha seleccionado ninguna nave.";
  }
}
