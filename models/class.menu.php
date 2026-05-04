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
                    'title' => 'Usuarios',
                    'icon' => 'fa-user',
                    'id' => 'collapseUser',
                    'items' => [
                        ['label' => 'Usuarios', 'link' => generateMkey('enter_user')],
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

        ob_start();
        ?>
            <button id="mobileSidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div id="sidebarOverlay"></div>

            <ul id="accordionSidebar" class="navbar-nav">
                <a class="sidebar-brand" href="dashboard.php">
                    <img src="../images/logo-fygroup-v1_bg_removed.png">
                </a>

                <div style="align-self: center;color: #fff; font-size: larger;">
                    <b>Sistema Antepuerto</b>
                </div>
                <br>

                <a class="nav-link d-flex align-items-center justify-content-start" href="dashboard.php" style="color:#fff; padding:8px 12px;">
                    <i class="fa fa-home"></i>
                    <span style="margin-left:8px;">Inicio</span>
                </a>

                <?php foreach ($menus as $menu): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#<?= $menu['id'] ?>">
                            <span><i class="fa <?= $menu['icon'] ?>"></i> <?= $menu['title'] ?></span>
                            <i class="fa fa-angle-right caret"></i>
                        </a>

                        <div id="<?= $menu['id'] ?>" class="collapse" data-parent="#accordionSidebar">
                            <div class="collapse-inner">
                                <?php foreach ($menu['items'] as $item): ?>
                                    <a class="collapse-item submenu-item" href="<?= $item['link'] ?>">
                                        <?= $item['label'] ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>

                <div class="text-white p-3 mt-auto text-center">
                    <div><i class="fas fa-copyright"></i> <?= $infoCfg['name'] ?></div>
                    <div><i class="fas fa-code-branch"></i> <?= $infoCfg['version'] ?></div>
                    <div><i class="fas fa-rotate"></i> <?= $updateTime->format('d-m-Y H:i') ?></div>
                </div>
            </ul>

        <?php

        return ob_get_clean();
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

        ob_start();
        ?>
            <style>
                body{
                    padding-top: 56px;
                }

                .topbar{
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    z-index: 1050;

                    background: linear-gradient(180deg,#1f5f8b,#174a6b);
                    border-bottom: 1px solid rgba(255,255,255,.08);
                    height: 56px;
                    box-shadow: 0 6px 18px rgba(0,0,0,.25);
                }

                #relojFecha{
                    font-weight: 500;
                    letter-spacing:.3px;
                }

                #userDropdown .arrow{
                    transition:.25s;
                    font-size:11px;
                    margin-left:8px;
                    opacity:.9;
                }

                #userDropdown.show .arrow{
                    transform: rotate(180deg);
                }

                #userDropdown{
                    border-radius:12px;
                    transition:.2s ease;
                }

                #userDropdown:hover{
                    background: rgba(255,255,255,.08);
                }

                .user-avatar{
                    border-radius:50%;
                    box-shadow: 0 4px 12px rgba(0,0,0,.25);
                }

                .dropdown-menu{
                    border: none;
                    border-radius: 14px;
                    overflow: hidden;
                    box-shadow: 0 12px 28px rgba(0,0,0,.25);
                    margin-top: 10px;
                    padding-right: 15px;
                }

                .dropdown-menu .border-bottom{
                    background: linear-gradient(180deg,#1f5f8b,#174a6b);
                    color:#fff;
                }

                .dropdown-item{
                    font-size: 13px;
                    padding: 10px 14px;
                    transition:.2s;
                }

                .dropdown-item:hover{
                    background: rgba(31,95,139,.08);
                    transform: translateX(3px);
                }

                .dropdown-item i{
                    width: 18px;
                }

                .dropdown-divider{
                    border-color: rgba(0,0,0,.06);
                }

                @media (max-width: 576px){
                    #userDropdown .flex-column{
                        display: none !important;
                    }
                }
            </style>

            <nav class="navbar navbar-expand navbar-dark topbar shadow-sm">
                <div class="container-fluid d-flex align-items-center">
                    <!-- Reloj -->
                    <div class="position-absolute w-100 text-white small d-flex justify-content-center align-items-center" style="pointer-events:none;">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="relojFecha"></span>
                    </div>

                    <!-- Usuario -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-toggle="dropdown">
                                <div class="user-avatar">
                                    <?= $avatarName ?>
                                </div>

                                <div class="d-flex flex-column text-left">
                                    <span class="text-white small font-weight-bold"><?= $fullName ?></span>
                                    <span class="text-white small" style="opacity:.75;"><?= $run ?></span>
                                </div>

                                <i class="fas fa-chevron-down arrow text-white"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <div class="px-3 py-3 text-center border-bottom">
                                    <div class="d-flex justify-content-center mb-2">
                                        <div style="background: rgba(255,255,255,.08); padding: 8px 14px; border-radius: 12px; box-shadow: 0 6px 14px rgba(0,0,0,.25); backdrop-filter: blur(4px);">
                                            <img src="../images/logo-fygroup-v1_bg_removed.png" width="70">
                                        </div>
                                    </div>

                                    <small style="opacity:.8;"><?= $arrayDivision[$division] ?></small>
                                </div>

                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#userModal">
                                    <i class="fas fa-user"></i> Perfil
                                </a>

                                <?php if ($admin): ?>
                                    <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#goalModal">
                                        <i class="fas fa-cogs"></i> Ajustar Capacidad
                                    </a>
                                <?php endif; ?>

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

        <?php

        return ob_get_clean();
    }

    public static function sideBarPortal()
    {
        $cfg = new cfg();
        $infoCfg = json_decode($cfg->getInfo(1), true);

        ob_start();
        ?>
            <style>
                #accordionSidebar{
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 100vh;
                    z-index: 1040;
                    overflow-y: auto;
                }

                body{
                    padding-left: 220px; /* ajusta al ancho real del sidebar */
                }

                @media (max-width: 768px){
                    body{
                        padding-left: 0;
                    }

                    #accordionSidebar{
                        position: absolute;
                    }
                }
            </style>

            <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#1e293b;">

                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                    <img src="../images/logo-fygroup-v1_bg_removed.png" style="width:100%;">
                </a>

                <div class="sidebar-heading">Sistema Antepuerto</div>
                <div class="sidebar-heading">(Portal Cliente)</div>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAntepuerto">
                        <i class="fas fa-fw fa-truck"></i>
                        <span>Antepuerto</span>
                    </a>

                    <div id="collapseAntepuerto" class="collapse" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Items:</h6>

                            <a class="collapse-item" href="<?= generateMkey('enter_container_port', 'myPortal') ?>">
                                Ingreso Contenedores
                            </a>

                            <a class="collapse-item" href="<?= generateMkey('enter_thermo_port', 'myPortal') ?>">
                                Ingreso Termos
                            </a>
                        </div>
                    </div>
                </li>

                <hr class="sidebar-divider d-none d-md-block">

                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

                <div class="d-flex flex-column h-100">
                    <div class="text-center d-none d-md-inline mt-auto" style="color: white;">
                        <hr class="sidebar-divider">
                        <small><?= $infoCfg['name'] ?></small>
                        <br>
                        <small><b>Versión: </b><?= $infoCfg['version'] ?></small>
                    </div>
                </div>
            </ul>

        <?php

        return ob_get_clean();
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
        ob_start();
        ?>
            <style>
                body{
                    padding-top: 56px;
                }

                .topbar-portal-2{
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 56px;
                    z-index: 1050;

                    background: #1e293b;
                    box-shadow: 0 4px 12px rgba(0,0,0,.25);
                }

                .topbar-divider{
                    width: 1px;
                    height: 20px;
                    background: rgba(255,255,255,.2);
                    margin: 0 10px;
                }

                #relojFecha,
                #countDownSession{
                    color: white;
                }
            </style>

            <nav class="navbar navbar-expand navbar-light topbar-portal-2 shadow-sm">

                <button class="btn btn-link text-white">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto align-items-center">

                    <li class="nav-item d-flex align-items-center">
                        <i class="fas fa-clock text-white"></i>
                        <span id="relojFecha" class="ml-2"></span>
                    </li>

                    <div class="topbar-divider"></div>

                    <li class="nav-item d-flex align-items-center">
                        <i class="fas fa-clock text-white"></i>
                        <span id="countDownSession" class="ml-2"></span>
                    </li>

                    <div class="topbar-divider"></div>

                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">

                            <span class="mr-2 text-white">
                                Bienvenido, <?= $_SESSION['user']['name'] ?>!
                            </span>

                            <img class="img-profile rounded-circle" src="../images/undraw_profile.svg" width="32">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow">

                            <a class="dropdown-item text-danger"
                            href="logout.php"
                            data-toggle="modal"
                            data-target="#logoutModal">

                                <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

        <?php

        return ob_get_clean();
    }

    public static function footerSSL()
    {
        $cfg = new cfg();
        $infoCfg = json_decode($cfg->getInfo(1), true);

        ob_start();
        ?>
            <style>
                body{
                    padding-bottom: 56px;
                }

                .footer-ssl{
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 56px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #0f172a;
                    color: rgba(255,255,255,.75);
                    box-shadow: 0 -6px 18px rgba(0,0,0,.35);
                    z-index: 1000;
                    font-size: 13px;
                }

                .footer-inner{
                    width: 100%;
                    max-width: 1200px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 12px;
                    padding: 0 15px;
                }

                .footer-logo{
                    height: 30px;
                    background: #fff;
                    padding: 4px 8px;
                    border-radius: 6px;
                }

                .footer-text{
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    flex-wrap: wrap;
                    justify-content: center;
                    text-align: center;
                }

                .footer-mark{
                    font-weight: 600;
                    color: #ffffff;
                }

                .footer-dot{
                    opacity: .4;
                }

                .footer-icon{
                    color: #38bdf8;
                }

                @media (max-width: 600px){
                    .footer-ssl{
                        font-size: 11px;
                    }

                    .footer-logo{
                        height: 20px;
                    }
                }
            </style>

            <footer class="footer-ssl">
                <div class="footer-inner">
                    <img class="footer-logo". src="../images/logo-fygroup-v1_bg_removed.png" alt="FYGroup - Sistema Integral">

                    <div class="footer-text">
                        <i class="fas fa-copyright footer-icon"></i>
                        <span><?= date('Y') ?></span>

                        <span class="footer-dot">•</span>

                        <span class="footer-mark">
                            <?= htmlspecialchars($infoCfg['mark']) ?>
                        </span>

                        <span class="footer-dot">•</span>

                        <span>Todos los derechos reservados</span>
                    </div>
                </div>
            </footer>

        <?php

        return ob_get_clean();
    }

}
