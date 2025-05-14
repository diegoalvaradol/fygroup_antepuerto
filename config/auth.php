<?php
session_start();
$exemptFiles = ['login.php', 'register.php'];

$currentFile = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user']) && !in_array($currentFile, $exemptFiles)) {
  $redirectBack = urlencode($_SERVER['REQUEST_URI']);
  header("Location: login.php?redirect=$redirectBack");
  exit();
}
