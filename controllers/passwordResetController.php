<?php
require '../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  /* Define si es localhost */
  $whitelist = ['127.0.0.1', '::1', 'localhost'];
  $localHost = false;

  if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_NAME'], $whitelist)) {
    $localHost = true;
  }

  $email      = $_POST["email"];
  $division   = $_POST["division"];
  $token      = bin2hex(random_bytes(16));
  $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

  $user = new user();

  /* Buscar el nombre del usuario */
  $sql  = "SELECT * FROM app_users WHERE email = :email AND division = :division LIMIT 1";
  $list = $user->getFirstMember($sql, ['email' => $email, 'division' => $division]);

  if ($list && $user->setResetToken($email, $token, $expiration)) {
    $nombreUsuario = $list['name'];
    $userDivision  = $list['division'];
    $url           = generateMkey('reset_form') . '&token=' . $token;

    if ($userDivision == 'ssl') {
      $link = $localHost ? "http://localhost/ssl-chile/mySSL/" . $url : "https://myssl.ssl-lines.com/mySSL/" . $url;
    } elseif ($userDivision == 'terminal' || $userDivision == 'exporter') {
      $link = $localHost ? "http://localhost/ssl-chile/myPortal/" . $url : "https://myssl.ssl-lines.com/myPortal/" . $url;
    }

    $mail = new PHPMailer(true);
    try {
      // Configurar SMTP con Ferozo
      $mail->isSMTP();
      $mail->Host       = 'l0011525.ferozo.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'soporte@ssl-lines.com';
      $mail->Password   = 'Ssl*2025sop';
      $mail->SMTPSecure = 'ssl'; // usar 'ssl'
      $mail->Port       = 465;

      // Configurar codificación
      $mail->CharSet  = 'UTF-8';
      $mail->Encoding = 'base64';

      // Datos del correo
      $mail->setFrom('soporte@ssl-lines.com', 'Soporte SSL');
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Priority = 1;
      $mail->Subject  = 'Reestablecer tu contraseña en Sistema Integral SSL.';
      $mail->Body     = '
                <div style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 30px; text-align: center;">
                    <div style="background-color: #fff; padding: 30px; border-radius: 8px; display: inline-block; max-width: 500px;">
                        <h2 style="color: #4CAF50;">Hola ' . htmlspecialchars($nombreUsuario) . ' 👋</h2>
                        <p style="color: #555;">Hemos recibido una solicitud para reestablecer tu contraseña.</p>
                        <p style="color: #555;">Haz clic en el siguiente botón para continuar:</p>
                        <p style="margin: 30px 0;">
                            <a href="' . $link . '" style="background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">Reestablecer Contraseña</a>
                        </p>
                        <p style="color: #888;">Si no solicitaste este cambio, puedes ignorar este correo.</p>
                        <hr style="margin: 30px 20px;">
                        <footer style="font-size: 12px; color: #aaa; text-align: center;">
                            © ' . date('Y') . ' Sistema Integral SSL. Todos los derechos reservados.<br>
                            Este es un correo automático, por favor no respondas.
                        </footer>
                    </div>
                </div>
      ';

      $mail->send();

      echo "OK";
    } catch (Exception $e) {
      echo "NOOK2";
    }
  } else {
    echo "NOOK";
  }
}
