<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.user.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email      = $_POST["email"];
  $division   = $_POST["division"];
  $token      = bin2hex(random_bytes(16));
  $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

  $db   = (new Database())->getConnection();
  $user = new User($db);

  /* Buscar el nombre del usuario */
  $query = "SELECT * FROM app_users WHERE email = :email AND division = :division LIMIT 1";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":email", $email);
  $stmt->bindParam(":division", $division);
  $stmt->execute();
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($userData && $user->setResetToken($email, $token, $expiration)) {
    $nombreUsuario = $userData['name'];
    $userDivision  = $userData['division'];

    if ($userDivision == 'ssl') {
      $link = "http://localhost/ssl/mySSL/reset_form.php?token=$token";
    } elseif ($userDivision == 'portal') {
      $link = "http://localhost/ssl/portal/reset_form.php?token=$token";
    }

    $mail = new PHPMailer(true);
    try {
      // Configurar SMTP con Gmail
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'diego.alvaraado@gmail.com'; // <-- tu Gmail
      $mail->Password   = 'ykbu atsv iyba fqib';       // <-- contraseña de aplicación
      $mail->SMTPSecure = 'tls';
      $mail->Port       = 587;

      /* Configurar correo y codificación */
      $mail->CharSet  = 'UTF-8';  // <-- ESTA LÍNEA habilita caracteres especiales
      $mail->Encoding = 'base64'; // Opcional, para asegurar correcta codificación

      // Datos del correo
      $mail->setFrom('diego.alvaraado@gmail.com', 'Soporte SSL');
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
                        <footer style="font-size: 12px; color: #aaa;">
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
