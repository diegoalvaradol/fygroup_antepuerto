<?php
$from = urlencode($_POST['from'] . 'T00:00:00');
$to   = urlencode($_POST['to'] . 'T23:59:59');
$url  = "https://coquimbobus.biznet.cl:8243/EPCO_formulario_op_select_planifacion_naviera_puerto_coquimbo_fecha?fecha1=$from&fecha2=$to";

// Obtener datos desde API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Solo si es necesario
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (empty($data['Entries']['Entry'])) {
  echo '';
  exit;
}

$fechaInicio = (new DateTime($_POST['from']))->format('d-m-Y');
$fechaFin    = (new DateTime($_POST['to']))->format('d-m-Y');

$table = '<div class="container" style="max-width:-webkit-fill-available;">';
$table .= '<div class="table-responsive" style="font-size:13px;">';
$table .= "<h6 class='m-0 font-weight-bold text-primary' style='text-align:center;'>Rango Consultado: $fechaInicio al $fechaFin</h6>";
$table .= '<hr>';
$table .= '<table class="table table-bordered table-hover" table-striped align-middle">';
$table .= '<thead style="background-color:#2653d4; color:white;">';
$table .= '<tr>';
$table .= '<th>Nave</th>';
$table .= '<th>ETA</th>';
$table .= '<th>ETD</th>';
$table .= '<th>Turnos</th>';
$table .= '<th>Loa</th>';
$table .= '<th>Agencia</th>';
$table .= '<th>Cliente</th>';
$table .= '<th>Operación</th>';
$table .= '<th>Tipo Carga</th>';
$table .= '<th>Cantidad</th>';
$table .= '<th>Sitio</th>';
$table .= '<th>Puerto Anterior</th>';
$table .= '<th>Observaciones</th>';
$table .= '<th>Estado</th>';
$table .= '</tr>';
$table .= '</thead>';
$table .= '<tbody>';

foreach ($data['Entries']['Entry'] as $fila) {
  $fechaEta = new DateTime($fila["eta"]);
  $fechaEtd = new DateTime($fila["etd"]);
  $eta      = $fechaEta->format('d-m-Y H:i');
  $etd      = $fechaEtd->format('d-m-Y H:i');

  $loa          = $fila["loa"] === null ? '-' : $fila["loa"];
  $cantidad     = number_format($fila["cantidad"], 0, ',', '.');
  $tipoCantidad = $fila["tipo_cantidad"] === "-1" ? null : $fila["tipo_cantidad"];
  $sitio        = $fila["sitio"] === "-1" ? 'Por definir' : $fila["sitio"];
  $obs          = $fila["observacion"] === null ? '-' : $fila["observacion"];
  $estado       = $fila["estado"] === "Aceptado" ? 'style="color:green;"' : 'style="color:red;"';

  $table .= '<tr>';
  $table .= '<td>' . htmlspecialchars($fila["nave"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($eta) . '</td>';
  $table .= '<td>' . htmlspecialchars($etd) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["turnos"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($loa) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["agencia"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["cliente"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["operacion"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["tipo_carga"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($cantidad . ' ' . $tipoCantidad) . '</td>';
  $table .= '<td>' . htmlspecialchars($sitio) . '</td>';
  $table .= '<td>' . htmlspecialchars($fila["puerto_anterior"]) . '</td>';
  $table .= '<td>' . htmlspecialchars($obs) . '</td>';
  $table .= '<td ' . $estado . '> <b>' . htmlspecialchars($fila["estado"]) . '</b></td>';
  $table .= '</tr>';
}

$table .= '</tbody>';
$table .= '</table>';
$table .= '</div>';
$table .= '</div>';

echo $table;
