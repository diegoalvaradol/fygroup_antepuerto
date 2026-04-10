<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

class menu extends iQuery
{
    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public static function sideBarSSL()
    {
        $cfg = new cfg();
        $user = new user();

        $infoCfg = json_decode($cfg->getInfo(1), true);
        $updateTime = new DateTime($infoCfg['update_date']);
        $admin = $user->isAdmin($_SESSION['user']['run']);

        /* Definición de menús */
        $menus = [
          [
            'title' => 'Operaciones',
            'icon' => 'fa-truck',
            'id' => 'collapseOperaciones',
            'items' => [
              ['label' => 'Ingreso Contenedores', 'link' => generateMkey('enter_container_port')],
              ['label' => 'Ingreso Termos', 'link' => generateMkey('enter_thermo_port')],
              ['label' => 'Carga Internacional', 'link' => generateMkey('enter_container_international')],
              ['label' => 'Seguimiento', 'link' => generateMkey('tracking')],
              ['label' => 'Roleo de Carga', 'link' => generateMkey('vessel_transfer')],
            ],
          ],
          [
            'title' => 'Puerto',
            'icon' => 'fa-anchor',
            'id' => 'collapsePuerto',
            'items' => [
              ['label' => 'Naves', 'link' => generateMkey('enter_ship')],
              ['label' => 'Lineas Navieras', 'link' => generateMkey('enter_ship_line')],
              ['label' => 'Puertos', 'link' => generateMkey('enter_port')],
            ],
          ],
          [
            'title' => 'Empresas',
            'icon' => 'fa-building',
            'id' => 'collapseEmpresas',
            'items' => [
              ['label' => 'Empresa', 'link' => generateMkey('enter_company')],
            ],
          ],
          [
            'title' => 'Itinerarios',
            'icon' => 'fa-calendar-days',
            'id' => 'collapseProgramacion',
            'items' => [
              ['label' => 'Itinerarios FY', 'link' => generateMkey('program_fygroup')],
              ['label' => 'Itinerarios TPC', 'link' => generateMkey('program_tpc')],
              ['label' => 'Itinerarios EPCO', 'link' => generateMkey('program_epco')],
              ['label' => 'Cool Carriers', 'link' => generateMkey('program_cool_carriers')],
              ['label' => 'Global Reefers', 'link' => generateMkey('program_global_reefers')],
            ],
          ],
          [
            'title' => 'Live Position',
            'icon' => 'fa-satellite',
            'id' => 'collapseLivePosition',
            'items' => [
              ['label' => 'Live Position', 'link' => generateMkey('marinetraffic_live_map')],
            ],
          ],
        ];

        if ($admin) {
            $menus = array_merge($menus, [
              [
                'title' => 'Maersk',
                'icon' => 'fa-ship',
                'id' => 'collapseMaersk',
                'items' => [
                  ['label' => 'Punto a Punto', 'link' => generateMkey('point_schedule_maersk')],
                  ['label' => 'Puerto', 'link' => generateMkey('port_schedule_maersk')],
                  ['label' => 'Nave', 'link' => generateMkey('vessel_schedule_maersk')],
                  ['label' => 'Programación', 'link' => generateMkey('program_maersk')],
                  ['label' => 'Seguimiento de Carga', 'link' => generateMkey('tracking_schedule_maersk')],
                ],
              ],
              [
                'title' => 'MSC',
                'icon' => 'fa-ship',
                'id' => 'collapseMedlog',
                'items' => [
                  ['label' => 'Stacking MSC', 'link' => generateMkey('program_msc')],
                  ['label' => 'Importación MSC', 'link' => generateMkey('program_import_msc')],
                  ['label' => 'EIR Medlog', 'link' => generateMkey('eir_msc')],
                ],
              ],

              [
                'title' => 'Reportes',
                'icon' => 'fa-file-pdf',
                'id' => 'collapseReporte',
                'items' => [
                  ['label' => 'Reporte de Naves', 'link' => generateMkey('ship_report')],
                  ['label' => 'Liquidación de Naves', 'link' => generateMkey('vessel_liquidation')],
                  ['label' => 'Reporte de Turnos', 'link' => generateMkey('shifts_report')],
                ],
              ],
              [
                'title' => 'Estadística',
                'icon' => 'fa-chart-bar',
                'id' => 'collapseEstaditica',
                'items' => [
                  ['label' => 'Estadística Naves', 'link' => generateMkey('stadistics_by_vessel')],
                ],
              ],
              [
                'title' => 'Tarifario',
                'icon' => 'fa-dollar-sign',
                'id' => 'collapsePrecio',
                'items' => [
                  ['label' => 'Lista de Tarifas', 'link' => generateMkey('list_price_indicators')],
                ],
              ],
              [
                'title' => 'Servidor',
                'icon' => 'fa-server',
                'id' => 'collapseServer',
                'items' => [
                  ['label' => 'SQL Administrador', 'link' => generateMkey('sql_console')],
                  ['label' => 'Respaldo de Archivos', 'link' => generateMkey('files_backup')],
                  ['label' => 'Carga Planificación', 'link' => generateMkey('load_schedule')],
                ],
              ],
            ]);
        }

        $sidebar = '
            <style>
                /* Formularios, card y tablas */
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

                .mb-1, .my-1 {
                    margin-bottom: .55rem !important;
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
                    border-bottom: 2px solid #3787ba;
                    display: inline-block;
                    padding-bottom: 0px;
                    margin-bottom: 10px;
                }

                /* Tarjetas */
                .custom-alert-info {
                    background: linear-gradient(135deg, #e0f2ff, #f0f9ff);
                    border: 1px solid #b6e0fe;
                    border-left: 5px solid #0d6efd;
                    border-radius: 10px;
                    color: #0c5460;
                    padding: 12px 16px;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                }

                .custom-alert-info .icon {
                    font-size: 20px;
                    color: #0d6efd;
                }

                .custom-alert-warning {
                    background: linear-gradient(135deg, #fff4e5, #fffaf0);
                    border: 1px solid #ffe0b2;
                    border-left: 5px solid #f59e0b;
                    border-radius: 12px;
                    color: #7c4a03;
                    padding: 14px 18px;
                    box-shadow: 0 3px 8px rgba(0,0,0,0.06);
                }

                .custom-alert-warning .icon {
                    font-size: 20px;
                    color: #f59e0b;
                }

                /* badge */
                .flag-badge {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    background: white;
                    padding: 4px 10px;
                    border-radius: 20px;
                    border: 1px solid #ffe0b2;
                    font-size: 12px;
                    font-weight: 600;
                    color: #7c4a03;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
                }

                .flag-badge img {
                    width: 18px;
                    height: auto;
                    border-radius: 3px;
                }

                /* Tarjetas */
                .card {
                    border: none;
                    border-radius: 1rem;
                    transition: transform .2s ease, box-shadow .2s ease;
                    background: #fff;
                }

                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
                }

                /* Progress bar animada */
                .progress-bar {
                    background: linear-gradient(90deg, #36d1dc, #5b86e5);
                    transition: width 1s ease-in-out;
                }

                /* Botones */
                .btn {
                    border-radius: 30px;
                    transition: all .3s ease;
                }
                .btn:hover {
                    transform: scale(1.05);
                }

                /* Modal más elegante */
                .modal-content {
                    border-radius: 1rem;
                    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
                }

                /* ===== SIDEBAR BASE ===== */
                #accordionSidebar{
                    position: fixed;
                    top: 0;
                    left: -280px;
                    width: 280px;
                    height: 100vh;
                    background: linear-gradient(180deg,#1f5f8b,#174a6b);
                    font-size: 13px;
                    transition: .3s;
                    z-index: 1040;
                    overflow-y:auto;
                    padding-bottom:20px;
                }

                #accordionSidebar.show{
                    left:0;
                }

                #sidebarOverlay{
                    position:fixed;
                    inset:0;
                    background:rgba(0,0,0,.45);
                    opacity:0;
                    visibility:hidden;
                    transition:.3s;
                    z-index:1030;
                }
                #sidebarOverlay.active{
                    opacity:1;
                    visibility:visible;
                }

                /* LOGO */
                .sidebar-brand{
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    justify-content:center;
                    padding:18px;
                    margin:12px;
                    border-radius:14px;
                    background:rgba(255,255,255,.08);
                    color:#fff;
                    text-align:center;
                }
                .sidebar-brand img{
                    max-width:140px;
                    margin-bottom:6px;
                }

                /* ITEMS */
                #accordionSidebar .nav-link{
                    color:#fff;
                    padding:10px 14px;
                    border-radius:10px;
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                }
                #accordionSidebar .nav-link:hover{
                    background:rgba(255,255,255,.12);
                }

                /* FLECHA */
                .caret{
                    transition:.3s;
                }
                .nav-link.collapsed .caret{
                    transform:rotate(0deg);
                }
                .nav-link:not(.collapsed) .caret{
                    transform:rotate(90deg);
                }

                /* SUBMENU */
                .collapse-inner{
                    background:#fff;
                    margin:6px 10px;
                    border-radius:10px;
                    padding:6px;
                }
                .collapse-item{
                    display:block;
                    padding:8px 10px;
                    color:#333;
                    font-size:12px;
                }

                /* BOTON MOBILE */
                #mobileSidebarToggle{
                    position:fixed;
                    top:12px;
                    left:12px;
                    z-index:1050;
                    width:44px;
                    height:44px;
                    border:none;
                    border-radius:10px;
                    background:#174a6b;
                    color:#fff;
                }

                #mobileSidebarToggle{
                    position: fixed;
                    top: 10px;
                    left: 14px;
                    z-index: 1050;

                    width: 45px;
                    height: 45px;

                    border: 1px solid rgba(255,255,255,.18);
                    border-radius: 14px;

                    background: #1f5f8b;
                    color: #fff;
                    font-size: 20px;

                    display:flex;
                    align-items:center;
                    justify-content:center;

                    cursor:pointer;

                    box-shadow:
                        0 12px 26px rgba(0,0,0,.35),
                        inset 0 1px 0 rgba(255,255,255,.15);

                    transition: all .25s ease;
                }

                /* estado activo (sidebar abierto) */
                #mobileSidebarToggle.active{
                    box-shadow:
                        0 16px 34px rgba(0,0,0,.45),
                        0 0 18px rgba(31,95,139,.9),
                        0 0 30px rgba(23,74,107,.7),
                        inset 0 1px 0 rgba(255,255,255,.25);

                    transform: scale(1.04);
                }

                /* hover extra */
                #mobileSidebarToggle:hover{
                    transform: translateY(-2px);
                }

                #mobileSidebarToggle:active{
                    transform: scale(.95);
                }
            </style>

            <button id="mobileSidebarToggle"><i class="fa fa-bars"></i></button>
            <div id="sidebarOverlay"></div>

            <ul id="accordionSidebar" class="navbar-nav">
                <a class="sidebar-brand" href="dashboard.php">
                    <img src="../images/logo-fygroup-v1_bg_removed.png">
                    <div><b>' . $infoCfg['name'] . '</b></div>
                </a>
        ';

        foreach ($menus as $menu) {
            $sidebar .= '
                <li class="nav-item">
                    <a class="nav-link collapsed" data-toggle="collapse" href="#' . $menu['id'] . '">
                        <span><i class="fa ' . $menu['icon'] . '"></i> ' . $menu['title'] . '</span>
                        <i class="fa fa-angle-right caret"></i>
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
                </li>
            ';
        }

        $sidebar .= '
                <div class="text-white p-3 mt-auto text-center">
                    <div><i class="fas fa-copyright"></i> ' . $infoCfg['name'] . '</div>
                    <div><i class="fas fa-code-branch"></i> ' . $infoCfg['version'] . '</div>
                    <div><i class="fas fa-rotate"></i> ' . $updateTime->format('d-m-Y H:i') . '</div>
                </div>
            </ul>

            <script>
                document.addEventListener("DOMContentLoaded", function(){
                    const sidebar = document.getElementById("accordionSidebar");
                    const toggle = document.getElementById("mobileSidebarToggle");
                    const overlay = document.getElementById("sidebarOverlay");

                    function close(){
                        sidebar.classList.remove("show");
                        overlay.classList.remove("active");
                        toggle.classList.remove("active");
                    }

                    function open(){
                        sidebar.classList.add("show");
                        overlay.classList.add("active");
                        toggle.classList.add("active");
                    }

                    toggle.addEventListener("click", function(){
                        const isOpen = sidebar.classList.contains("show");

                        if(isOpen){
                            close();
                        }else{
                            open();
                        }
                    });

                    overlay.addEventListener("click", close);

                    window.addEventListener("resize", function(){
                        if(window.innerWidth >= 769) close();
                    });

                    close();
                });
            </script>
        ';

        return $sidebar;
    }

    public static function mainTapBarSSL()
    {
        $user = new user();
        $arrayDivision = get::getDivisionName();

        $admin = $user->isAdmin($_SESSION['user']['run']);
        $fullName = htmlspecialchars($_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name']);
        $run = $_SESSION['user']['run'];
        $division = $_SESSION['user']['division'];
        $avatarName = $user->avatarIniciales($fullName, 38);

        $tapBar = '
            <style>
                .topbar{
                    background: linear-gradient(180deg,#1f5f8b,#174a6b);
                    border-bottom: 1px solid rgba(255,255,255,.08);
                    height: 56px;
                    box-shadow: 0 6px 18px rgba(0,0,0,.25);
                }

                /* reloj */
                #relojFecha{
                    font-weight: 500;
                    letter-spacing:.3px;
                }

                /* dropdown arrow */
                #userDropdown .arrow{
                    transition:.25s;
                    font-size:11px;
                    margin-left:8px;
                    opacity:.9;
                }

                #userDropdown.show .arrow{
                    transform: rotate(180deg);
                }

                /* hover usuario */
                #userDropdown{
                    border-radius:12px;
                    transition:.2s ease;
                }

                #userDropdown:hover{
                    background: rgba(255,255,255,.08);
                }

                /* avatar */
                .user-avatar{
                    border-radius:50%;
                    box-shadow: 0 4px 12px rgba(0,0,0,.25);
                }

                /* dropdown */
                .dropdown-menu{
                    border: none;
                    border-radius: 14px;
                    overflow: hidden;
                    box-shadow: 0 12px 28px rgba(0,0,0,.25);
                    margin-top: 10px;
                }

                /* header dropdown */
                .dropdown-menu .border-bottom{
                    background: linear-gradient(180deg,#1f5f8b,#174a6b);
                    color:#fff;
                }

                /* items */
                .dropdown-item{
                    font-size: 13px;
                    padding: 10px 14px;
                    transition:.2s;
                }

                .dropdown-item:hover{
                    background: rgba(31,95,139,.08);
                    transform: translateX(3px);
                }

                /* iconos */
                .dropdown-item i{
                    width: 18px;
                }

                /* divider */
                .dropdown-divider{
                    border-color: rgba(0,0,0,.06);
                }

                #relojFecha {
                    display: inline-block;
                }

                .fa-clock {
                    display: inline-block;
                }

                @media (max-width: 576px){
                    #userDropdown .flex-column{
                        display: none !important;
                    }
                }
            </style>

            <nav class="navbar navbar-expand navbar-dark topbar shadow-sm">
                <div class="container-fluid d-flex align-items-center justify-content-between">
                    <!-- reloj -->
                    <div class="position-absolute w-100 text-white small d-flex justify-content-center align-items-center" style="pointer-events:none;">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="relojFecha"></span>
                    </div>

                    <!-- usuario -->
                    <ul class="navbar-nav ml-auto mr-2">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle d-flex align-items-center px-3" href="#" id="userDropdown" data-toggle="dropdown">
                                <div class="user-avatar">
                                    ' . $avatarName . '
                                </div>

                                <div class="d-flex flex-column ml-2 mr-2 text-left">
                                    <span class="text-white small font-weight-bold">' . $fullName . '</span>
                                    <span class="text-white small" style="opacity:.75;">' . $run . '</span>
                                </div>

                                <i class="fas fa-chevron-down arrow text-white"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <div class="px-3 py-3 text-center border-bottom">
                                    <img src="../images/logo-fygroup-v1_bg_removed.png" width="70">
                                    <br>
                                    <small style="opacity:.8;">' . $arrayDivision[$division] . '</small>
                                </div>

                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#userModal">
                                    <i class="fas fa-user"></i> Perfil
                                </a>
                ';

        if ($admin) {
            $tapBar .= '
                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#goalModal">
                                    <i class="fas fa-cogs"></i> Ajustar Capacidad
                                </a>';
        }

        $tapBar .= '
                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#licenseModal">
                                    <i class="fas fa-copyright"></i> Licencia
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            ';

        return $tapBar;
    }

    public static function sideBarPortal()
    {
        $cfg = new cfg();
        $infoCfg = json_decode($cfg->getInfo(1), true);

        $sideBarPortal = '<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#1e293b;">';
        $sideBarPortal .= '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">';
        $sideBarPortal .= '<img src="../images/logo-fygroup-v1_bg_removed.png" style="width:100%;">';
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
        $tapBarPortal .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION['user']['name'] . '!</span>';
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
        $tapBarPortal .= '<span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, ' . $_SESSION['user']['name'] . '!</span>';
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
        $cfg = new cfg();
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
