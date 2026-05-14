<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
    $ship = new ship();
    $id = $_POST['id'];

    $sql = 'SELECT
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
    WHERE ship_id = :id LIMIT 1
  ';

    $list = $ship->findAllStatic($sql, ['id' => $id]);

    $infoVessel = null;
    $primary = '#2563eb';

    foreach ($list->getCollection() as $info) {
        $eta = $info['eta'];
        $etd = $info['etd'];
        $pol = $info['pol_city'] . ' - ' . $info['pol_country'];
        $pod = $info['pod_city'] . ' - ' . $info['pod_country'];
        $voyage = $info['voyage'];
        $line = $info['name'];

        $infoVessel = '
            <div style=" border:1px solid #e5e7eb; border-left:4px solid ' . $primary . '; border-radius:8px; padding:12px 16px; background:#f9fafb; font-family:Arial, sans-serif; font-size:14px; width:500px;">
                <div style="margin-bottom:6px;">
                <b style="color:' . $primary . ';">ETA:</b> ' . htmlspecialchars(date('d-m-Y H:i', strtotime($eta))) . '
                <span style="margin:0 6px; color:#9ca3af;">|</span>
                <b style="color:' . $primary . ';">ETD:</b> ' . htmlspecialchars(date('d-m-Y H:i', strtotime($etd))) . '
                </div>

                <div style="margin-bottom:6px;">
                <b style="color:' . $primary . ';">POL:</b> ' . htmlspecialchars($pol) . '
                <span style="margin:0 6px; color:#9ca3af;">|</span>
                <b style="color:' . $primary . ';">POD:</b> ' . htmlspecialchars($pod) . '
                </div>

                <div>
                <b style="color:' . $primary . ';">Viaje:</b> ' . htmlspecialchars($voyage) . '
                <span style="margin:0 6px; color:#9ca3af;">|</span>
                <b style="color:' . $primary . ';">Línea:</b> ' . htmlspecialchars($line) . '
                </div>
            </div>
        ';
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
            echo 'Información no encontrada.';
        }
    } else {
        echo 'No se ha seleccionado una nave.';
    }
}
