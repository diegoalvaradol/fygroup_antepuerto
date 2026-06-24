<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

/**
 * Method generateSecureLink //Genera un token de seguridad para acceder a un módulo específico.
 *
 * @param  $module [modulo]
 * @param  $area   [area]
 * @return String
 */
function generateSecureLink($module, $area = 'myFY', $ttl = 300)
{
    $secret = 'FYGROUP_DIEGO_2026_0517';
    $t = time();

    $data = $module . '|' . $area . '|' . $t . '|' . $ttl;

    $sig = hash_hmac('sha256', $data, $secret);

    return "./?pag={$module}&area={$area}&t={$t}&ttl={$ttl}&sig={$sig}";
}

/**
 * Valida mkey con expiración y módulo
 */
function validateSecureLink($module, $area, $time, $ttl, $sig)
{
    $secret = 'FYGROUP_DIEGO_2026_0517';

    // 1. verificar expiración
    if ((time() - (int) $time) > (int) $ttl) {
        return false;
    }

    // 2. reconstruir firma
    $data = $module . '|' . $area . '|' . $time . '|' . $ttl;

    $expected = hash_hmac('sha256', $data, $secret);

    // 3. comparar seguro
    return hash_equals($expected, $sig);
}

/**
 * Limpieza de tokens expirados (opcional)
 */
function cleanExpiredMkeys()
{
    if (!isset($_SESSION['mkeys'])) {
        return;
    }

    $now = time();

    foreach ($_SESSION['mkeys'] as $key => $data) {
        if (($now - $data['time']) > $data['ttl']) {
            unset($_SESSION['mkeys'][$key]);
        }
    }
}

/**
 * Method esLocalhost //Verifica si la solicitud proviene de localhost o una IP local.
 *
 * @return String
 */
function esLocalhost()
{
    $whitelist = ['127.0.0.1', '::1', 'localhost'];

    return in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_NAME'], $whitelist);
}

function isDev()
{
    return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
}

/**
 * Method getCurrentUser //Obtiene el usuario actual de la sesión.
 *
 * @return String
 */
function getCurrentUser()
{
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    } else {
        return null;
    }
}

/**
 * Method formatDate //Formatea una fecha a un formato específico.
 *
 * @param  $date   [fecha a formatear]
 * @param  $format [formato de fecha, por defecto 'Y-m-d H:i:s']
 * @return String
 */
function formatDate($date, $format = 'd-m-Y H:i:s')
{
    if ($date == '0000-00-00 00:00:00' || $date == '0000-00-00' || $date == null) {
        return '';
    }

    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    if (!$dateTime) {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    }

    if ($dateTime) {
        return $dateTime->format($format);
    } else {
        return '';
    }
}

/**
 * Method formatCarPlate //Formatea una patente a formato chileno
 *
 * @param $plate  [patente a formatear]
 *
 * @return String
 */
function formatCarPlate($plate)
{
    $plate = strtoupper(str_replace('-', '', trim($plate)));

    if (preg_match('/^([A-Z]{4})(\d{2})$/', $plate, $m)) {
        return $m[1] . '-' . $m[2]; // XXXX-11
    }

    if (preg_match('/^([A-Z]{2})(\d{4})$/', $plate, $m)) {
        return $m[1] . '-' . $m[2]; // XX-1111
    }

    return $plate;
}

function timeAgo($datetime)
{
    if (!$datetime) {
        return '';
    }

    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return 'hace segundos';
    }
    if ($diff < 3600) {
        return 'hace ' . floor($diff / 60) . ' min';
    }
    if ($diff < 86400) {
        return 'hace ' . floor($diff / 3600) . ' h';
    }

    return 'hace ' . floor($diff / 86400) . ' días';
}

function cleanInput($value)
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function jsonResponse($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function normalizeString($str)
{
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9 ]/', '', $str);

    return trim($str);
}

/**
 * Method mostrarAccesoDenegado
 *
 * @param  $usuario [usuario que intenta acceder]
 * @param  $pagina  [página  a la que intentó acceder]
 * @param  $url     [URL     de la página]
 * @return String
 */
function mostrarAccesoDenegado($usuario, $pagina, $url)
{
    $isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);
    $baseUrl = $isLocal ? 'http://localhost/ssl-chile/' : 'https://antepuerto.fygroup.cl/';
    $logo = $baseUrl . 'logos/logo-fygroup-circle-bg-removed.png';

    $emails = [
        'diego.alvaraado@gmail.com',
        'alvarado@fygroup.cl',
        'flores@fygroup.cl',
    ];

    echo '
        <!DOCTYPE html>
        <html lang="es-CL">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
            <title>FYGroup | Acceso Denegado</title>
            <meta http-equiv="refresh" content="5;url=dashboard.php">

            <!-- FontAwesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

            <style>
                body {
                    margin: 0;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-family: Arial, sans-serif;
                    background: linear-gradient(135deg, #1f2933, #111827);
                    color: #fff;
                }
                .card {
                    background: #1f2937;
                    padding: 2.5rem 3rem;
                    border-radius: 12px;
                    text-align: center;
                    box-shadow: 0 20px 40px rgba(0,0,0,.4);
                    max-width: 420px;
                }
                .card img {
                    width: 180px;
                    margin-bottom: 15px;
                }
                .icon {
                    font-size: 45px;
                    margin-bottom: 10px;
                    color: #ef4444;
                }
                .card h1 {
                    font-size: 2rem;
                    margin-bottom: .5rem;
                }
                .card p {
                    opacity: .85;
                    margin-bottom: 1rem;
                }
                .card small {
                    display: block;
                    opacity: .6;
                    margin-bottom: 1.5rem;
                    font-size: 12px;
                }
                .card a {
                    display: inline-block;
                    padding: .6rem 1.3rem;
                    border-radius: 8px;
                    background: #ef4444;
                    color: #fff;
                    text-decoration: none;
                    font-weight: bold;
                    font-size: 13px;
                    cursor: pointer;
                }
                .card a:hover {
                    background: #dc2626;
                }
            </style>
        </head>

        <body>
            <div class="card">
                <img src="' . $logo . '" alt="FYGroup Logo">

                <div class="icon">
                <i class="fa-solid fa-ban"></i>
                </div>

                <h1>Acceso denegado</h1>
                <p>No tienes permisos para acceder a esta sección.</p>

                <small>
                Usuario: ' . htmlspecialchars($usuario) . '<br>
                Página: ' . htmlspecialchars($pagina) . '
                </small>

                <small>Redirección automática en 5 segundos…</small>

                <a onclick="location.href=\'dashboard.php\'">Ir ahora</a>
            </div>
        </body>
        </html>
    ';

    /* CORREO */
    try {
        // Configurar SMTP con CPanel
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'mail.fygroup.cl';
        $mail->SMTPAuth = true;
        $mail->Username = 'soporte@fygroup.cl';
        $mail->Password = 'Panul2026._';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('soporte@fygroup.cl', 'Soporte FYGroup');

        foreach ($emails as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = '🚫 Acceso Denegado Detectado';

        $mail->Body = '
            <div style="margin:0;padding:40px 0;background:#0f172a;font-family:Arial,sans-serif;color:#fff;">
                <div style="max-width:700px;margin:auto;background:#1e293b;border-radius:16px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,.6);">
                    <!-- HEADER -->
                    <div style="text-align:center;padding:30px 20px;background:#020617;">
                        <img src="' . $logo . '" style="width:180px;margin-bottom:15px;">
                        <h1 style="margin:0;font-size:28px;color:#ef4444;">🚫 ACCESO DENEGADO</h1>
                        <p style="margin:5px 0 0 0;font-size:14px;opacity:.7;">Alerta de seguridad del sistema</p>
                    </div>

                    <!-- ALERTA -->
                    <div style="padding:25px;">
                        <div style="background:#450a0a;padding:15px 20px;border-left:6px solid #ef4444;border-radius:8px;font-size:15px;margin-bottom:25px;">
                            Se detectó un intento de acceso no autorizado.
                        </div>

                        <!-- DATOS -->
                        <table style="width:100%;border-collapse:separate;border-spacing:0;font-size:15px;background:#020617;border-radius:10px;overflow:hidden;">
                            <tr>
                                <td style="padding:12px 15px;font-weight:bold;width:35%;color:#94a3b8;border-bottom:1px solid #1e293b;">👤 Usuario</td>
                                <td style="padding:12px 15px;border-bottom:1px solid #1e293b;">' . htmlspecialchars($usuario) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 15px;font-weight:bold;color:#94a3b8;border-bottom:1px solid #1e293b;">📄 Página</td>
                                <td style="padding:12px 15px;border-bottom:1px solid #1e293b;">' . htmlspecialchars($pagina) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 15px;font-weight:bold;color:#94a3b8;border-bottom:1px solid #1e293b;">🌐 URL</td>
                                <td style="padding:12px 15px;border-bottom:1px solid #1e293b;word-break:break-all;">' . htmlspecialchars($url) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 15px;font-weight:bold;color:#94a3b8;border-bottom:1px solid #1e293b;">🕒 Fecha</td>
                                <td style="padding:12px 15px;border-bottom:1px solid #1e293b;">' . date('d-m-Y H:i:s') . '</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 15px;font-weight:bold;color:#94a3b8;">📡 IP</td>
                                <td style="padding:12px 15px;">' . $_SERVER['REMOTE_ADDR'] . '</td>
                            </tr>
                        </table>

                        <!-- BLOQUE FINAL -->
                        <div style="margin-top:30px;padding:15px;background:#020617;border-radius:8px;font-size:14px;text-align:center;opacity:.8;">
                            Este evento fue registrado automáticamente por el sistema FYGroup.
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div style="text-align:center;padding:20px;font-size:12px;background:#020617;opacity:.6;">
                        © ' . date('Y') . ' FYGroup.
                    </div>
                </div>
            </div>
        ';

        $mail->send();
    } catch (Exception $e) {
        // opcional log
    }

    exit;
}
