<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

class menu extends iQuery
{
    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public static function menu()
    {
        $user = new user();

        if ($user->isDev($_SESSION['user']['run'])) {
            return self::developerMenu();
        }

        $menus = self::userMenu();

        if ($user->isAdmin($_SESSION['user']['run'])) {
            $menus = array_merge($menus, self::adminMenu());
        }

        return $menus;
    }

    private static function userMenu()
    {
        $menus = [
            [
                'title' => 'Operaciones',
                'icon' => 'fa-truck',
                'id' => 'collapseOperaciones',
                'items' => [
                    ['label' => 'Ingreso Contenedores', 'link' => generateSecureLink('enter_container_port')],
                    ['label' => 'Ingreso Termos', 'link' => generateSecureLink('enter_thermo_port')],
                    ['label' => 'Carga Internacional', 'link' => generateSecureLink('enter_container_international')],
                    ['label' => 'Seguimiento', 'link' => generateSecureLink('tracking')],
                    ['label' => 'Roleo de Carga', 'link' => generateSecureLink('vessel_transfer')],
                ],
            ],
            [
                'title' => 'Puerto',
                'icon' => 'fa-anchor',
                'id' => 'collapsePuerto',
                'items' => [
                    ['label' => 'Naves', 'link' => generateSecureLink('enter_ship')],
                    ['label' => 'Lineas Navieras', 'link' => generateSecureLink('enter_ship_line')],
                    ['label' => 'Puertos', 'link' => generateSecureLink('enter_port')],
                ],
            ],
            [
                'title' => 'Empresas',
                'icon' => 'fa-building',
                'id' => 'collapseEmpresas',
                'items' => [
                    ['label' => 'Empresa', 'link' => generateSecureLink('enter_company')],
                ],
            ],
            [
                'title' => 'Itinerarios',
                'icon' => 'fa-calendar-days',
                'id' => 'collapseProgramacion',
                'items' => [
                    ['label' => 'Itinerarios FY', 'link' => generateSecureLink('program_fygroup')],
                    ['label' => 'Itinerarios TPC', 'link' => generateSecureLink('program_tpc')],
                    ['label' => 'Itinerarios EPCO', 'link' => generateSecureLink('program_epco')],
                    ['label' => 'Cool Carriers', 'link' => generateSecureLink('program_cool_carriers')],
                    ['label' => 'Global Reefers', 'link' => generateSecureLink('program_global_reefers')],
                ],
            ],
            [
                'title' => 'Live Position',
                'icon' => 'fa-satellite',
                'id' => 'collapseLivePosition',
                'items' => [
                    ['label' => 'Live Position', 'link' => generateSecureLink('marinetraffic_live_map')],
                ],
            ],
            [
                'title' => 'Portadas',
                'icon' => 'fa-file-pen',
                'id' => 'collapsePortadas',
                'items' => [
                    ['label' => 'Crear Portada', 'link' => generateSecureLink('cover_maker')],
                ],
            ],
            [
                'title' => 'Layout',
                'icon' => 'fa-satellite',
                'id' => 'collapseLayout',
                'items' => [
                    ['label' => 'Layout Antepuerto', 'link' => generateSecureLink('layout_antepuerto')],
                ],
            ],
        ];

        return $menus;
    }

    private static function adminMenu()
    {
        $menus = [
            [
                'title' => 'Maersk',
                'icon' => 'fa-ship',
                'id' => 'collapseMaersk',
                'items' => [
                    ['label' => 'Punto a Punto', 'link' => generateSecureLink('point_schedule_maersk')],
                    ['label' => 'Puerto', 'link' => generateSecureLink('port_schedule_maersk')],
                    ['label' => 'Nave', 'link' => generateSecureLink('vessel_schedule_maersk')],
                    ['label' => 'Programación', 'link' => generateSecureLink('program_maersk')],
                    ['label' => 'Seguimiento de Carga', 'link' => generateSecureLink('tracking_schedule_maersk')],
                ],
            ],
            [
                'title' => 'MSC',
                'icon' => 'fa-ship',
                'id' => 'collapseMedlog',
                'items' => [
                    ['label' => 'Stacking MSC', 'link' => generateSecureLink('program_msc')],
                    ['label' => 'Importación MSC', 'link' => generateSecureLink('program_import_msc')],
                    ['label' => 'EIR Medlog', 'link' => generateSecureLink('eir_msc')],
                ],
            ],
            [
                'title' => 'Reportes',
                'icon' => 'fa-file-pdf',
                'id' => 'collapseReporte',
                'items' => [
                    ['label' => 'Reporte por Nave', 'link' => generateSecureLink('ship_report')],
                    ['label' => 'Liquidación de Nave', 'link' => generateSecureLink('vessel_liquidation')],
                    ['label' => 'Reporte de Turno', 'link' => generateSecureLink('shifts_report')],
                    ['label' => 'Reporte de Temporada', 'link' => generateSecureLink('seasons_report')],
                ],
            ],
            [
                'title' => 'Estadística',
                'icon' => 'fa-chart-bar',
                'id' => 'collapseEstadistica',
                'items' => [
                    ['label' => 'Estadística Naves', 'link' => generateSecureLink('stadistics_by_vessel')],
                ],
            ],
            [
                'title' => 'Tarifario',
                'icon' => 'fa-dollar-sign',
                'id' => 'collapsePrecio',
                'items' => [
                    ['label' => 'Lista de Tarifas ', 'link' => generateSecureLink('list_price_indicators')],
                ],
            ],
            [
                'title' => 'Usuarios',
                'icon' => 'fa-users',
                'id' => 'collapseUser',
                'items' => [
                    ['label' => 'Usuarios', 'link' => generateSecureLink('enter_user')],
                ],
            ],
            [
                'title' => 'Servidor',
                'icon' => 'fa-server',
                'id' => 'collapseServer',
                'items' => [
                    ['label' => 'Carga Planificación', 'link' => generateSecureLink('load_schedule')],
                ],
            ],
        ];

        return $menus;
    }

    private static function developerMenu()
    {
        $menus = [
            [
                'title' => 'Estados',
                'icon' => 'fa-bars-progress',
                'id' => 'collapseStatus',
                'items' => [
                    ['label' => 'Estados', 'link' => generateSecureLink('system_status_manager')],
                ],
            ],
            [
                'title' => 'Desarrollador',
                'icon' => 'fa-code',
                'id' => 'collapseDeveloper',
                'items' => [
                    ['label' => 'SQL Administrador', 'link' => generateSecureLink('sql_console')],
                    ['label' => 'Backup', 'link' => generateSecureLink('backup_database')],
                    ['label' => 'Respaldo de Archivos', 'link' => generateSecureLink('files_backup')],
                ],
            ],
            [
                'title' => 'Información',
                'icon' => 'fa-circle-info',
                'id' => 'collapseInfo',
                'items' => [
                    ['label' => 'PHP', 'link' => generateSecureLink('php_info')],
                    ['label' => 'Servidor', 'link' => generateSecureLink('server_info')],
                    ['label' => 'Sistema', 'link' => generateSecureLink('system_info')],
                ],
            ],
        ];

        return $menus;
    }

    public static function breadcrumb()
    {
        $menus = self::menu();
        $currentPage = $_GET['pag'] ?? '';

        ob_start();
        ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item home">
                        <a href="dashboard.php">
                            <i class="fas fa-home"></i>
                            <span>Inicio</span>
                        </a>
                    </li>

                    <?php foreach ($menus as $menu): ?>
                        <?php foreach ($menu['items'] as $item): ?>
                            <?php $url = parse_url($item['link']);?>
                            <?php parse_str($url['query'] ?? '', $query);?>
                            <?php $menuPage = $query['pag'] ?? '';?>

                            <?php if ($menuPage === $currentPage): ?>
                                <li class="breadcrumb-item menu">
                                    <?= $menu['title']; ?>
                                </li>

                                <li class="breadcrumb-item page active">
                                    <?= $item['label']; ?>
                                </li>
                                <?php break 2; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>

        <?php

        return ob_get_clean();
    }

    public static function sideBarSSL()
    {
        $infoCfg = json_decode((new cfg())->getInfo(1), true);
        $updateTime = new DateTime($infoCfg['update_date']);
        $menus = self::menu();

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
                    <img src="../logos/new-logo-fygroup-bg-removed.png" alt="FYGroup" class="preload-logo">
                </a>

                <div style="align-self: center;color: #fff; font-size: larger;">
                    <b>Sistema Antepuerto</b>
                </div>
                <br>

                <a class="nav-link d-flex align-items-center justify-content-start" href="dashboard.php" style="color:#fff; padding:8px 12px;">
                    <i class="fas fa-home"></i>
                    <span style="margin-left:8px;">Inicio</span>
                </a>

                <?php foreach ($menus as $menu): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#<?= $menu['id'] ?>">
                            <span><i class="fas <?= $menu['icon'] ?>"></i> <?= $menu['title'] ?></span>
                            <i class="fas fa-angle-right caret"></i>
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
            <nav class="navbar navbar-expand topbar">
                <div class="container-fluid d-flex align-items-center justify-content-between">
                    <!-- Reloj -->
                    <div class="clock-box">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="relojFecha"></span>
                    </div>

                    <!-- Usuario -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- Avatar -->
                                <div class="user-avatar">
                                    <?= $avatarName ?>
                                </div>

                                <!-- Info -->
                                <div class="d-flex flex-column text-left ml-2 user-info">
                                    <span class="text-white small font-weight-bold text-truncate">
                                        <?= $fullName ?>
                                    </span>

                                    <span class="text-white small user-run">
                                        <?= $run ?>
                                    </span>
                                </div>

                                <!-- Flecha -->
                                <i class="fas fa-chevron-down text-white ml-2"></i>
                            </a>

                            <!-- Dropdown -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="px-3 py-3 text-center border-bottom">
                                    <div class="d-flex justify-content-center mb-2">
                                        <div class="logo-box">
                                            <img src="../logos/new-logo-fygroup-bg-removed.png" width="100">
                                        </div>
                                    </div>

                                    <small style="opacity:.8;">
                                        <?= $arrayDivision[$division] ?>
                                    </small>
                                </div>

                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#userModal">
                                    <i class="fas fa-user mr-2"></i>
                                    Perfil
                                </a>

                                <?php if ($admin): ?>
                                    <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#goalModal">
                                        <i class="fas fa-cogs mr-2"></i>
                                        Ajustar Capacidad
                                    </a>
                                <?php endif; ?>

                                <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#licenseModal">
                                    <i class="fas fa-copyright mr-2"></i>
                                    Licencia
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Cerrar Sesión
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
        $infoCfg = json_decode((new cfg())->getInfo(1), true);
        $updateTime = new DateTime($infoCfg['update_date']);

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
                    <img src="../logos/new-logo-fygroup-bg-removed.png">
                </a>

                <div style="align-self:center;color:#fff;font-size:larger;">
                    <b>Sistema Antepuerto</b>
                </div>

                <div style="align-self:center;color:#cbd5e1;font-size:13px;">
                    Portal Cliente
                </div>

                <br>

                <a class="nav-link d-flex align-items-center justify-content-start" href="dashboard.php" style="color:#fff;padding:8px 12px;">
                    <i class="fas fa-home"></i>
                    <span style="margin-left:8px;">Inicio</span>
                </a>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#collapseAntepuerto" data-toggle="collapse" aria-expanded="false">
                        <span>
                            <i class="fas fa-truck"></i>
                            Antepuerto
                        </span>

                        <i class="fas fa-angle-right caret"></i>
                    </a>

                    <div id="collapseAntepuerto" class="collapse" data-parent="#accordionSidebar">
                        <div class="collapse-inner">
                            <a class="collapse-item submenu-item"
                            href="<?= generateSecureLink('enter_container_port') ?>">
                                Ingreso Contenedores
                            </a>

                            <a class="collapse-item submenu-item"
                            href="<?= generateSecureLink('enter_thermo_port') ?>">
                                Ingreso Termos
                            </a>
                        </div>
                    </div>
                </li>

                <div class="text-white p-3 mt-auto text-center">
                    <div>
                        <i class="fas fa-copyright"></i>
                        <?= $infoCfg['name'] ?>
                    </div>

                    <div>
                        <i class="fas fa-code-branch"></i>
                        <?= $infoCfg['version'] ?>
                    </div>

                    <div>
                        <i class="fas fa-rotate"></i>
                        <?= $updateTime->format('d-m-Y H:i') ?>
                    </div>
                </div>
            </ul>
        <?php

        return ob_get_clean();
    }

    public static function secondTapBarPortal()
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
            <nav class="navbar navbar-expand topbar">
                <div class="container-fluid d-flex align-items-center justify-content-between">

                    <!-- Reloj -->
                    <div class="clock-box">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="relojFecha"></span>

                        <div class="mx-3 d-none d-md-block"
                            style="width:1px;height:25px;background:rgba(255,255,255,.2);"></div>

                        <i class="fas fa-hourglass-half mr-2 d-none d-md-block"></i>
                        <span id="countDownSession" class="d-none d-md-block"></span>
                    </div>

                    <!-- Usuario -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">

                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- Avatar -->
                                <div class="user-avatar">
                                    <?= $avatarName ?>
                                </div>

                                <!-- Información Usuario -->
                                <div class="d-flex flex-column text-left ml-2 user-info">
                                    <span class="text-white small font-weight-bold text-truncate">
                                        <?= $fullName ?>
                                    </span>

                                    <span class="text-white small user-run">
                                        <?= $run ?>
                                    </span>
                                </div>

                                <!-- Flecha -->
                                <i class="fas fa-chevron-down text-white ml-2"></i>
                            </a>

                            <!-- Dropdown -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="px-3 py-3 text-center border-bottom">
                                    <div class="d-flex justify-content-center mb-2">
                                        <div class="logo-box">
                                            <img src="../logos/new-logo-fygroup-bg-removed.png" width="70" alt="Logo">
                                        </div>
                                    </div>

                                    <div>
                                        <strong><?= $fullName ?></strong>
                                    </div>

                                    <small style="opacity:.8;">
                                        Portal Cliente
                                    </small>

                                </div>

                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
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
            <footer class="footer-ssl">
                <div class="footer-inner">
                    <img class="footer-logo". src="../logos/new-logo-fygroup-bg-removed.png" alt="FYGroup - Sistema Integral">

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
