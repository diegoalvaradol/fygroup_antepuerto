<?php
require_once __DIR__ . '/../config/includes.php';

class menu extends iQuery
{
  public function __construct()
  {
    parent::__construct(); // usa Database::get() desde iQuery
  }

  public static function sideBarSSL()
  {
    $cfg  = new cfg();
    $user = new user();

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
        'title' => 'Famesa',
        'icon'  => 'fa-burst',
        'id'    => 'collapseFamesa',
        'items' => [
          ['label' => 'Ingreso Camiones', 'link' => generateMkey('enter_truck_famesa')],
          ['label' => 'Reporte Turno', 'link' => generateMkey('shifts_report_famesa')]
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
        'title' => 'Empresas',
        'icon'  => 'fa-building',
        'id'    => 'collapseEmpresas',
        'items' => [
          ['label' => 'Empresa', 'link' => generateMkey('enter_company')]
        ]
      ],
      [
        'title' => 'Itinerarios',
        'icon'  => 'fa-calendar-days',
        'id'    => 'collapseProgramacion',
        'items' => [
          ['label' => 'Itinerarios FY', 'link' => generateMkey('program_fygroup')],
          ['label' => 'Itinerarios TPC', 'link' => generateMkey('program_tpc')],
          ['label' => 'Itinerarios EPCO', 'link' => generateMkey('program_epco')],
          ['label' => 'Cool Carriers', 'link' => generateMkey('program_cool_carriers')],
          ['label' => 'Global Reefers', 'link' => generateMkey('program_global_reefers')]
        ]
      ],
      [
        'title' => 'Live Position',
        'icon'  => 'fa-satellite',
        'id'    => 'collapseLivePosition',
        'items' => [
          ['label' => 'Live Position', 'link' => generateMkey('marinetraffic_live_map')]
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
          'icon'  => 'fa-file-pdf',
          'id'    => 'collapseReporte',
          'items' => [
            ['label' => 'Reporte de Naves', 'link' => generateMkey('ship_report')],
            ['label' => 'Liquidación de Naves', 'link' => generateMkey('vessel_liquidation')],
            ['label' => 'Reporte de Turnos', 'link' => generateMkey('shifts_report')]
          ]
        ],
        [
          'title' => 'Estadística',
          'icon'  => 'fa-chart-bar',
          'id'    => 'collapseEstaditica',
          'items' => [
            ['label' => 'Estadística Naves', 'link' => generateMkey('stadistics_by_vessel')]
          ]
        ],
        [
          'title' => 'Tarifario',
          'icon'  => 'fa-dollar-sign',
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

    $sidebar = '
      <style>
        #accordionSidebar{
          font-size:13px;
          background:#1e293b;
        }

        #accordionSidebar .sidebar-brand{
          padding:16px 10px;
          border-bottom:1px solid rgba(255,255,255,.05);
        }

        #accordionSidebar .nav-item{
          margin:2px 8px;
        }

        #accordionSidebar .nav-link{
          padding:10px 14px;
          border-radius:10px;
          color:#cbd5e1;
          display:flex;
          align-items:center;
          gap:10px;
          transition:.2s;
        }

        #accordionSidebar .nav-link:hover{
          background:rgba(255,255,255,0.08);
          color:#fff;
        }

        #accordionSidebar .nav-link i{
          width:18px;
          text-align:center;
          font-size:13px;
        }

        #accordionSidebar .nav-link .arrow{
          margin-left:auto;
          font-size:10px;
          transition:.25s;
        }

        #accordionSidebar .nav-link[aria-expanded="true"] .arrow{
          transform:rotate(180deg);
        }

        #accordionSidebar .collapse-inner{
          background:#fff;
          border-radius:10px;
          margin:6px 4px 10px 4px;
          padding:6px 4px;
          box-shadow:0 4px 10px rgba(0,0,0,.08);
        }

        #accordionSidebar .collapse-item{
          display:block;
          padding:8px 12px;
          border-radius:6px;
          font-size:12.5px;
          color:#1e293b;
          transition:.2s;
        }

        #accordionSidebar .collapse-item:hover{
          background:#e2e8f0;
          padding-left:16px;
        }

        #accordionSidebar .sidebar-heading{
          font-size:11px;
          opacity:.6;
          padding:8px 16px 4px;
          letter-spacing:.5px;
        }

        #accordionSidebar .sidebar-footer{
          padding:14px;
          font-size:11px;
          color:#94a3b8;
          border-top:1px solid rgba(255,255,255,.05);
        }

        #accordionSidebar .sidebar-footer i{
          width:14px;
          text-align:center;
          margin-right:5px;
        }

        .sidebar.toggled {
          overflow: visible;
          width: 9.5rem!important;
        }

        /* formularios, card y tablas */
        form,
        form input,
        form select,
        form textarea,
        .card.shadow.mb-4 {
          border-radius: 12px !important;
          overflow: hidden;
        }

        .btn {
          border-radius: 12px !important;
        }

        /* caja principal */
        .select2-container--default .select2-selection--single {
          border-radius: 12px !important;
          height: 38px;
          display: flex;
          align-items: center;
        }

        /* multiple */
        .select2-container--default .select2-selection--multiple {
          border-radius: 12px !important;
        }

        /* dropdown */
        .select2-dropdown {
          border-radius: 12px !important;
          overflow: hidden;
        }

        /* modal */
        .modal-content,
        .modal-header,
        .modal-footer {
          border-radius: 12px !important;
        }

        .input-group > .input-group-prepend > .input-group-text {
          border-radius: 12px 0 0 12px !important;
        }

        .input-group > .form-control {
          border-radius: 0 12px 12px 0 !important;
        }

        h1.h3.text-gray-800 {
          border-bottom: 2px solid #4e73df;
          display: inline-block;
          padding-bottom: 0px;
          margin-bottom: 10px;
        }
      </style>

      <ul class="navbar-nav sidebar accordion d-flex flex-column" id="accordionSidebar">
        <a class="sidebar-brand d-flex justify-content-center align-items-center py-3" href="dashboard.php">
          <img src="../images/ssl-logo-azul.png" style="max-width:180px; width:100%; height:auto;">
        </a>

        <div class="sidebar-heading text-white">
          Sistema Antepuerto
        </div>
    ';

    foreach ($menus as $menu) {
      $sidebar .= '
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#' . $menu['id'] . '" aria-expanded="false">
          <i class="fas ' . $menu['icon'] . '"></i>
          <span>' . $menu['title'] . '</span>
          <i class="fas fa-chevron-down arrow"></i>
        </a>

        <div id="' . $menu['id'] . '" class="collapse" data-parent="#accordionSidebar">
          <div class="collapse-inner">
      ';

      foreach ($menu['items'] as $item) {
        $sidebar .= '
          <a class="collapse-item" href="' . $item['link'] . '">
            ' . $item['label'] . '
          </a>
        ';
      }

      $sidebar .= '
          </div>
        </div>
      </li>';
    }

    $sidebar .= '
      <div class="sidebar-footer mt-auto text-center">
        <div><i class="fas fa-copyright"></i>' . $infoCfg['name'] . '</div>
        <div><i class="fas fa-code-branch"></i>' . $infoCfg['version'] . '</div>
        <div><i class="fas fa-rotate"></i>' . $updateTime->format('d-m-Y H:i') . '</div>

        <div class="mt-2">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
      </div>
    </ul>';

    return $sidebar;
  }

  public static function mainTapBarSSL()
  {
    $user          = new user();
    $arrayDivision = get::getDivisionName();

    $admin      = $user->isAdmin($_SESSION["user"]["run"]);
    $fullName   = htmlspecialchars($_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"]);
    $run        = $_SESSION["user"]["run"];
    $division   = $_SESSION["user"]["division"];
    $avatarName = $user->avatarIniciales($fullName, 35);

    $tapBar = '
      <style>
        #userDropdown .arrow{
          transition:.25s;
          font-size:11px;
          margin-left:6px;
        }

        #userDropdown.show .arrow{
          transform:rotate(180deg);
        }

        .topbar{
          background:#1e293b;
          border-bottom:1px solid rgba(255,255,255,.05);
        }
      </style>

      <nav class="navbar navbar-expand navbar-dark topbar shadow" style="height:50px;margin-bottom:5px;">
        <div class="container-fluid">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none me-3">
            <i class="fas fa-bars"></i>
          </button>

          <div class="mx-auto text-white d-flex align-items-center small">
            <i class="fas fa-clock me-2"></i>
            <div id="relojFecha"></div>
          </div>

          <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle d-flex align-items-center px-2" href="#" id="userDropdown" data-bs-toggle="dropdown">
                ' . $avatarName . '
                &nbsp

                <div class="d-flex flex-column ms-2 me-2">
                  <span class="text-white small fw-semibold">' . $fullName . '</span>
                  <span class="text-white small opacity-75">' . $run . '</span>
                </div>

                <i class="fas fa-chevron-down arrow text-white"></i>
              </a>

              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li class="px-3 py-3 text-center border-bottom">
                  <img src="../favicon/apple-touch-icon.png" class="rounded-circle mb-2" width="60">
                  <br>
                  <small class="text-muted">' . $arrayDivision[$division] . '</small>
                </li>

                <li>
                  <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="fas fa-user me-2 text-primary"></i> Perfil
                  </a>
                </li>';

    if ($admin) {
      $tapBar .= '
              <li>
                <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#goalModal">
                  <i class="fas fa-cogs me-2 text-primary"></i> Ajustar Capacidad
                </a>
              </li>';
    }

    $tapBar .= '
              <li>
                <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#licenseModal">
                  <i class="fas fa-copyright me-2 text-primary"></i> Licencia
                </a>
              </li>

              <li><hr class="dropdown-divider"></li>

              <li>
                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <i class="fas fa-right-from-bracket me-2 text-danger"></i> Cerrar Sesión
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <script>
      document.addEventListener("DOMContentLoaded", function(){
        var dropdown = document.getElementById("userDropdown");

        dropdown.addEventListener("show.bs.dropdown", ()=> dropdown.classList.add("show"));
        dropdown.addEventListener("hide.bs.dropdown", ()=> dropdown.classList.remove("show"));
      });
    </script>';

    return $tapBar;
  }

  public static function sideBarPortal()
  {
    $cfg     = new cfg();
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
    $cfg     = new cfg();
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
