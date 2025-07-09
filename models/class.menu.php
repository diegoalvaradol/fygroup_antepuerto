<?php
require_once __DIR__ . '/../config/includes.php';

class menu
{
  public static function sideBarSSL()
  {
    $db   = (new Database())->getConnection();
    $cfg  = new cfg($db);
    $user = new user($db);

    $infoCfg = json_decode($cfg->getInfo(1), true);
    $admin   = $user->isAdmin($_SESSION["user"]["run"]);

    $sideBar = '<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#293c74;">';
    $sideBar .= '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">';
    $sideBar .= '<img src="../img/ssl-logo-azul.png" style="width:100%;">';

    /* Menú de Antepuerto */
    $sideBar .= '</a>';
    $sideBar .= '<div class="sidebar-heading">Sistema Antepuerto</div>';
    $sideBar .= '<li class="nav-item">';
    $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAntepuerto" aria-expanded="true" aria-controls="collapseAntepuerto">';
    $sideBar .= '<i class="fas fa-fw fa-truck"></i>';
    $sideBar .= '<span>Antepuerto</span>';
    $sideBar .= '</a>';
    $sideBar .= '<div id="collapseAntepuerto" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">';
    $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
    $sideBar .= '<h6 class="collapse-header">Items:</h6>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_container_port') . '>Ingreso Contenedores</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_thermo_port') . '>Ingreso Termos</a>';
    $sideBar .= '</div>';
    $sideBar .= '</div>';
    $sideBar .= '</li>';

    /* Menú Internacional */
    $sideBar .= '<li class="nav-item">';
    $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInternational" aria-expanded="true" aria-controls="collapseInternational">';
    $sideBar .= '<i class="fa fa-fw fa-earth-americas"></i>';
    $sideBar .= '<span>Internacional</span>';
    $sideBar .= '</a>';
    $sideBar .= '<div id="collapseInternational" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">';
    $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
    $sideBar .= '<h6 class="collapse-header">Items:</h6>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_container_international') . '>Carga Internacional</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('tracking') . '>Seguimiento</a>';
    $sideBar .= '</div>';
    $sideBar .= '</div>';
    $sideBar .= '</li>';

    /*cMenú de Puerto */
    $sideBar .= '<li class="nav-item">';
    $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePuerto" aria-expanded="true" aria-controls="collapsePuerto">';
    $sideBar .= '<i class="fas fa-fw fa-ship"></i>';
    $sideBar .= '<span>Puerto</span>';
    $sideBar .= '</a>';
    $sideBar .= '<div id="collapsePuerto" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
    $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
    $sideBar .= '<h6 class="collapse-header">Items:</h6>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_ship') . '>Naves</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_ship_line') . '>Lineas Navieras</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('enter_port') . '>Puertos</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('vessel_transfer') . '>Roleo de Carga</a>';
    $sideBar .= '</div>';
    $sideBar .= '</div>';
    $sideBar .= '</li>';

    /* Menú de Planificación */
    $sideBar .= '<li class="nav-item">';
    $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProgramacion" aria-expanded="true" aria-controls="collapseProgramacion">';
    $sideBar .= '<i class="fas fa-fw fa-file-pdf"></i>';
    $sideBar .= '<span>Planificación</span>';
    $sideBar .= '</a>';
    $sideBar .= '<div id="collapseProgramacion" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
    $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
    $sideBar .= '<h6 class="collapse-header">Items:</h6>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('program_tpc') . '>Planificación Naviera TPC</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('program_cool_carriers') . '>Itinerarios Cool Carriers</a>';
    $sideBar .= '<a class="collapse-item" href=' . generateMkey('program_global_reefers') . '>Itinerarios Global Reefers</a>';

    if ($admin) {
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('program_msc') . '>Programación MSC</a>';
    }

    $sideBar .= '</div>';
    $sideBar .= '</div>';
    $sideBar .= '</li>';

    /* Menú de Itinerarios Maersk */
    if ($admin) {
      $sideBar .= '<li class="nav-item">';
      $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collpaseMaersk" aria-expanded="true" aria-controls="collpaseMaersk">';
      $sideBar .= '<i class="fas fa-fw fa-calendar-days"></i>';
      $sideBar .= '<span>Itinerario Maersk</span>';
      $sideBar .= '</a>';
      $sideBar .= '<div id="collpaseMaersk" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
      $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
      $sideBar .= '<h6 class="collapse-header">Items:</h6>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('point_schedule_maersk') . '>Punto a Punto</a>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('port_schedule_maersk') . '>Puerto</a>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('vessel_schedule_maersk') . '>Nave</a>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('program_maersk') . '>Programación</a>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('tracking_schedule_maersk') . '>Seguimiento de Carga</a>';
      $sideBar .= '</div>';
      $sideBar .= '</div>';
      $sideBar .= '</li>';

      /* Menú de Reportes */
      $sideBar .= '<li class="nav-item">';
      $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReporte" aria-expanded="true" aria-controls="collapseReporte">';
      $sideBar .= '<i class="fas fa-fw fa-book"></i>';
      $sideBar .= '<span>Reportes</span>';
      $sideBar .= '</a>';
      $sideBar .= '<div id="collapseReporte" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
      $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
      $sideBar .= '<h6 class="collapse-header">Items:</h6>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('ship_report') . '>Reporte de Naves</a>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('vessel_liquidation') . '>Liquidación de Naves</a>';
      $sideBar .= '</div>';
      $sideBar .= '</div>';
      $sideBar .= '</li>';

      /* Menú de Indicadores Financieros */
      $sideBar .= '<li class="nav-item">';
      $sideBar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePrecio" aria-expanded="true" aria-controls="collapsePrecio">';
      $sideBar .= '<i class="fas fa-fw fa-sack-dollar"></i>';
      $sideBar .= '<span>Precios</span>';
      $sideBar .= '</a>';
      $sideBar .= '<div id="collapsePrecio" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
      $sideBar .= '<div class="bg-white py-2 collapse-inner rounded">';
      $sideBar .= '<h6 class="collapse-header">Items:</h6>';
      $sideBar .= '<a class="collapse-item" href=' . generateMkey('list_price_indicators') . '>Lista de Precios</a>';
      $sideBar .= '</div>';
      $sideBar .= '</div>';
      $sideBar .= '</li>';
    }

    $sideBar .= '<hr class="sidebar-divider d-none d-md-block">';
    $sideBar .= '<div class="text-center d-none d-md-inline">';
    $sideBar .= '<button class="rounded-circle border-0" id="sidebarToggle"></button>';
    $sideBar .= '</div>';
    $sideBar .= '<div class="d-flex flex-column h-100">';
    $sideBar .= '<div class="text-center d-none d-md-inline mt-auto">';
    $sideBar .= '<hr class="sidebar-divider">';
    $sideBar .= '<small>' . $infoCfg['name'] . '</small>';
    $sideBar .= '<br>';
    $sideBar .= '<small><b>Versión: </b>' . $infoCfg['version'] . '</small>';
    $sideBar .= '</div>';
    $sideBar .= '</div>';
    $sideBar .= '</ul>';

    return $sideBar;
  }

  public static function mainTapBarSSL()
  {
    $db    = (new Database())->getConnection();
    $user  = new user($db);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    $tapBar = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#293c74;">';
    $tapBar .= '<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">';
    $tapBar .= '<i class="fa fa-bars"></i>';
    $tapBar .= '</button>';
    $tapBar .= '<ul class="navbar-nav ml-auto">';
    $tapBar .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBar .= '<label class="ml-auto" id="relojFecha" style="color:white; align-content:center;"></label>';
    $tapBar .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBar .= '<li class="nav-item dropdown no-arrow">';
    $tapBar .= '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    $tapBar .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION["user"]["name"] . '!</span>';
    $tapBar .= '<img class="img-profile rounded-circle" src="../img/undraw_profile.svg">';
    $tapBar .= '</a>';
    $tapBar .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBar .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#userModal" style="color: #0483cd;">';
    $tapBar .= '<i class="fas fa-user fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Perfil';
    $tapBar .= '</a>';

    if ($admin) {
      $tapBar .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#goalModal" style="color: #0483cd;">';
      $tapBar .= '<i class="fas fa-cogs fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Ajustar Capacidad';
      $tapBar .= '</a>';
    }

    $tapBar .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBar .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBar .= '</a>';
    $tapBar .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#licenceModal" style="color: #0483cd;">';
    $tapBar .= '<i class="fas fa-copyright fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Licencia';
    $tapBar .= '</a>';
    $tapBar .= '<div class="dropdown-divider"></div>';
    $tapBar .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #cd1804;">';
    $tapBar .= '<i class="fa-solid fa-right-from-bracket" style="color: #cd1804;"></i> Cerrar Sesión';
    $tapBar .= '</a>';
    $tapBar .= '</div>';
    $tapBar .= '</li>';
    $tapBar .= '</ul>';
    $tapBar .= '</nav>';

    return $tapBar;
  }

  public static function secondTapBarSSL()
  {
    $tapBar = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#293c74;">';
    $tapBar .= '<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">';
    $tapBar .= '<i class="fa fa-bars"></i>';
    $tapBar .= '</button>';
    $tapBar .= '<ul class="navbar-nav ml-auto">';
    $tapBar .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBar .= '<label class="ml-auto" id="relojFecha" style="color:white; align-content:center;"></label>';
    $tapBar .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBar .= '<li class="nav-item dropdown no-arrow">';
    $tapBar .= '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    $tapBar .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION["user"]["name"] . '!</span>';
    $tapBar .= '<img class="img-profile rounded-circle" src="../img/undraw_profile.svg">';
    $tapBar .= '</a>';
    $tapBar .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBar .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBar .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBar .= '</a>';
    $tapBar .= '<div class="dropdown-divider"></div>';
    $tapBar .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #cd1804;">';
    $tapBar .= '<i class="fa-solid fa-right-from-bracket" style="color: #cd1804;"></i> Cerrar Sesión';
    $tapBar .= '</a>';
    $tapBar .= '</div>';
    $tapBar .= '</li>';
    $tapBar .= '</ul>';
    $tapBar .= '</nav>';

    return $tapBar;
  }

  public static function sideBarPortal()
  {
    $db      = (new Database())->getConnection();
    $cfg     = new cfg($db);
    $infoCfg = json_decode($cfg->getInfo(1), true);

    $sideBarPortal = '<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#293c74;">';
    $sideBarPortal .= '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">';
    $sideBarPortal .= '<img src="../img/ssl-logo-azul.png" style="width:100%;">';
    $sideBarPortal .= '</a>';
    $sideBarPortal .= '<div class="sidebar-heading">Sistema Antepuerto</div>';
    $sideBarPortal .= '<div class="sidebar-heading">(Portal Cliente)</div>';
    $sideBarPortal .= '<li class="nav-item">';
    $sideBarPortal .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAntepuerto" aria-expanded="true" aria-controls="collapseAntepuerto">';
    $sideBarPortal .= '<i class="fas fa-fw fa-truck"></i>';
    $sideBarPortal .= '<span>Antepuerto</span>';
    $sideBarPortal .= '</a>';
    $sideBarPortal .= '<div id="collapseAntepuerto" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">';
    $sideBarPortal .= '<div class="bg-white py-2 collapse-inner rounded">';
    $sideBarPortal .= '<h6 class="collapse-header">Items:</h6>';
    $sideBarPortal .= '<a class="collapse-item" href=' . generateMkey('enter_container_port', 'myPortal') . '>Ingreso Contenedores</a>';
    $sideBarPortal .= '<a class="collapse-item" href=' . generateMkey('enter_thermo_port', 'myPortal') . '>Ingreso Termos</a>';
    $sideBarPortal .= '</div>';
    $sideBarPortal .= '</div>';
    $sideBarPortal .= '</li>';
    $sideBarPortal .= '<hr class="sidebar-divider d-none d-md-block">';
    $sideBarPortal .= '<div class="text-center d-none d-md-inline">';
    $sideBarPortal .= '<button class="rounded-circle border-0" id="sidebarToggle"></button>';
    $sideBarPortal .= '</div>';
    $sideBarPortal .= '<div class="d-flex flex-column h-100">';
    $sideBarPortal .= '<div class="text-center d-none d-md-inline mt-auto">';
    $sideBarPortal .= '<hr class="sidebar-divider">';
    $sideBarPortal .= '<small>' . $infoCfg['name'] . '</small>';
    $sideBarPortal .= '<br>';
    $sideBarPortal .= '<small><b>Versión: </b>' . $infoCfg['version'] . '</small>';
    $sideBarPortal .= '</div>';
    $sideBarPortal .= '</div>';
    $sideBarPortal .= '</ul>';

    return $sideBarPortal;
  }

  public static function mainTapBarPortal()
  {
    $tapBarPortal = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#293c74;">';
    $tapBarPortal .= '<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">';
    $tapBarPortal .= '<i class="fa fa-bars"></i>';
    $tapBarPortal .= '</button>';
    $tapBarPortal .= '<ul class="navbar-nav ml-auto">';
    $tapBarPortal .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBarPortal .= '<label class="ml-auto" id="relojFecha" style="color:white; align-content:center;"></label>';
    $tapBarPortal .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBarPortal .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBarPortal .= '<label class="ml-auto" id="countDownSession" style="color:white; align-content:center;"></label>';
    $tapBarPortal .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBarPortal .= '<li class="nav-item dropdown no-arrow">';
    $tapBarPortal .= '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    $tapBarPortal .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION["user"]["name"] . '!</span>';
    $tapBarPortal .= '<img class="img-profile rounded-circle" src="../img/undraw_profile.svg">';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBarPortal .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBarPortal .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-divider"></div>';
    $tapBarPortal .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #cd1804;">';
    $tapBarPortal .= '<i class="fa-solid fa-right-from-bracket" style="color: #cd1804;"></i> Cerrar Sesión';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '</div>';
    $tapBarPortal .= '</li>';
    $tapBarPortal .= '</ul>';
    $tapBarPortal .= '</nav>';

    return $tapBarPortal;
  }

  public static function secondTapBarPortal()
  {
    $tapBarPortal = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#293c74;">';
    $tapBarPortal .= '<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">';
    $tapBarPortal .= '<i class="fa fa-bars"></i>';
    $tapBarPortal .= '</button>';
    $tapBarPortal .= '<ul class="navbar-nav ml-auto">';
    $tapBarPortal .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBarPortal .= '<label class="ml-auto" id="relojFecha" style="color:white; align-content:center;"></label>';
    $tapBarPortal .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBarPortal .= '<label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>';
    $tapBarPortal .= '<label class="ml-auto" id="countDownSession" style="color:white; align-content:center;"></label>';
    $tapBarPortal .= '<div class="topbar-divider d-none d-sm-block"></div>';
    $tapBarPortal .= '<li class="nav-item dropdown no-arrow">';
    $tapBarPortal .= '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    $tapBarPortal .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION["user"]["name"] . '!</span>';
    $tapBarPortal .= '<img class="img-profile rounded-circle" src="../img/undraw_profile.svg">';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBarPortal .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBarPortal .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-divider"></div>';
    $tapBarPortal .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #cd1804;">';
    $tapBarPortal .= '<i class="fa-solid fa-right-from-bracket" style="color: #cd1804;"></i> Cerrar Sesión';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '</div>';
    $tapBarPortal .= '</li>';
    $tapBarPortal .= '</ul>';
    $tapBarPortal .= '</nav>';

    return $tapBarPortal;
  }

  public static function footerSSL()
  {
    $db      = (new Database())->getConnection();
    $cfg     = new cfg($db);
    $infoCfg = json_decode($cfg->getInfo(1), true);

    $footer = '<footer class="sticky-footer bg-white">';
    $footer .= '<div class="container my-auto">';
    $footer .= '<div class="copyright text-center my-auto">';
    $footer .= '<span>Copyright &copy; ' . $infoCfg['mark'] . ' 2025</span>';
    $footer .= '</div>';
    $footer .= '</div>';
    $footer .= '</footer>';

    return $footer;
  }

}
