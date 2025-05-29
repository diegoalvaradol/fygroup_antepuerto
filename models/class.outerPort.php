<?php
require_once __DIR__ . '/../models/class.ship.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/class.config.php';
require_once __DIR__ . '/../models/class.user.php';

class outerPort
{
  private $conexion;
  protected $table = "app_outer_port";

  public $id              = "row_id";
  public $vessel          = "vessel_id";
  public $carplate        = "car_plate";
  public $guide           = "guide_number";
  public $container       = "container";
  public $seal            = "seal_number";
  public $exporter        = "exporter";
  public $agency          = "agency";
  public $cellphonedriver = "cellphone_driver";
  public $arrivaldate     = "arrival_date";
  public $departuredate   = "departure_date";
  public $comodity        = "comodity";
  public $booking         = "booking";
  public $stay            = "stay";
  public $observations    = "observations";
  public $pallets         = "pallets_quantity";
  public $origin          = "origin";
  public $created         = "created";
  public $createdby       = "created_by";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (vessel_id, car_plate, guide_number, container, seal_number, exporter, agency, cellphone_driver, arrival_date, comodity, booking, stay, observations, pallets_quantity, origin, created, created_by)";
    $query .= " VALUES (:vessel, :carplate, :guide, :container, :seal, :exporter, :agency, :cellphonedriver, :arrivaldate, :comodity, :booking, :stay, :observations, :palletsquantity, :origin, :created, :createdby)";

    $stmt = $this->conexion->prepare($query);

    $this->vessel          = htmlspecialchars(strip_tags($this->vessel));
    $this->carplate        = htmlspecialchars(strip_tags($this->carplate));
    $this->guide           = htmlspecialchars(strip_tags($this->guide));
    $this->container       = htmlspecialchars(strip_tags($this->container ?? ''));
    $this->seal            = htmlspecialchars(strip_tags($this->seal ?? ''));
    $this->exporter        = htmlspecialchars(strip_tags($this->exporter));
    $this->agency          = htmlspecialchars(strip_tags($this->agency ?? ''));
    $this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver ?? ''));
    $this->arrivaldate     = $this->arrivaldate;
    $this->comodity        = htmlspecialchars(strip_tags($this->comodity));
    $this->booking         = htmlspecialchars(strip_tags($this->booking));
    $this->stay            = htmlspecialchars(strip_tags($this->stay ?? ''));
    $this->observations    = htmlspecialchars(strip_tags($this->observations));
    $this->pallets         = htmlspecialchars(strip_tags($this->pallets));
    $this->origin          = htmlspecialchars(strip_tags($this->origin));
    $this->created         = $this->created;
    $this->createdby       = htmlspecialchars(strip_tags($this->createdby));

    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
    $stmt->bindParam(":carplate", $this->carplate);
    $stmt->bindParam(":guide", $this->guide);
    $stmt->bindParam(":container", $this->container);
    $stmt->bindParam(":seal", $this->seal);
    $stmt->bindParam(":exporter", $this->exporter);
    $stmt->bindParam(":agency", $this->agency);
    $stmt->bindParam(":cellphonedriver", $this->cellphonedriver);
    $stmt->bindParam(":arrivaldate", $this->arrivaldate);
    $stmt->bindParam(":comodity", $this->comodity);
    $stmt->bindParam(":booking", $this->booking);
    $stmt->bindParam(":stay", $this->stay);
    $stmt->bindParam(":observations", $this->observations);
    $stmt->bindParam(":palletsquantity", $this->pallets, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":createdby", $this->createdby);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET departure_date = :departuredate WHERE row_id = :id AND origin = :origin";
    $stmt  = $this->conexion->prepare($query);

    $this->id            = htmlspecialchars(strip_tags($this->id));
    $this->departuredate = $this->departuredate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin, PDO::PARAM_INT);
    $stmt->bindParam(":departuredate", $this->departuredate);

    return $stmt->execute();
  }

  public function getTotalContainer()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(*) as totalContainer FROM $this->table WHERE $this->origin = 1";
    } else {
      $query = "SELECT COUNT(*) as totalContainer FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.origin = 1 AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['totalContainer'] > 0) {
      echo $result['totalContainer'];
    } else {
      echo 0;
    }
  }

  public function getTotalContainerPallets()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(pallets_quantity) as totalPallets FROM $this->table WHERE $this->origin = 1";
    } else {
      $query = "SELECT COUNT(pallets_quantity) as totalPallets FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.origin = 1 AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['totalPallets'] > 0) {
      echo $result['totalPallets'] * 20;
    } else {
      echo 0;
    }
  }

  public function getTotalThermo()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(*) as totalThermo FROM $this->table WHERE $this->origin = 2";
    } else {
      $query = "SELECT COUNT(*) as totalThermo FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.origin = 2 AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['totalThermo'] > 0) {
      return $result['totalThermo'];
    } else {
      return 0;
    }
  }

  public function getTotalPallets()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(pallets_quantity) as totalPallets FROM $this->table WHERE $this->origin = 2";
    } else {
      $query = "SELECT COUNT(pallets_quantity) as totalPallets FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.origin = 2 AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['totalPallets'] > 0) {
      return $result['totalPallets'];
    } else {
      return 0;
    }
  }

  public function getTotalTrucks()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(*) as total FROM $this->table WHERE 1";
    } else {
      $query = "SELECT COUNT(*) as total FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE 1 AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['total'] > 0) {
      return $result['total'];
    } else {
      return 0;
    }
  }

  public function getPercentUsage($goals)
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(*) as total FROM $this->table WHERE departure_date = '0000-00-00 00:00:00'";
    } else {
      $query = "SELECT COUNT(*) as total FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.departure_date = '0000-00-00 00:00:00' AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total   = $result['total'];
    $percent = (($total * 100) / $goals);

    return number_format($percent, 2, ',', '');
  }

  public function getTotalTrucksInAnpuerto()
  {
    $user  = new user($this->conexion);
    $admin = $user->isAdmin($_SESSION["user"]["run"]);

    if ($admin) {
      $query = "SELECT COUNT(*) as total FROM $this->table WHERE departure_date = '0000-00-00 00:00:00'";
    } else {
      $query = "SELECT COUNT(*) as total FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE p.departure_date = '0000-00-00 00:00:00' AND sh.finished = 0";
    }

    $stmt = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total = $result['total'];

    return $total;
  }

  public function findByUser($run)
  {
    $query = "SELECT * FROM app_users WHERE run = :run";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":run", $run);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $result["name"] . ' ' . $result["last_name"];

    return $name;
  }

  public function getTableContainer()
  {
    $ship  = new ship($this->conexion);
    $count = 0;

    /* Filtros */
    $filterNave    = isset($_POST['nave']) ? $_POST['nave'] : '';
    $filterPatente = isset($_POST['patente']) ? $_POST['patente'] : '';
    $filterGuia    = isset($_POST['guia']) ? trim($_POST['guia']) : '';

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 1"];
    $params     = [];

    if ($filterNave !== '') {
      $conditions[]    = "sh.ship_id = :nave";
      $params[':nave'] = $filterNave;
    }

    if ($filterPatente !== '') {
      $conditions[]       = "$this->carplate = :patente";
      $params[':patente'] = $filterPatente;
    }

    if ($filterGuia !== '') {
      $conditions[]    = "$this->guide LIKE :guia";
      $params[':guia'] = "%$filterGuia%";
    }

    $whereClause = implode(' AND ', $conditions);
    $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause AND sh.finished = 0 ORDER BY row_id ASC";
    $stmt        = $this->conexion->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Formulario de filtros */
    $form = "
    <div class='row'>
      <div class='col-lg-12'>
        <div class='card shadow mb-4'>
          <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Formulario de Búsqueda</h6>
          </div>

          <div class='card-body'>
            <form method='POST' class='form-container' id='filterFormContainer'>
              <div class='form-group row'>
                <div class='col-sm-4'>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'></select>
                </div>
                <div class='col-sm-4'>
                  <select class='form-control select2 form-control-user' id='patente' name='patente'></select>
                </div>
                <div class='col-sm-4'>
                  <input type='text' name='guia' class='form-control' placeholder='N° de Guía' value='" . htmlspecialchars($filterGuia) . "'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user'><i class='fas fa-solid fa-search'></i> Buscar</button>
                </br>
                <button type='button' class='btn btn-sm btn-success btn-user' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                </br>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=location.pathname'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#2653d4; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Posición</th>";
    $thead .= "<th>Nave</th>";
    $thead .= "<th>Patente</th>";
    $thead .= "<th>Guía</th>";
    $thead .= "<th>Contenedor</th>";
    $thead .= "<th>Sello</th>";
    $thead .= "<th>Exportador</th>";
    $thead .= "<th>Agencia</th>";
    $thead .= "<th>Pallets</th>";
    $thead .= "<th>Teléfono</th>";
    $thead .= "<th>Entrada</th>";
    $thead .= "<th>Salida</th>";
    $thead .= "<th>Tiempo de Estadía</th>";
    $thead .= "<th>Condición</th>";
    $thead .= "<th>Booking</th>";
    $thead .= "<th>Estadía</th>";
    $thead .= "<th>Obersvaciones</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Ingresado Por</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->created]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<button type='button' class='btn btn-danger btn-user btn-sm'><i class='fas fa-solid fa-exclamation-triangle'></i> " . $data[$this->comodity] . "</button>";
        } else {
          $comodity = "<button type='button' class='btn btn-success btn-user btn-sm'><i class='fas fa-solid fa-check'></i> " . $data[$this->comodity] . "</button>";
        }

        if ($data[$this->departuredate] != '0000-00-00 00:00:00') {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = $_SESSION["user"]["division"] == 'ssl' ? "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editContainerHour(" . $data[$this->id] . ")'><i class='fas fa-solid fa-clock'></i> Salida</button>" : 'Sin hora de salida.';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
          $arrivalDate   = new DateTime($data[$this->arrivaldate]);
          $departureDate = new DateTime($data[$this->departuredate]);

          $interval = $arrivalDate->diff($departureDate);
          $days     = $interval->format('%d');
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

          if ($days >= 1) {
            $attr = "style='background-color:red; color:white;'";
          }
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->id] . "</td>";
        $tr .= "<td>" . $ship->getVesselName($data[$this->vessel]) . "</td>";
        $tr .= "<td>" . $data[$this->carplate] . "</td>";
        $tr .= "<td>" . $data[$this->guide] . "</td>";
        $tr .= "<td>" . $data[$this->container] . "</td>";
        $tr .= "<td>" . $data[$this->seal] . "</td>";
        $tr .= "<td>" . $data[$this->exporter] . "</td>";
        $tr .= "<td>" . $data[$this->agency] . "</td>";
        $tr .= "<td>" . $data[$this->pallets] . "</td>";
        $tr .= "<td>" . $data[$this->cellphonedriver] . "</td>";
        $tr .= "<td>" . $arrival . "</td>";
        $tr .= "<td>" . $departure . "</td>";
        $tr .= "<td>" . $stayTime . "</td>";
        $tr .= "<td>" . $comodity . "</td>";
        $tr .= "<td>" . $data[$this->booking] . "</td>";
        $tr .= "<td>" . $data[$this->stay] . "</td>";
        $tr .= "<td>" . $data[$this->observations] . "</td>";
        $tr .= "<td>" . $created . "</td>";
        $tr .= "<td>" . $this->findByUser($data[$this->createdby]) . "</td>";
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= "<td colspan='19' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = $form . "
    <div class='container-fluid'>
      <div class='sticky-form bg-white pr-3 pl-3 mb-3'>
        <h6 class='h3 mb-1 text-gray-800'>Listado de Contenedores</h6>
        <h6> Total de Registros: " . $count . "</h6>
      </div>

      <div class='table-responsive'>
        <table class='table table-bordered table-hover' style='width:max-content;'>
        " . $thead . $tr . $tbclose . "
        </table>
      </div>
    </div>
    ";

    return $table;
  }

  public function downloadTableContainerExcel($nave = '', $patente = '', $guia = '')
  {
    $ship = new ship($this->conexion);

    $filtros = [];
    $where   = "WHERE $this->origin = 1";

    if (!empty($nave)) {
      $where .= " AND sh.ship_id = ?";
      $filtros[] = "$nave";
    }

    if (!empty($patente)) {
      $where .= " AND $this->carplate = ?";
      $filtros[] = "$patente";
    }

    if (!empty($guia)) {
      $where .= " AND $this->guide LIKE ?";
      $filtros[] = "%$guia%";
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where AND sh.finished = 0 ORDER BY row_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Crear Excel */
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Contenedores');

    /* Encabezados */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Contenedor', 'Sello', 'Exportador', 'Agencia', 'Pallets', 'Teléfono', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Ingresado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Agregar los datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->created]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created   = $createdTime->format('d-m-Y H:i');
      $arrival   = $arrivalTime->format('d-m-Y H:i');
      $departure = $data[$this->departuredate] != '0000-00-00 00:00:00' ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';
      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->id],
        $ship->getVesselName($data[$this->vessel]),
        $data[$this->carplate],
        $data[$this->guide],
        $data[$this->container],
        $data[$this->seal],
        $data[$this->exporter],
        $data[$this->agency],
        $data[$this->pallets],
        $data[$this->cellphonedriver],
        $arrival,
        $departure,
        $stayTime,
        $data[$this->comodity],
        $data[$this->booking],
        $data[$this->stay],
        $data[$this->observations],
        $created,
        $this->findByUser($data[$this->createdby])
      ], null, 'A' . $row);

      $row++;
    }

    /* Enviar headers para descarga */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_Contenedores_Antepuerto_' . date('d-m-Y H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');

    /* Descargar el archivo */
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');

    exit;
  }

  public function getTableThermo()
  {
    $ship  = new ship($this->conexion);
    $count = 0;

    /* Filtros */
    $filterNave    = isset($_POST['nave']) ? $_POST['nave'] : '';
    $filterPatente = isset($_POST['patente']) ? $_POST['patente'] : '';
    $filterGuia    = isset($_POST['guia']) ? trim($_POST['guia']) : '';

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 2"];
    $params     = [];

    if ($filterNave !== '') {
      $conditions[]    = "sh.ship_id = :nave";
      $params[':nave'] = $filterNave;
    }

    if ($filterPatente !== '') {
      $conditions[]       = "$this->carplate = :patente";
      $params[':patente'] = $filterPatente;
    }

    if ($filterGuia !== '') {
      $conditions[]    = "$this->guide LIKE :guia";
      $params[':guia'] = "%$filterGuia%";
    }

    $whereClause = implode(' AND ', $conditions);
    $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause AND sh.finished = 0 ORDER BY row_id ASC";
    $stmt        = $this->conexion->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Formulario de filtros */
    $form = "
    <div class='row'>
      <div class='col-lg-12'>
        <div class='card shadow mb-4'>
          <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Formulario de Búsqueda</h6>
          </div>

          <div class='card-body'>
            <form method='POST' class='form-container' id='filterFormThermo'>
              <div class='form-group row'>
                <div class='col-sm-4'>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'></select>
                </div>
                <div class='col-sm-4'>
                  <select class='form-control select2 form-control-user' id='patente' name='patente'></select>
                </div>
                <div class='col-sm-4'>
                  <input type='text' name='guia' class='form-control' placeholder='N° de Guía' value='" . htmlspecialchars($filterGuia) . "'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=location.pathname'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#2653d4; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Posición</th>";
    $thead .= "<th>Nave</th>";
    $thead .= "<th>Patente</th>";
    $thead .= "<th>Guía</th>";
    $thead .= "<th>Exportador</th>";
    $thead .= "<th>Pallets</th>";
    $thead .= "<th>Entrada</th>";
    $thead .= "<th>Salida</th>";
    $thead .= "<th>Tiempo de Estadia</th>";
    $thead .= "<th>Condición</th>";
    $thead .= "<th>Booking</th>";
    $thead .= "<th>Estadía</th>";
    $thead .= "<th>Obersvaciones</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Ingresado Por</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->created]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<button type='button' class='btn btn-danger btn-user btn-sm'><i class='fas fa-solid fa-exclamation-triangle'></i> " . $data[$this->comodity] . "</button>";
        } else {
          $comodity = "<button type='button' class='btn btn-success btn-user btn-sm'><i class='fas fa-solid fa-check'></i> " . $data[$this->comodity] . "</button>";
        }

        if ($data[$this->departuredate] != '0000-00-00 00:00:00') {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = $_SESSION["user"]["division"] == 'ssl' ? "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editTermoHour(" . $data[$this->id] . ")'><i class='fas fa-solid fa-clock'></i> Salida</button>" : 'Sin hora de salida.';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
          $arrivalDate   = new DateTime($data[$this->arrivaldate]);
          $departureDate = new DateTime($data[$this->departuredate]);

          $interval = $arrivalDate->diff($departureDate);
          $days     = $interval->format('%d');
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

          if ($days >= 1) {
            $attr = "style='background-color:red; color:white;'";
          }
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->id] . "</td>";
        $tr .= "<td>" . $ship->getVesselName($data[$this->vessel]) . "</td>";
        $tr .= "<td>" . $data[$this->carplate] . "</td>";
        $tr .= "<td>" . $data[$this->guide] . "</td>";
        $tr .= "<td>" . $data[$this->exporter] . "</td>";
        $tr .= "<td>" . $data[$this->pallets] . "</td>";
        $tr .= "<td>" . $arrival . "</td>";
        $tr .= "<td>" . $departure . "</td>";
        $tr .= "<td style='width:350px;'>" . $stayTime . "</td>";
        $tr .= "<td>" . $comodity . "</td>";
        $tr .= "<td>" . $data[$this->booking] . "</td>";
        $tr .= "<td>" . $data[$this->stay] . "</td>";
        $tr .= "<td>" . $data[$this->observations] . "</td>";
        $tr .= "<td>" . $created . "</td>";
        $tr .= "<td>" . $this->findByUser($data[$this->createdby]) . "</td>";
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= "<td colspan='15' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = $form . "
    <div class='container-fluid'>
      <div class='sticky-form bg-white pr-3 pl-3 mb-3'>
        <h6 class='h3 mb-1 text-gray-800'>Listado de Termos</h6>
        <h6> Total de Registros: " . $count . "</h6>
      </div>

      <div class='table-responsive'>
        <table class='table table-bordered table-hover' style='width:max-content;'>
        " . $thead . $tr . $tbclose . "
        </table>
      </div>
    </div>
    ";

    return $table;
  }

  public function downloadTableThermoExcel($nave = '', $patente = '', $guia = '')
  {
    $ship = new ship($this->conexion);

    $filtros = [];
    $where   = "WHERE $this->origin = 2";

    if (!empty($nave)) {
      $where .= " AND sh.ship_id = ?";
      $filtros[] = $nave;
    }

    if (!empty($patente)) {
      $where .= " AND $this->carplate = ?";
      $filtros[] = $patente;
    }

    if (!empty($guia)) {
      $where .= " AND $this->guide LIKE ?";
      $filtros[] = "%$guia%";
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where AND sh.finished = 0 ORDER BY row_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Termos');

    /* Encabezados del Excel */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Exportador', 'Pallets', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Ingresado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Filas de datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->created]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created = $createdTime->format('d-m-Y H:i');
      $arrival = $arrivalTime->format('d-m-Y H:i');

      $departure = $data[$this->departuredate] != '0000-00-00 00:00:00' ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->id],
        $ship->getVesselName($data[$this->vessel]),
        $data[$this->carplate],
        $data[$this->guide],
        $data[$this->exporter],
        $data[$this->pallets],
        $arrival,
        $departure,
        $stayTime,
        $data[$this->comodity],
        $data[$this->booking],
        $data[$this->stay],
        $data[$this->observations],
        $created,
        $this->findByUser($data[$this->createdby])
      ], null, 'A' . $row);

      $row++;
    }

    /* Cabeceras para descargar */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Reporte_Thermos_Antepuerto_' . date('d-m-Y H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');

    /* Descargar el archivo */
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');

    exit;
  }

  public function shipReport()
  {
    $ship  = new ship($this->conexion);
    $count = 0;

    /* Filtros */
    $filterNave  = isset($_POST['nave']) ? $_POST['nave'] : '';
    $filterTipo  = isset($_POST['tipo']) && $_POST['tipo'] != '-' ? $_POST['tipo'] : '';
    $filterDesde = isset($_POST['desde']) && $_POST['desde'] != '' ? $_POST['desde'] : '';
    $filterHasta = isset($_POST['hasta']) && $_POST['hasta'] != '' ? $_POST['hasta'] : '';

    /* Condiciones dinámicas */
    $conditions = ['1']; // Siempre verdadero para facilitar concatenación
    $params     = [];

    /* Filtrar por nave */
    if ($filterNave !== '') {
      $conditions[]    = "sh.ship_id = :nave";
      $params[':nave'] = $filterNave;
    }

    /* Filtrar por tipo */
    if ($filterTipo !== '') {
      $conditions[]    = "p.origin = :tipo"; // Asegúrate que "origin" sea una columna válida
      $params[':tipo'] = $filterTipo;
    }

    /* Fechas */
    if ($filterDesde !== '') {
      $conditions[]     = "p.arrival_date >= :desde"; // Usa >= para incluir el mismo día
      $params[':desde'] = $filterDesde . ' 00:00:00';
    }

    if ($filterHasta !== '') {
      $conditions[]     = "p.arrival_date <= :hasta"; // Usa <= para incluir el mismo día
      $params[':hasta'] = $filterHasta . ' 23:59:59';
    }

    $whereClause = implode(' AND ', $conditions);
    $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause ORDER BY p.row_id ASC";
    $stmt        = $this->conexion->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Formulario de filtros */
    $form = "
    <div class='row'>
      <div class='col-lg-12'>
        <div class='card shadow mb-4'>
          <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Formulario de Búsqueda</h6>
          </div>

          <div class='card-body'>
            <form method='POST' class='form-container' id='filterFormShipReport'>
              <div class='form-group row'>
                <div class='col-sm-3'>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'></select>
                </div>
                <div class='col-sm-3'>
                  <select class='form-control select2 form-control-user' id='tipo' name='tipo'>
                    <option value='-' selected>Seleccione una tipo...</option>
                    <option value='1'>Contenedores</option>
                    <option value='2'>Termos</option>
                  </select>
                </div>
                <div class='col-sm-3'>
                  <input type='date' class='form-control form-control-user' id='desde' name='desde'>
                </div>
                <div class='col-sm-3'>
                  <input type='date' class='form-control form-control-user' id='hasta' name='hasta'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['tipo'] ?? "") . "', '" . ($_POST['desde'] ?? "") . "', '" . ($_POST['hasta'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=location.pathname'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#2653d4; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Posición</th>";
    $thead .= "<th>Nave</th>";
    $thead .= "<th>Patente</th>";
    $thead .= "<th>Guía</th>";
    $thead .= "<th>Exportador</th>";
    $thead .= "<th>Pallets</th>";
    $thead .= "<th>Entrada</th>";
    $thead .= "<th>Salida</th>";
    $thead .= "<th>Tiempo de Estadia</th>";
    $thead .= "<th>Condición</th>";
    $thead .= "<th>Booking</th>";
    $thead .= "<th>Estadía</th>";
    $thead .= "<th>Obersvaciones</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Ingresado Por</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->created]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<button type='button' class='btn btn-danger btn-user btn-sm'><i class='fas fa-solid fa-exclamation-triangle'></i> " . $data[$this->comodity] . "</button>";
        } else {
          $comodity = "<button type='button' class='btn btn-success btn-user btn-sm'><i class='fas fa-solid fa-check'></i> " . $data[$this->comodity] . "</button>";
        }

        if ($data[$this->departuredate] != '0000-00-00 00:00:00') {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = $_SESSION["user"]["division"] == 'ssl' ? "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editTermoHour(" . $data[$this->id] . ")'><i class='fas fa-solid fa-clock'></i> Salida</button>" : 'Sin hora de salida.';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
          $arrivalDate   = new DateTime($data[$this->arrivaldate]);
          $departureDate = new DateTime($data[$this->departuredate]);

          $interval = $arrivalDate->diff($departureDate);
          $days     = $interval->format('%d');
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

          if ($days >= 1) {
            $attr = "style='background-color:red; color:white;'";
          }
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->id] . "</td>";
        $tr .= "<td>" . $ship->getVesselName($data[$this->vessel]) . "</td>";
        $tr .= "<td>" . $data[$this->carplate] . "</td>";
        $tr .= "<td>" . $data[$this->guide] . "</td>";
        $tr .= "<td>" . $data[$this->exporter] . "</td>";
        $tr .= "<td>" . $data[$this->pallets] . "</td>";
        $tr .= "<td>" . $arrival . "</td>";
        $tr .= "<td>" . $departure . "</td>";
        $tr .= "<td style='width:350px;'>" . $stayTime . "</td>";
        $tr .= "<td>" . $comodity . "</td>";
        $tr .= "<td>" . $data[$this->booking] . "</td>";
        $tr .= "<td>" . $data[$this->stay] . "</td>";
        $tr .= "<td>" . $data[$this->observations] . "</td>";
        $tr .= "<td>" . $created . "</td>";
        $tr .= "<td>" . $this->findByUser($data[$this->createdby]) . "</td>";
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= "<td colspan='15' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = $form . "
    <div class='container-fluid'>
      <div class='sticky-form bg-white pr-3 pl-3 mb-3'>
        <h6 class='h3 mb-1 text-gray-800'>Reporte</h6>
        <h6> Total de Registros: " . $count . "</h6>
      </div>

      <div class='table-responsive'>
        <table class='table table-bordered table-hover' style='width:max-content;'>
        " . $thead . $tr . $tbclose . "
        </table>
      </div>
    </div>
    ";

    return $table;
  }

  public function downloadTableShipReport($nave = '', $tipo = '', $desde = '', $hasta = '')
  {
    $ship = new ship($this->conexion);

    $filtros = [];
    $where   = "WHERE 1";

    if (!empty($nave)) {
      $where .= " AND sh.ship_id = ?";
      $filtros[] = $nave;
    }

    if (!empty($tipo) && $tipo != '-') {
      $where .= " AND p.origin = ?";
      $filtros[] = $tipo;
    }

    if (!empty($desde)) {
      $where .= " AND p.arrival_date >= ?";
      $filtros[] = $desde . ' 00:00:00';
    }

    if (!empty($hasta)) {
      $where .= " AND p.arrival_date <= ?";
      $filtros[] = $hasta . ' 23:59:59';
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where ORDER BY p.row_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Termos');

    /* Encabezados del Excel */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Exportador', 'Pallets', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Ingresado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Filas de datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->created]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created = $createdTime->format('d-m-Y H:i');
      $arrival = $arrivalTime->format('d-m-Y H:i');

      $departure = $data[$this->departuredate] != '0000-00-00 00:00:00' ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->id],
        $ship->getVesselName($data[$this->vessel]),
        $data[$this->carplate],
        $data[$this->guide],
        $data[$this->exporter],
        $data[$this->pallets],
        $arrival,
        $departure,
        $stayTime,
        $data[$this->comodity],
        $data[$this->booking],
        $data[$this->stay],
        $data[$this->observations],
        $created,
        $this->findByUser($data[$this->createdby])
      ], null, 'A' . $row);

      $row++;
    }

    /* Cabeceras para descargar */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Reporte_de_Naves_' . date('d-m-Y H:i:s') . '.xlsx"');
    header('Cache-Control: max-age=0');

    /* Descargar el archivo */
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');

    exit;
  }
}
