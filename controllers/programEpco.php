<?php
declare(strict_types=1);

ob_start();

/**
 * Escapa contenido para HTML.
 */
function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/**
 * Formatea una fecha recibida desde la API.
 */
function formatDate(mixed $date): string
{
    if (empty($date)) {
        return '-';
    }

    try {
        return (new DateTime((string) $date))->format('d-m-Y H:i');
    } catch (Exception) {
        return '-';
    }
}

/*
|--------------------------------------------------------------------------
| Validar parámetros
|--------------------------------------------------------------------------
*/
$from = $_POST['from'] ?? null;
$to   = $_POST['to'] ?? null;

if (empty($from) || empty($to)) {
    ob_end_clean();
    echo '';
    exit;
}

/*
|--------------------------------------------------------------------------
| Validar fechas
|--------------------------------------------------------------------------
*/
try {
    $fechaFrom = new DateTime($from);
    $fechaTo   = new DateTime($to);
} catch (Exception) {
    ob_end_clean();
    echo '';
    exit;
}

/*
|--------------------------------------------------------------------------
| Preparar parámetros API
|--------------------------------------------------------------------------
*/
$fechaApiFrom = $fechaFrom->format('Y-m-d') . 'T00:00:00';
$fechaApiTo   = $fechaTo->format('Y-m-d') . 'T23:59:59';

$query = http_build_query([
    'fecha1' => $fechaApiFrom,
    'fecha2' => $fechaApiTo
]);

$url = 'https://coquimbobus.biznet.cl:8243/EPCO_formulario_op_select_planifacion_naviera_puerto_coquimbo_fecha?' . $query;

/*
|--------------------------------------------------------------------------
| Consultar API
|--------------------------------------------------------------------------
*/
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_HTTPHEADER     => [
        'Accept: application/json'
    ],

    // Si el servidor utiliza un certificado SSL válido,
    // estas opciones deben permanecer en TRUE.
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

$response = curl_exec($ch);

if ($response === false) {
    curl_close($ch);

    ob_end_clean();
    echo '';
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

/*
|--------------------------------------------------------------------------
| Validar respuesta HTTP
|--------------------------------------------------------------------------
*/
if ($httpCode < 200 || $httpCode >= 300) {
    ob_end_clean();
    echo '';
    exit;
}

/*
|--------------------------------------------------------------------------
| Decodificar JSON
|--------------------------------------------------------------------------
*/
$data = json_decode($response, true);

if (!is_array($data) || empty($data['Entries']['Entry']) ||!is_array($data['Entries']['Entry'])) {
    ob_end_clean();
    echo '';
    exit;
}

/*
|--------------------------------------------------------------------------
| Fechas mostradas al usuario
|--------------------------------------------------------------------------
*/
$fechaInicio = $fechaFrom->format('d-m-Y');
$fechaFin    = $fechaTo->format('d-m-Y');

$count = 0;

/*
|--------------------------------------------------------------------------
| Construcción de tabla
|--------------------------------------------------------------------------
*/

$table = '<div class="container" style="max-width:-webkit-fill-available;">';

$table .= '<div class="table-responsive" style="font-size:13px;">';

$table .= "
    <h6 class='m-0 font-weight-bold text-primary' style='text-align:center;'>
        Rango Consultado: " . e($fechaInicio) . " al " . e($fechaFin) . ".
    </h6>
";

$table .= '<hr>';

$table .= '
    <table class="table table-bordered table-hover table-striped align-middle">
';

$table .= '
    <thead style="background-color:#2653d4; color:white;">
        <tr>
            <th>Nave</th>
            <th>ETA</th>
            <th>ETD</th>
            <th>Turnos</th>
            <th>Loa</th>
            <th>Agencia</th>
            <th>Cliente</th>
            <th>Operación</th>
            <th>Tipo Carga</th>
            <th>Cantidad</th>
            <th>Sitio</th>
            <th>Puerto Anterior</th>
            <th>Observaciones</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
';

/*
|--------------------------------------------------------------------------
| Recorrer registros
|--------------------------------------------------------------------------
*/
foreach ($data['Entries']['Entry'] as $fila) {

    $eta = formatDate($fila['eta'] ?? null);
    $etd = formatDate($fila['etd'] ?? null);

    $loa = (
        !isset($fila['loa']) ||
        $fila['loa'] === null ||
        $fila['loa'] === ''
    )
        ? '-'
        : $fila['loa'];

    $cantidad = number_format(
        (float) ($fila['cantidad'] ?? 0),
        0,
        ',',
        '.'
    );

    $tipoCantidad = (
        !isset($fila['tipo_cantidad']) ||
        $fila['tipo_cantidad'] === '-1' ||
        $fila['tipo_cantidad'] === ''
    )
        ? ''
        : $fila['tipo_cantidad'];

    $sitio = (
        !isset($fila['sitio']) ||
        $fila['sitio'] === '-1' ||
        $fila['sitio'] === ''
    )
        ? 'Por definir'
        : $fila['sitio'];

    $obs = (
        !isset($fila['observacion']) ||
        $fila['observacion'] === null ||
        $fila['observacion'] === ''
    )
        ? '-'
        : $fila['observacion'];

    $estadoTexto = $fila['estado'] ?? '-';

    $estadoStyle = ($estadoTexto === 'Aceptado')
        ? 'color:green;'
        : 'color:red;';


    /*
    |--------------------------------------------------------------------------
    | Fila
    |--------------------------------------------------------------------------
    */
    $table .= '<tr>';

    $table .= '<td>' . e($fila['nave'] ?? '-') . '</td>';

    $table .= '<td>' . e($eta) . '</td>';

    $table .= '<td>' . e($etd) . '</td>';

    $table .= '<td>' . e($fila['turnos'] ?? '-') . '</td>';

    $table .= '<td>' . e($loa) . '</td>';

    $table .= '<td>' . e($fila['agencia'] ?? '-') . '</td>';

    $table .= '<td>' . e($fila['cliente'] ?? '-') . '</td>';

    $table .= '<td>' . e($fila['operacion'] ?? '-') . '</td>';

    $table .= '<td>' . e($fila['tipo_carga'] ?? '-') . '</td>';

    $table .= '<td>' . e(
        trim($cantidad . ' ' . $tipoCantidad)
    ) . '</td>';

    $table .= '<td>' . e($sitio) . '</td>';

    $table .= '<td>' . e($fila['puerto_anterior'] ?? '-') . '</td>';

    $table .= '<td>' . e($obs) . '</td>';

    $table .= '
        <td style="' . e($estadoStyle) . '">
            <b>' . e($estadoTexto) . '</b>
        </td>
    ';

    $table .= '</tr>';

    $count++;
}

$table .= '</tbody>';

$table .= '</table>';

$table .= "
    <p class='m-1 font-weight-bold text-grey'>
        Total de registros: " . e($count) . "
    </p>
";

$table .= '</div>';

$table .= '</div>';

/*
|--------------------------------------------------------------------------
| Salida
|--------------------------------------------------------------------------
*/
echo $table;
ob_end_flush();
