<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

class html
{
  protected PDO $db;
  protected array $infoCfg = [];
  protected DateTime $releasedTime;
  protected DateTime $updateTime;

  protected bool $admin             = false;
  protected string $sideBar         = '';
  protected string $topBar          = '';
  protected string $footer          = '';
  protected string $topButton       = '';
  protected array $cardBody         = [];
  protected string $titlePrefix     = 'SSL | ';
  protected string $title           = '';
  protected string $shortTitle      = '';
  protected string $descriptionPage = '';

  public function __construct()
  {
    $this->db = (new Database())->getConnection();

    $cfg  = new cfg($this->db);
    $user = new user($this->db);

    $this->infoCfg      = json_decode($cfg->getInfo(1), true);
    $this->releasedTime = new DateTime($this->infoCfg['released_date']);
    $this->updateTime   = new DateTime($this->infoCfg['update_date']);

    $this->admin = $user->isAdmin($_SESSION["user"]["run"]);

    $this->sideBar   = menu::sideBarSSL();
    $this->topBar    = menu::secondTapBarSSL();
    $this->footer    = menu::footerSSL();
    $this->topButton = UIComponents::scrollToTopButton();

    if (!$this->admin) {
      $usuario = $_SESSION["user"]["name"] . ' ' .
        $_SESSION["user"]["last_name"] . ' (' .
        $_SESSION["user"]["run"] . ')';

      $pag = basename($_SERVER['SCRIPT_NAME']);
      $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

      mostrarAccesoDenegado($usuario, $pag, $url);
      exit;
    }
  }

  /* ===== Setters / Getters ===== */
  public function addCardBody(string $content): void
  {
    $this->cardBody[] = $content;
  }

  public function setCardBody(array $contents): void
  {
    $this->cardBody = $contents;
  }

  public function getCardBody(): string
  {
    return implode("\n", $this->cardBody);
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getTitle(): string
  {
    return $this->titlePrefix . $this->title;
  }

  public function setShortTitle(string $shortTitle): void
  {
    $this->shortTitle = $shortTitle;
  }

  public function getShortTitle(): string
  {
    return $this->shortTitle;
  }

  public function setDescriptionPage(string $descriptionPage): void
  {
    $this->descriptionPage = $descriptionPage;
  }

  public function getDescriptionPage(): string
  {
    return $this->descriptionPage;
  }

  /* ===== Render HTML ===== */
  protected function loadHtml(): string
  {
    ob_start();
    ?>
		<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="utf-8">

			<!-- Responsive correcto -->
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<!-- Compatibilidad -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge">

			<!-- Color en móviles -->
			<meta name="theme-color" content="#4e73df">

			<link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
			<title><?= $this->getTitle(); ?></title>

			<!-- Custom fonts for this template-->
			<link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
			<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

			<!-- Custom styles for this template-->
			<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
		</head>

		<body id="page-top">
		<div id="wrapper">
			<?= $this->sideBar ?>

			<div id="content-wrapper" class="d-flex flex-column">
				<div id="content">
					<?= $this->topBar ?>

					<div class="container-fluid">
						<h1 class="h3 mb-1 text-gray-800">
							<?= $this->getShortTitle(); ?>
						</h1>
						<p class="mb-4">
							<?= $this->getDescriptionPage(); ?>
						</p>

						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">
									<?= $this->getShortTitle(); ?>
								</h6>
							</div>

							<div class="card-body">
								<?= $this->getCardBody(); ?>
							</div>
						</div>
					</div>
				</div>

				<?= $this->footer ?>
			</div>
		</div>

		<?= $this->topButton ?>

		<!-- Logout Modal -->
		<div class="modal fade" id="logoutModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">¿Deseas cerrar sesión?</h5>
						<button type="button" class="close" data-bs-dismiss="modal">
							<span>×</span>
						</button>
					</div>
					<div class="modal-body">
						Selecciona 'Cerrar sesión' si realmente deseas hacerlo.
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" data-dismiss="modal">Cancelar</button>
						<a class="btn btn-danger" href="logout.php">
							<i class="fas fa-sign-out-alt"></i> Cerrar sesión
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Info Modal -->
		<div class="modal fade" id="infoModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Acerca del Sistema</h5>
						<button type="button" class="close" data-bs-dismiss="modal">
							<span>×</span>
						</button>
					</div>
					<div class="modal-body">
						<small><b>Nombre:</b> <?= $this->infoCfg['name'] ?></small><br>
						<small><b>Versión:</b> <?= $this->infoCfg['version'] ?></small><br>
						<small><b>Compilación:</b> <?= $this->infoCfg['compilation'] ?></small><br>
						<small><b>Lanzamiento:</b> <?= $this->releasedTime->format('d-m-Y H:i') ?></small><br>
						<small><b>Últ. Actualización:</b> <?= $this->updateTime->format('d-m-Y H:i') ?></small>
					</div>
				</div>
			</div>
		</div>

		<!-- JS -->
		<!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Bootstrap JS (necesario para popover) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		</body>
		</html>
		<?php

    return ob_get_clean();
  }

  public function draw(): void
  {
    echo $this->loadHtml();
  }
}
