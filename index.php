<?php
//header("Location: login.php");
//exit;

require_once __DIR__ . '/config/database.php';

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = rtrim($url, '/');

switch ($url) {
  case '':
  case '/':
    require 'dashboard.php';
    break;

  case '/enter_container_port':
    require 'enter_container_port.php';
    break;

  case '/enter_port':
    require 'enter_port.php';
    break;

  case '/enter_ship_line':
    require 'enter_ship_line.php';
    break;

  case '/enter_ship':
    require 'enter_ship.php';
    break;

  case '/enter_thermo_port':
    require 'enter_thermo_port.php';
    break;

  case '/forgot_password':
    require 'forgot_password.php';
    break;

  case '/login':
    require 'login.php';
    break;

  case '/logout':
    require 'logout.php';
    break;

  case '/maintenance':
    require 'maintenance.php';
    break;

  case '/program_maersk':
    require 'program_maersk.php';
    break;

  case '/program_msc':
    require 'program_msc.php';
    break;

  case '/program_tpc':
    require 'program_tpc.php';
    break;

  case '/register':
    require 'register.php';
    break;

  case '/reset_form':
    require 'reset_form.php';
    break;

  case '/ship_report':
    require 'ship_report.php';
    break;

  default:
    http_response_code(404);
    require __DIR__ . '/mySSL/404.php';
    break;
}
