<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
/**
 * Method generateMkey //Genera un token de seguridad para acceder a un módulo específico.
 *
 * @param  $module [modulo]
 * @param  $area   [area]
 * @return void
 */
function generateMkey($module, $area = 'mySSL')
{
  $secretKey = "SSL-CHILE-DIEGO_2025_0517";
  $time      = time();
  $random    = bin2hex(random_bytes(5));
  $token     = md5($secretKey . $module . $time . $random);

  return './?pag=' . $module . '&area=' . $area . '&mkey=' . $token;
}

/**
 * Method esLocalhost //Verifica si la solicitud proviene de localhost o una IP local.
 *
 * @return void
 */
function esLocalhost()
{
  $whitelist = ['127.0.0.1', '::1', 'localhost'];

  return in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_NAME'], $whitelist);
}

/**
 * Method getCurrentUser //Obtiene el usuario actual de la sesión.
 *
 * @return void
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
 * @param  $date   [fecha   a formatear]
 * @param  $format [formato de fecha, por defecto 'Y-m-d H:i:s']
 * @return void
 */
function formatDate($date, $format = 'Y-m-d H:i:s')
{
  if ($date == '0000-00-00 00:00:00' || $date == '0000-00-00') {
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
 * Method mostrarAccesoDenegado
 *
 * @param  $usuario [usuario que intenta acceder]
 * @param  $pagina  [página  a la que intentó acceder]
 * @return String
 */
function mostrarAccesoDenegado($usuario, $pagina)
{
  // Evita cabeceras duplicadas si ya se envió salida antes
  if (headers_sent() === false) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
  }

  /* Mostrar mensaje de error */
  echo '
  <style>
    .access-denied {
      max-width: 460px;
      margin: 80px auto;
      padding: 24px 30px;
      border: 2px solid #dc3545;
      border-radius: 10px;
      background: #fff5f5;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      color: #b02a37;
      text-align: center;
      box-shadow: 0 0 12px rgba(220,53,69,0.4);
      user-select: none;
    }
    .access-denied svg.icon-large {
      width: 56px;
      height: 56px;
      fill: #dc3545;
      margin-bottom: 14px;
      stroke: #dc3545;
      stroke-width: 3;
    }
    .access-denied h1 {
      font-size: 1.5rem;
      margin: 0 0 14px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      font-weight: 700;
      color: #a8222f;
    }
    .access-denied h1 svg.icon-warning {
      width: 22px;
      height: 22px;
      fill: #dc3545;
      stroke: none;
    }
    .access-denied p {
      font-size: 1.1rem;
      margin: 0 0 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      line-height: 1.3;
    }
    .access-denied p svg.icon-lock {
      width: 20px;
      height: 20px;
      fill: #dc3545;
      flex-shrink: 0;
    }
    .btn-return {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: 6px;
      border: none;
      background-color: #dc3545;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease;
      user-select: none;
      margin: 0 auto;
    }
    .btn-return:hover,
    .btn-return:focus {
      background-color: #a8222f;
      outline: none;
    }
    .btn-return i {
      font-size: 1.1rem;
    }
  </style>

  <div class="access-denied" role="alert" aria-live="assertive" aria-atomic="true" tabindex="0">
    <svg class="icon-large" aria-hidden="true" focusable="false" viewBox="0 0 64 64" role="img" xmlns="http://www.w3.org/2000/svg" aria-label="Error icon">
      <circle cx="32" cy="32" r="30" />
      <line x1="20" y1="20" x2="44" y2="44" stroke-linecap="round"/>
      <line x1="44" y1="20" x2="20" y2="44" stroke-linecap="round"/>
    </svg>

    <h1>
      <svg class="icon-warning" viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 21h22L12 2 1 21z"/>
        <path d="M13 18h-2v-2h2v2zm0-4h-2v-4h2v4z" fill="#fff"/>
      </svg>
      Acceso Denegado
    </h1>

    <p>
      <svg class="icon-lock" viewBox="0 0 24 24" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M6 9V7a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h1zm2-2v2h8V7a4 4 0 0 0-8 0z"/>
      </svg>
      No tienes permisos necesarios para ver esta página.
    </p>

    <button type="button" class="btn-return" onclick="location.href=\'dashboard.php\'" aria-label="Volver al inicio">
      <i class="fas fa-arrow-left"></i> Volver al Inicio
    </button>
  </div>
  ';

  /* Enviar correo */
  try {
    require '../vendor/autoload.php';
    require_once __DIR__ . '/../config/includes.php';
    date_default_timezone_set("America/Santiago");

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'l0011525.ferozo.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'soporte@ssl-lines.com';
    $mail->Password   = 'Ssl*2025sop';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->CharSet  = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('soporte@ssl-lines.com', 'Soporte SSL');
    $mail->addAddress('diego.alvaraado@gmail.com');
    $mail->isHTML(true);
    $mail->Priority = 1;

    $mail->Subject = '⚠️ Intento de acceso denegado en el sistema';
    $mail->Body    = '
      <div style="font-family: Arial; background:#f2f2f2; padding:30px;">
        <div style="background:#fff; padding:24px 32px; border-radius:8px; border-left:6px solid #dc3545; max-width:540px; margin:auto;">
          <h2 style="color:#dc3545;">🚫 Alerta de Acceso Denegado</h2>
          <table style="font-size:14px; color:#555;">
            <tr><td><strong>Usuario:</strong></td><td>' . htmlspecialchars($usuario) . '</td></tr>
            <tr><td><strong>Página:</strong></td><td>' . htmlspecialchars($pagina) . '</td></tr>
            <tr><td><strong>Fecha:</strong></td><td>' . date('d-m-Y H:i:s') . '</td></tr>
            <tr><td><strong>IP:</strong></td><td>' . $_SERVER['REMOTE_ADDR'] . '</td></tr>
          </table>
          <p style="color:#888; font-size:12px;">Mensaje automático del Sistema SSL.</p>
        </div>
      </div>';

    $mail->send();
  } catch (Exception $e) {
    error_log("Correo acceso denegado falló: " . $mail->ErrorInfo);
  }

  exit;

}
