<?php
require_once __DIR__ . '/../models/class.ship.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/class.config.php';

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

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (vessel_id, car_plate, guide_number, container, seal_number, exporter, agency, cellphone_driver, arrival_date, comodity, booking, stay, observations, pallets_quantity, origin, created)";
    $query .= " VALUES (:vessel, :carplate, :guide, :container, :seal, :exporter, :agency, :cellphonedriver, :arrivaldate, :comodity, :booking, :stay, :observations, :palletsquantity, :origin, :created)";

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
    //$this->departuredate   = $this->departuredate;
    $this->comodity     = htmlspecialchars(strip_tags($this->comodity));
    $this->booking      = htmlspecialchars(strip_tags($this->booking));
    $this->stay         = htmlspecialchars(strip_tags($this->stay ?? ''));
    $this->observations = htmlspecialchars(strip_tags($this->observations));
    $this->pallets      = htmlspecialchars(strip_tags($this->pallets));
    $this->origin       = htmlspecialchars(strip_tags($this->origin));
    $this->created      = $this->created;

    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
    $stmt->bindParam(":carplate", $this->carplate);
    $stmt->bindParam(":guide", $this->guide);
    $stmt->bindParam(":container", $this->container);
    $stmt->bindParam(":seal", $this->seal);
    $stmt->bindParam(":exporter", $this->exporter);
    $stmt->bindParam(":agency", $this->agency);
    $stmt->bindParam(":cellphonedriver", $this->cellphonedriver);
    $stmt->bindParam(":arrivaldate", $this->arrivaldate);
    //$stmt->bindParam(":departuredate", $this->departuredate);
    $stmt->bindParam(":comodity", $this->comodity);
    $stmt->bindParam(":booking", $this->booking);
    $stmt->bindParam(":stay", $this->stay);
    $stmt->bindParam(":observations", $this->observations);
    $stmt->bindParam(":palletsquantity", $this->pallets, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin);
    $stmt->bindParam(":created", $this->created);

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
    $query = "SELECT COUNT(*) as totalContainer FROM $this->table WHERE $this->origin  = 1";
    $stmt  = $this->conexion->prepare($query);
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
    $query = "SELECT COUNT(pallets_quantity) as totalPallets FROM $this->table WHERE $this->origin  = 1";
    $stmt  = $this->conexion->prepare($query);
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
    $query = "SELECT COUNT(*) as totalThermo FROM $this->table WHERE $this->origin = 2";
    $stmt  = $this->conexion->prepare($query);
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
    $query = "SELECT SUM(pallets_quantity) as totalPallets FROM $this->table WHERE $this->origin = 2";
    $stmt  = $this->conexion->prepare($query);
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
    $query = "SELECT COUNT(*) as total FROM $this->table WHERE 1";
    $stmt  = $this->conexion->prepare($query);
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
    $query = "SELECT COUNT(*) as total FROM $this->table WHERE departure_date = '0000-00-00 00:00:00'";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total   = $result['total'];
    $percent = (($total * 100) / $goals);

    return number_format($percent, 2, ',', '');
  }

  public function getTotalTrucksInAnpuerto()
  {
    $query = "SELECT COUNT(*) as total FROM $this->table WHERE departure_date = '0000-00-00 00:00:00'";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total = $result['total'];

    return $total;
  }

  public function getTableContainer()
  {
    $ship  = new ship($this->conexion);
    $count = 0;

    /* Filtros */
    $filterNave       = isset($_POST['nave']) ? trim($_POST['nave']) : '';
    $filterCondicion  = isset($_POST['condicion']) ? trim($_POST['condicion']) : '';
    $filterExportador = isset($_POST['exportador']) ? trim($_POST['exportador']) : '';

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 1"];
    $params     = [];

    if ($filterNave !== '') {
      $conditions[]    = "sh.vessel_name LIKE :nave";
      $params[':nave'] = "%$filterNave%";
    }

    if ($filterCondicion !== '') {
      $conditions[]         = "$this->comodity LIKE :condicion";
      $params[':condicion'] = "%$filterCondicion%";
    }

    if ($filterExportador !== '') {
      $conditions[]          = "$this->exporter LIKE :exportador";
      $params[':exportador'] = "%$filterExportador%";
    }

    $whereClause = implode(' AND ', $conditions);
    $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause ORDER BY row_id ASC";
    $stmt        = $this->conexion->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Formulario de filtros */
    $form = "
      <form method='POST' class='mb-3 sticky-form col-8' id='filterFormThermo'>
        <div class='form-row mb-2'>
          <div class='col'>
            <input type='text' name='nave' class='form-control' placeholder='Motonave' value='" . htmlspecialchars($filterNave) . "'>
          </div>
          <div class='col'>
            <input type='text' name='condicion' class='form-control' placeholder='Condición' value='" . htmlspecialchars($filterCondicion) . "'>
          </div>
          <div class='col'>
            <input type='text' name='exportador' class='form-control' placeholder='Exportador' value='" . htmlspecialchars($filterExportador) . "'>
          </div>
        </div>

        <div class='form-row'>
          <div class='col'>
            <button type='submit' class='btn btn-sm btn-primary'><i class='fas fa-solid fa-search'></i> Buscar</button>
            <button type='button' class='btn btn-sm btn-success' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['condicion'] ?? "") . "', '" . htmlspecialchars($_POST['exportador'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
            <button type='button' class='btn btn-sm btn-warning' onclick='location.href=location.pathname'><i class='fas fa-undo'></i> Recargar Filtros</button>
          </div>
        </div>
      </form>
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
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr       = null;
    $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $created = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
        $arrival = (new DateTime($data[$this->arrivaldate]))->format('d-m-Y H:i');

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
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = $hours . ' horas y ' . $minutes . ' minutos';
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr>";
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
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= "<td colspan='17' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = "
    <div class='container-fluid'>
      <div class='sticky-form bg-white pr-3 pl-3 mb-3'>
        <h6 class='h3 mb-1 text-gray-800'>Filtro de Búsqueda</h6>
        " . $form . "
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

  public function downloadTableContainerExcel($nave = '', $condicion = '', $exportador = '')
  {
    $ship = new ship($this->conexion);

    $filtros = [];
    $where   = "WHERE $this->origin = 1";

    if (!empty($nave)) {
      $where .= " AND sh.vessel_name LIKE ?";
      $filtros[] = "%$nave%";
    }

    if (!empty($condicion)) {
      $where .= " AND $this->comodity LIKE ?";
      $filtros[] = "%$condicion%";
    }

    if (!empty($exportador)) {
      $where .= " AND $this->exporter LIKE ?";
      $filtros[] = "%$exportador%";
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where ORDER BY row_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Crear Excel */
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Contenedores');

    /* Encabezados */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Contenedor', 'Sello', 'Exportador', 'Agencia', 'Pallets', 'Teléfono', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Agregar los datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $created   = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
      $arrival   = (new DateTime($data[$this->arrivaldate]))->format('d-m-Y H:i');
      $departure = $data[$this->departuredate] != '0000-00-00 00:00:00' ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = $hours . ' horas y ' . $minutes . ' minutos';
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
        $created
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
    $filterNave       = isset($_POST['nave']) ? trim($_POST['nave']) : '';
    $filterCondicion  = isset($_POST['condicion']) ? trim($_POST['condicion']) : '';
    $filterExportador = isset($_POST['exportador']) ? trim($_POST['exportador']) : '';

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 2"];
    $params     = [];

    if ($filterNave !== '') {
      $conditions[]    = "sh.vessel_name LIKE :nave";
      $params[':nave'] = "%$filterNave%";
    }

    if ($filterCondicion !== '') {
      $conditions[]         = "$this->comodity LIKE :condicion";
      $params[':condicion'] = "%$filterCondicion%";
    }

    if ($filterExportador !== '') {
      $conditions[]          = "$this->exporter LIKE :exportador";
      $params[':exportador'] = "%$filterExportador%";
    }

    $whereClause = implode(' AND ', $conditions);
    $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause ORDER BY row_id ASC";
    $stmt        = $this->conexion->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Formulario de filtros */
    $form = "
      <form method='POST' class='mb-3 sticky-form col-8' id='filterFormThermo'>
        <div class='form-row mb-2'>
          <div class='col'>
            <input type='text' name='nave' class='form-control' placeholder='Motonave' value='" . htmlspecialchars($filterNave) . "'>
          </div>
          <div class='col'>
            <input type='text' name='condicion' class='form-control' placeholder='Condición' value='" . htmlspecialchars($filterCondicion) . "'>
          </div>
          <div class='col'>
            <input type='text' name='exportador' class='form-control' placeholder='Exportador' value='" . htmlspecialchars($filterExportador) . "'>
          </div>
        </div>

        <div class='form-row'>
          <div class='col'>
            <button type='submit' class='btn btn-sm btn-primary'><i class='fas fa-solid fa-search'></i> Buscar</button>
            <button type='button' class='btn btn-sm btn-success' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['condicion'] ?? "") . "', '" . htmlspecialchars($_POST['exportador'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
            <button type='button' class='btn btn-sm btn-warning' onclick='location.href=location.pathname'><i class='fas fa-undo'></i> Recargar Filtros</button>
          </div>
        </div>
      </form>
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
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr       = null;
    $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $created = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
        $arrival = (new DateTime($data[$this->arrivaldate]))->format('d-m-Y H:i');

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
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = $hours . ' horas y ' . $minutes . ' minutos';
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == '0000-00-00 00:00:00') {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr>";
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
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= "<td colspan='14' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = "
    <div class='container-fluid'>
      <div class='sticky-form bg-white pr-3 pl-3 mb-3'>
        <h6 class='h3 mb-1 text-gray-800'>Filtro de Búsqueda</h6>
        " . $form . "
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

  public function downloadTableThermoExcel($nave = '', $condicion = '', $exportador = '')
  {
    $ship = new ship($this->conexion);

    $filtros = [];
    $where   = "WHERE $this->origin = 2";

    if (!empty($nave)) {
      $where .= " AND sh.vessel_name LIKE ?";
      $filtros[] = "%$nave%";
    }

    if (!empty($condicion)) {
      $where .= " AND $this->comodity LIKE ?";
      $filtros[] = "%$condicion%";
    }

    if (!empty($exportador)) {
      $where .= " AND $this->exporter LIKE ?";
      $filtros[] = "%$exportador%";
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where ORDER BY row_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Termos');

    /* Encabezados del Excel */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Exportador', 'Pallets', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Filas de datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $created   = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
      $arrival   = (new DateTime($data[$this->arrivaldate]))->format('d-m-Y H:i');
      $departure = $data[$this->departuredate] != '0000-00-00 00:00:00' ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != '0000-00-00 00:00:00') {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = $hours . ' horas y ' . $minutes . ' minutos';
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
        $created
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

}
