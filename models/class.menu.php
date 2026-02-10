<?php
require_once __DIR__ . '/../config/includes.php';

class menu
{
  public static function sideBarSSL()
  {
    $db   = (new Database())->getConnection();
    $cfg  = new cfg($db);
    $user = new user($db);

    $infoCfg    = json_decode($cfg->getInfo(1), true);
    $updateTime = new DateTime($infoCfg['update_date']);
    $admin      = $user->isAdmin($_SESSION["user"]["run"]);

    /* Definición de menús */
    $menus = [
      [
        'title' => 'Operaciones',
        'icon'  => 'fa-truck',
        'id'    => 'collapseOperaciones',
        'items' => [
          ['label' => 'Ingreso Contenedores', 'link' => generateMkey('enter_container_port')],
          ['label' => 'Ingreso Termos', 'link' => generateMkey('enter_thermo_port')],
          ['label' => 'Carga Internacional', 'link' => generateMkey('enter_container_international')],
          ['label' => 'Seguimiento', 'link' => generateMkey('tracking')],
          ['label' => 'Roleo de Carga', 'link' => generateMkey('vessel_transfer')]
        ]
      ],
      [
        'title' => 'Puerto',
        'icon'  => 'fa-anchor',
        'id'    => 'collapsePuerto',
        'items' => [
          ['label' => 'Naves', 'link' => generateMkey('enter_ship')],
          ['label' => 'Lineas Navieras', 'link' => generateMkey('enter_ship_line')],
          ['label' => 'Puertos', 'link' => generateMkey('enter_port')]
        ]
      ],
      [
        'title' => 'Itinerarios',
        'icon'  => 'fa-calendar-days',
        'id'    => 'collapseProgramacion',
        'items' => [
          ['label' => 'Planificación Naviera FY', 'link' => generateMkey('program_fygroup')],
          ['label' => 'Planificación Naviera TPC', 'link' => generateMkey('program_tpc')],
          ['label' => 'Planificación Naviera EPCO', 'link' => generateMkey('program_epco')],
          ['label' => 'Itinerarios Cool Carriers', 'link' => generateMkey('program_cool_carriers')],
          ['label' => 'Itinerarios Global Reefers', 'link' => generateMkey('program_global_reefers')]
        ]
      ]
    ];

    if ($admin) {
      $menus = array_merge($menus, [
        [
          'title' => 'Maersk',
          'icon'  => 'fa-ship',
          'id'    => 'collapseMaersk',
          'items' => [
            ['label' => 'Punto a Punto', 'link' => generateMkey('point_schedule_maersk')],
            ['label' => 'Puerto', 'link' => generateMkey('port_schedule_maersk')],
            ['label' => 'Nave', 'link' => generateMkey('vessel_schedule_maersk')],
            ['label' => 'Programación', 'link' => generateMkey('program_maersk')],
            ['label' => 'Seguimiento de Carga', 'link' => generateMkey('tracking_schedule_maersk')]
          ]
        ],
        [
          'title' => 'MSC',
          'icon'  => 'fa-ship',
          'id'    => 'collapseMedlog',
          'items' => [
            ['label' => 'Stacking MSC', 'link' => generateMkey('program_msc')],
            ['label' => 'Importación MSC', 'link' => generateMkey('program_import_msc')],
            ['label' => 'EIR Medlog', 'link' => generateMkey('eir_msc')]
          ]
        ],
        [
          'title' => 'Reportes',
          'icon'  => 'fa-book',
          'id'    => 'collapseReporte',
          'items' => [
            ['label' => 'Reporte de Naves', 'link' => generateMkey('ship_report')],
            ['label' => 'Liquidación de Naves', 'link' => generateMkey('vessel_liquidation')]
          ]
        ],
        [
          'title' => 'Estadística',
          'icon'  => 'fa-chart-simple',
          'id'    => 'collapseEstaditica',
          'items' => [
            ['label' => 'Estadística Naves', 'link' => generateMkey('stadistics_by_vessel')]
          ]
        ],
        [
          'title' => 'Tarifario',
          'icon'  => 'fa-sack-dollar',
          'id'    => 'collapsePrecio',
          'items' => [
            ['label' => 'Lista de Tarifas', 'link' => generateMkey('list_price_indicators')]
          ]
        ],
        [
          'title' => 'Servidor',
          'icon'  => 'fa-server',
          'id'    => 'collapseServer',
          'items' => [
            ['label' => 'SQL Administrador', 'link' => generateMkey('sql_console')],
            ['label' => 'Respaldo de Archivos', 'link' => generateMkey('files_backup')],
            ['label' => 'Carga Planificación', 'link' => generateMkey('load_schedule')]
          ]
        ]
      ]);
    }

    $sidebar = '<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#1e293b;">';
    $sidebar .= '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">';
    $sidebar .= '<img src="../images/ssl-logo-azul.png" alt="Logo SSL" style="width:100%;">';
    $sidebar .= '</a>';
    $sidebar .= '<div class="sidebar-heading text-white mt-3">Sistema Antepuerto</div>';

    foreach ($menus as $menu) {
      /*$sidebar .= '<div class="sidebar-heading text-white mt-3">' . htmlspecialchars($menu['title']) . '</div>';*/
      $sidebar .= '<li class="nav-item">';
      $sidebar .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#' . $menu['id'] . '" aria-expanded="false" aria-controls="' . $menu['id'] . '">';
      $sidebar .= '<i class="fas fa-fw ' . htmlspecialchars($menu['icon']) . '"></i>';
      $sidebar .= '<span>' . htmlspecialchars($menu['title']) . '</span>';
      $sidebar .= '</a>';

      $sidebar .= '<div id="' . $menu['id'] . '" class="collapse" aria-labelledby="heading" data-parent="#accordionSidebar">';
      $sidebar .= '<div class="bg-white py-2 collapse-inner rounded">';
      $sidebar .= '<h6 class="collapse-header">Items:</h6>';

      foreach ($menu['items'] as $item) {
        $sidebar .= '<a class="collapse-item" href="' . htmlspecialchars($item['link']) . '">' . htmlspecialchars($item['label']) . '</a>';
      }

      $sidebar .= '</div>';
      $sidebar .= '</div>';
      $sidebar .= '</li>';
    }

    $sidebar .= '<div class="d-flex flex-column h-100">';
    $sidebar .= '<div class="text-center d-none d-md-inline text-white small">';
    $sidebar .= '<hr class="sidebar-divider">';
    $sidebar .= '<div><i class="fas fa-copyright"></i>  ' . htmlspecialchars($infoCfg['name']) . '</div>';
    $sidebar .= '<div><i class="fas fa-file-arrow-up"></i> ' . htmlspecialchars($infoCfg['version']) . '</div>';
    $sidebar .= '<div><i class="fas fa-cloud-arrow-up"></i> ' . $updateTime->format('d-m-Y H:i') . '</div>';
    $sidebar .= '</div>';
    $sidebar .= '</br>';
    $sidebar .= '<div class="text-center d-none d-md-inline">';
    $sidebar .= '<button class="rounded-circle border-0" id="sidebarToggle"></button>';
    $sidebar .= '</div>';
    $sidebar .= '</div>';

    $sidebar .= '</ul>';

    return $sidebar;
  }

  public static function mainTapBarSSL()
  {
    $db       = (new Database())->getConnection();
    $user     = new user($db);
    $admin    = $user->isAdmin($_SESSION["user"]["run"]);
    $userName = htmlspecialchars($_SESSION["user"]["name"]);

    $tapBar = '
    <nav class="navbar navbar-expand navbar-dark topbar mb-4 static-top shadow" style="background-color: #1e293b;">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="navbar-nav d-flex justify-content-end align-items-center w-100">
        <li class="nav-item d-flex align-items-center text-white me-3">
          <i class="fas fa-clock me-1"></i>
          <span id="relojFecha"></span>
        </li>

        <div class="topbar-divider d-none d-sm-block me-3"></div>

        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="d-none d-lg-inline text-white fw-semibold me-2">Bienvenido, ' . $userName . '!</span>
            <img class="img-profile rounded-circle" src="../images/undraw_profile.svg" alt="Perfil usuario" width="36" height="36">
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-user fa-sm fa-fw me-2"></i> Perfil
              </a>
            </li>';

    if ($admin) {
      $tapBar .= '
            <li>
              <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#goalModal">
                <i class="fas fa-cogs fa-sm fa-fw me-2"></i> Ajustar Capacidad
              </a>
            </li>';
    }

    $tapBar .= '
            <li>
              <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#licenceModal">
                <i class="fas fa-copyright fa-sm fa-fw me-2"></i> Licencia
              </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
              <a class="dropdown-item text-danger" href="logout.php" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-right-from-bracket me-2"></i> Cerrar Sesión
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>';

    return $tapBar;
  }

  public static function secondTapBarSSL()
  {
    $userName = htmlspecialchars($_SESSION["user"]["name"]);

    $tapBar = '
    <nav class="navbar navbar-expand navbar-dark topbar mb-4 static-top shadow" style="background-color: #1e293b;">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="navbar-nav d-flex justify-content-end align-items-center w-100">
        <li class="nav-item d-flex align-items-center text-white me-3">
          <i class="fas fa-clock me-1"></i>
          <span id="relojFecha"></span>
        </li>

        <div class="topbar-divider d-none d-sm-block me-3"></div>

        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="d-none d-lg-inline text-white fw-semibold me-2">Bienvenido, ' . $userName . '!</span>
            <img class="img-profile rounded-circle" src="../images/undraw_profile.svg" alt="Perfil usuario" width="36" height="36">
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#infoModal">
                <i class="fas fa-circle-info fa-sm fa-fw me-2"></i>Acerca del Sistema
              </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
              <a class="dropdown-item text-danger" href="logout.php" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-right-from-bracket me-2"></i> Cerrar Sesión
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>';

    return $tapBar;
  }

  public static function sideBarPortal()
  {
    $db      = (new Database())->getConnection();
    $cfg     = new cfg($db);
    $infoCfg = json_decode($cfg->getInfo(1), true);

    $sideBarPortal = '<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#1e293b;">';
    $sideBarPortal .= '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">';
    $sideBarPortal .= '<img src="../images/ssl-logo-azul.png" style="width:100%;">';
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
    $sideBarPortal .= '<div class="text-center d-none d-md-inline mt-auto" style="color: white;">';
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
    $tapBarPortal = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#1e293b;">';
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
    $tapBarPortal .= '<img class="img-profile rounded-circle" src="../images/undraw_profile.svg">';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBarPortal .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBarPortal .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-divider"></div>';
    $tapBarPortal .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #ef4444;">';
    $tapBarPortal .= '<i class="fa-solid fa-right-from-bracket" style="color: #ef4444;"></i> Cerrar Sesión';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '</div>';
    $tapBarPortal .= '</li>';
    $tapBarPortal .= '</ul>';
    $tapBarPortal .= '</nav>';

    return $tapBarPortal;
  }

  public static function secondTapBarPortal()
  {
    $tapBarPortal = '<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#1e293b;">';
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
    $tapBarPortal .= '<img class="img-profile rounded-circle" src="../images/undraw_profile.svg">';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
    $tapBarPortal .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">';
    $tapBarPortal .= '<i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema';
    $tapBarPortal .= '</a>';
    $tapBarPortal .= '<div class="dropdown-divider"></div>';
    $tapBarPortal .= '<a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #ef4444;">';
    $tapBarPortal .= '<i class="fa-solid fa-right-from-bracket" style="color: #ef4444;"></i> Cerrar Sesión';
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

    $footer = '
    <footer class="footer bg-light text-center text-muted" style="
        bottom: 0;
        width: -webkit-fill-available;
        height: 45px;
        line-height: 45px;
        font-size: 14px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
        ">
        <div class="container">
            <span><i class="fas fa-copyright"></i> ' . date('Y') . ' ' . htmlspecialchars($infoCfg['mark']) . '. Todos los derechos reservados.</span>
        </div>
    </footer>';

    return $footer;
  }

}
