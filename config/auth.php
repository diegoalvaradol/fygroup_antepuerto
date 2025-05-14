<?php
session_start();

$currentFile = basename($_SERVER['PHP_SELF']);
$exemptFiles = ['login.php', 'register.php'];

if (!isset($_SESSION['user']) && !in_array($currentFile, $exemptFiles)) {
  $redirectBack = urlencode($_SERVER['REQUEST_URI']);
  header("Location: /ssl-chile/mySSL/login.php?redirect=$redirectBack");
  exit();
}
