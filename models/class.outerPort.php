<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class outerPort extends iQuery
{
  protected string $table      = "app_outer_port";
  protected string $primaryKey = 'row_id';

  public $id              = "row_id";
  public $countervessel   = "counter_vessel";
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
  public $origin          = "origin"; /* [1 => Contenedores, 2 => Termos] */
  public $created         = "created";
  public $createdby       = "created_by";

  public function __construct()
  {
    parent::__construct(); // usa Database::get() desde iQuery
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (counter_vessel, vessel_id, car_plate, guide_number, container, seal_number, exporter, agency, cellphone_driver, arrival_date, departure_date, comodity, booking, stay, observations, pallets_quantity, origin, created, created_by)";
    $query .= " VALUES (:countervessel, :vessel, :carplate, :guide, :container, :seal, :exporter, :agency, :cellphonedriver, :arrivaldate, :departuredate, :comodity, :booking, :stay, :observations, :palletsquantity, :origin, :created, :createdby)";

    $stmt = $this->db->prepare($query);

    $this->countervessel   = htmlspecialchars(strip_tags($this->countervessel));
    $this->vessel          = htmlspecialchars(strip_tags($this->vessel));
    $this->carplate        = htmlspecialchars(strip_tags($this->carplate));
    $this->guide           = htmlspecialchars(strip_tags($this->guide));
    $this->container       = htmlspecialchars(strip_tags($this->container ?? ''));
    $this->seal            = htmlspecialchars(strip_tags($this->seal ?? ''));
    $this->exporter        = htmlspecialchars(strip_tags($this->exporter));
    $this->agency          = htmlspecialchars(strip_tags($this->agency ?? ''));
    $this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver ?? ''));
    $this->arrivaldate     = $this->arrivaldate;
    $this->departuredate   = null;
    $this->comodity        = htmlspecialchars(strip_tags($this->comodity));
    $this->booking         = htmlspecialchars(strip_tags($this->booking));
    $this->stay            = htmlspecialchars(strip_tags($this->stay ?? ''));
    $this->observations    = htmlspecialchars(strip_tags($this->observations));
    $this->pallets         = htmlspecialchars(strip_tags($this->pallets));
    $this->origin          = htmlspecialchars(strip_tags($this->origin));
    $this->created         = $this->created;
    $this->createdby       = htmlspecialchars(strip_tags($this->createdby));

    $stmt->bindParam(":countervessel", $this->countervessel, PDO::PARAM_INT);
    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
    $stmt->bindParam(":carplate", $this->carplate, PDO::PARAM_STR);
    $stmt->bindParam(":guide", $this->guide, PDO::PARAM_STR);
    $stmt->bindParam(":container", $this->container, PDO::PARAM_STR);
    $stmt->bindParam(":seal", $this->seal, PDO::PARAM_STR);
    $stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_STR);
    $stmt->bindParam(":agency", $this->agency, PDO::PARAM_STR);
    $stmt->bindParam(":cellphonedriver", $this->cellphonedriver, PDO::PARAM_STR);
    $stmt->bindParam(":arrivaldate", $this->arrivaldate, PDO::PARAM_STR);
    $stmt->bindValue(":departuredate", null, PDO::PARAM_NULL);
    $stmt->bindParam(":comodity", $this->comodity, PDO::PARAM_STR);
    $stmt->bindParam(":booking", $this->booking, PDO::PARAM_STR);
    $stmt->bindParam(":stay", $this->stay, PDO::PARAM_STR);
    $stmt->bindParam(":observations", $this->observations, PDO::PARAM_STR);
    $stmt->bindParam(":palletsquantity", $this->pallets, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":createdby", $this->createdby, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET departure_date = :departuredate WHERE row_id = :id AND origin = :origin";
    $stmt  = $this->db->prepare($query);

    $this->id            = htmlspecialchars(strip_tags($this->id));
    $this->departuredate = $this->departuredate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin, PDO::PARAM_INT);
    $stmt->bindParam(":departuredate", $this->departuredate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE row_id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function updateContainerThermo()
  {
    $query = "UPDATE $this->table SET counter_vessel = :countervessel, vessel_id = :vessel, car_plate = :carplate, guide_number = :guide, container = :container, seal_number = :seal, exporter = :exporter, agency = :agency, cellphone_driver = :cellphonedriver, arrival_date = :arrivaldate, comodity = :comodity, booking = :booking, stay = :stay, observations = :observations, pallets_quantity = :palletsquantity, created_by = :createdby WHERE row_id = :id AND origin = :origin";
    $stmt  = $this->db->prepare($query);

    $this->id              = htmlspecialchars(strip_tags($this->id));
    $this->countervessel   = htmlspecialchars(strip_tags($this->countervessel));
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
    $this->createdby       = htmlspecialchars(strip_tags($this->createdby));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":countervessel", $this->countervessel, PDO::PARAM_INT);
    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
    $stmt->bindParam(":carplate", $this->carplate, PDO::PARAM_STR);
    $stmt->bindParam(":guide", $this->guide, PDO::PARAM_STR);
    $stmt->bindParam(":container", $this->container, PDO::PARAM_STR);
    $stmt->bindParam(":seal", $this->seal, PDO::PARAM_STR);
    $stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_STR);
    $stmt->bindParam(":agency", $this->agency, PDO::PARAM_STR);
    $stmt->bindParam(":cellphonedriver", $this->cellphonedriver, PDO::PARAM_STR);
    $stmt->bindParam(":arrivaldate", $this->arrivaldate, PDO::PARAM_STR);
    $stmt->bindParam(":comodity", $this->comodity, PDO::PARAM_STR);
    $stmt->bindParam(":booking", $this->booking, PDO::PARAM_STR);
    $stmt->bindParam(":stay", $this->stay, PDO::PARAM_STR);
    $stmt->bindParam(":observations", $this->observations, PDO::PARAM_STR);
    $stmt->bindParam(":palletsquantity", $this->pallets, PDO::PARAM_INT);
    $stmt->bindParam(":origin", $this->origin, PDO::PARAM_INT);
    $stmt->bindParam(":createdby", $this->createdby);

    return $stmt->execute();
  }

  public function getTotalContainer($admin)
  {
    $query  = " SELECT COUNT(*) AS totalContainer FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.origin = 1";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.origin = 1 AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);

    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['totalContainer'] > 0 ? number_format($result['totalContainer'], 0, ',', '.') : 0;
  }

  public function getTotalContainerPallets($admin)
  {
    $query  = "SELECT COUNT(p.pallets_quantity) AS totalPallets FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.origin = 1";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.origin = 1
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['totalPallets'] > 0) ? number_format($result['totalPallets'] * 20, 0, ',', '.') : 0;
  }

  public function getTotalThermo($admin)
  {
    $query  = "SELECT COUNT(*) AS totalThermo FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.origin = 2";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.origin = 2
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 2
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 2
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['totalThermo'] > 0) ? number_format($result['totalThermo'], 0, ',', '.') : 0;
  }

  public function getTotalPallets($admin)
  {
    $query  = "SELECT COUNT(p.pallets_quantity) AS totalPallets FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.origin = 2";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.origin = 2
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 2
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.origin = 2
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['totalPallets'] > 0) ? number_format($result['totalPallets'] * 20, 0, ',', '.') : 0;
  }

  public function getTotalTrucks($admin)
  {
    $query  = "SELECT COUNT(*) as total FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE 1";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
         JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
         WHERE 1
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE 1
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['total'] > 0) ? number_format($result['total'], 0, ',', '.') : 0;
  }

  public function getPercentUsage($goals, $admin)
  {
    $query  = "SELECT COUNT(*) AS total FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.departure_date IS NULL";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total   = (int) $result['total'];
    $percent = $goals > 0 ? ($total * 100) / $goals : 0;

    return number_format($percent, 2, ',', '');
  }

  public function getTotalTrucksInAnpuerto($admin)
  {
    $query  = "SELECT COUNT(*) AS total FROM $this->table AS p";
    $params = [];

    if ($admin) {
      $query .= " WHERE p.departure_date IS NULL";
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
      ";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $query .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
        WHERE p.departure_date IS NULL
          AND sh.finished = 0
          AND sl.rut = :rut
      ";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $stmt = $this->db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return number_format((int) $result['total'], 0, ',', '.');
  }

  public function getTotalArrivedTrucks($admin)
  {
    $baseFrom = " FROM $this->table AS p";
    $joinShip = "";
    $whereAll = " WHERE 1";
    $whereAP  = " WHERE p.departure_date IS NULL";
    $params   = [];

    if ($admin) {
      // sin joins extra
    } elseif ($_SESSION["user"]["division"] === 'terminal') {
      $joinShip = " JOIN app_ships AS sh ON sh.ship_id = p.vessel_id";
      $whereAll .= " AND sh.finished = 0";
      $whereAP .= " AND sh.finished = 0";
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '96.591.730-6') { /* Cliente: Cool Carriers */
      $joinShip = " JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line";
      $whereAll .= " AND sh.finished = 0 AND sl.rut = :rut";
      $whereAP .= " AND sh.finished = 0 AND sl.rut = :rut";

      $params[':rut'] = $_SESSION["user"]["run"];
    } elseif ($_SESSION["user"]["division"] === 'shipper' && $_SESSION["user"]["run"] === '77.897.180-1') { /* Cliente: Seatrade */
      $joinShip = " JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line";
      $whereAll .= " AND sh.finished = 0 AND sl.rut = :rut";
      $whereAP .= " AND sh.finished = 0 AND sl.rut = :rut";

      $params[':rut'] = $_SESSION["user"]["run"];
    }

    $queryTotal      = "SELECT COUNT(*) AS total" . $baseFrom . $joinShip . $whereAll;
    $queryAntepuerto = "SELECT COUNT(*) AS total" . $baseFrom . $joinShip . $whereAP;

    /* Total arribados */
    $stmtTotal = $this->db->prepare($queryTotal);
    foreach ($params as $k => $v) {
      $stmtTotal->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmtTotal->execute();
    $totalArrivado = (int) $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    /* Total antepuerto */
    $stmtAntepuerto = $this->db->prepare($queryAntepuerto);
    foreach ($params as $k => $v) {
      $stmtAntepuerto->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmtAntepuerto->execute();
    $totalAntepuerto = (int) $stmtAntepuerto->fetch(PDO::FETCH_ASSOC)['total'];

    $totalDespachado = $totalArrivado - $totalAntepuerto;

    return number_format($totalDespachado, 0, ',', '.');
  }

  public function findByUser($run)
  {
    $query = "SELECT * FROM app_users WHERE run = :run";
    $stmt  = $this->db->prepare($query);
    $stmt->bindParam(":run", $run, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $result["name"] . ' ' . $result["last_name"];

    return $name;
  }

  public function vesselTransfer($fromVessel, $toVessel, $rowId)
  {
    $id = null;

    foreach ($rowId as $k => $v) {
      $id .= $v . ',';
    }

    $rows = rtrim($id, ',');

    $query = "UPDATE $this->table SET vessel_id = :tovessel WHERE vessel_id = :fromvessel AND row_id IN(:rows)";
    $stmt  = $this->db->prepare($query);

    $stmt->bindParam(":fromvessel", $fromVessel, PDO::PARAM_STR);
    $stmt->bindParam(":tovessel", $toVessel, PDO::PARAM_STR);
    $stmt->bindParam(":rows", $rows, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function trucksInOutPerDay($inicio, $fin)
  {
    $inicioCompleto = $inicio . ' 00:00:00';
    $finCompleto    = $fin . ' 23:59:59';

    $query = "
      SELECT dia,
            SUM(ingresos) AS total_ingresos,
            SUM(egresos) AS total_egresos
      FROM (
        SELECT DATE($this->arrivaldate) AS dia, COUNT(*) AS ingresos, 0 AS egresos
        FROM $this->table
        WHERE $this->arrivaldate BETWEEN :inicio1 AND :fin1
        GROUP BY dia

        UNION ALL

        SELECT DATE($this->departuredate) AS dia, 0 AS ingresos, COUNT(*) AS egresos
        FROM $this->table
        WHERE $this->departuredate BETWEEN :inicio2 AND :fin2
        GROUP BY dia
      ) AS movimientos
      GROUP BY dia
      ORDER BY dia ASC
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':inicio1', $inicioCompleto);
    $stmt->bindParam(':fin1', $finCompleto);
    $stmt->bindParam(':inicio2', $inicioCompleto);
    $stmt->bindParam(':fin2', $finCompleto);
    $stmt->execute();

    $data = [];
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
      if ($row['total_ingresos'] > 0 && $row['total_egresos'] > 0) {
        $data[] = [
          'Fecha'    => date('d-m-y', strtotime($row['dia'])),
          'Ingresos' => (int) $row['total_ingresos'],
          'Egresos'  => (int) $row['total_egresos']
        ];
      }
    }

    return json_encode($data);
  }

  public function getTableContainer()
  {
    $ship      = new ship();
    $user      = new user();
    $adminEdit = $user->isAdminEdit($_SESSION["user"]["run"]);
    $count     = 0;

    /* Filtros */
    $filterNave     = isset($_POST['nave']) ? $_POST['nave'] : '-';
    $filterPatente  = isset($_POST['patente']) ? $_POST['patente'] : '-';
    $filterGuia     = isset($_POST['guia']) ? trim($_POST['guia']) : '';
    $filterDivision = $_SESSION['user']['division'];
    $filterCliente  = $_SESSION['user']['run'];

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 1"];
    $params     = [];

    if ($filterNave !== '-') {
      $conditions[]    = "sh.ship_id = :nave";
      $params[':nave'] = $filterNave;
    }

    if ($filterPatente !== '-') {
      $conditions[]       = "$this->carplate = :patente";
      $params[':patente'] = $filterPatente;
    }

    if ($filterGuia !== '') {
      $conditions[]    = "$this->guide LIKE :guia";
      $params[':guia'] = "%$filterGuia%";
    }

    /* División Naviera para Marval (Cool Carriers) */
    if ($filterDivision === 'shipper' && $filterCliente === '96.591.730-6') {
      $conditions[]   = "sl.rut = :rut";
      $params[':rut'] = $filterCliente;
    }

    /* División Naviera para Seatrade */
    if ($filterDivision === 'shipper' && $filterCliente === '77.897.180-1') {
      $conditions[]   = "sl.rut = :rut";
      $params[':rut'] = $filterCliente;
    }

    $whereClause = implode(' AND ', $conditions);

    /* Contador de registros */
    $countQuery = "SELECT COUNT(*) FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0";
    $countStmt  = $this->db->prepare($countQuery);
    $countStmt->execute($params);
    $totalRegistros = $countStmt->fetchColumn();

    /* Construccion total de la página y query */
    $porPagina = 25; /* Número de registros por página */
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;

    if ($_SESSION["user"]["division"] === 'ssl') {
      $urlBase = generateMkey('enter_container_port', 'mySSL') . '&page=';
    } else {
      $urlBase = generateMkey('enter_container_port', 'myPortal') . '&page=';
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC LIMIT :inicio, :porPagina";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
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
                  <label for='nave' class='text-gray-800 font-weight-bold'>Motonave</label>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'>
                    <option value='-'>Seleccione una nave...</option>
                  </select>
                </div>

                <div class='col-sm-4'>
                  <label for='patente' class='text-gray-800 font-weight-bold'>Patente</label>
                  <select class='form-control select2 form-control-user' id='patente' name='patente'>
                    <option value='-'>Seleccione una patente...</option>
                  </select>
                </div>

                <div class='col-sm-4'>
                  <label for='guia' class='text-gray-800 font-weight-bold'>N° de Guía</label>
                  <input type='text' id='guia' name='guia' class='form-control' placeholder='N° de Guía' value='" . htmlspecialchars($filterGuia) . "'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user' style='margin-right:0.5%;'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
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
    $thead .= "<th>Digitado Por</th>";
    $thead .= $_SESSION["user"]["division"] == 'ssl' ? "<th>Acciones</th>" : null;
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->arrivaldate]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<i class='fas fa-solid fa-exclamation-triangle text-danger'> " . $data[$this->comodity] . "</i>";
        } else {
          $comodity = "<i class='fas fa-solid fa-check text-success'> " . $data[$this->comodity] . "</i>";
        }

        if ($data[$this->departuredate] != null) {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = '<em>Sin hora de salida.</em>';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
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
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
          $stayTime = 'No disponible.';
        }

        $btnAddContainerHour = "<button type='button' class='btn btn-success btn-user btn-sm' onclick='editContainerHour(" . $data[$this->id] . ")'><i class='fas fa-solid fa-clock'></i> Salida</button>";
        $btnEdit             = $adminEdit ? "<button id='editcontainer' type='button' class='btn btn-sm btn-warning btn-user' onclick='editContainer(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pencil'></i> Editar</button>" : null;
        $btnDelete           = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deleteTruck(" . $data[$this->id] . ")'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";
        $btnCellphone        = $_SESSION["user"]["division"] === 'ssl' ? "<button type='button' class='btn btn-success btn-user btn-sm px-2 py-1' title='Llamar a +56{$data[$this->cellphonedriver]}' style='width:30px; height:30px;' onclick=\"window.location.href='tel:+56{$data[$this->cellphonedriver]}'\"><i class='fas fa-solid fa-phone'></i></button>" : null;

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->countervessel] . "</td>";
        $tr .= "<td>" . $ship->getVesselName($data[$this->vessel]) . "</td>";
        $tr .= "<td>" . $data[$this->carplate] . "</td>";
        $tr .= "<td>" . $data[$this->guide] . "</td>";
        $tr .= "<td>" . $data[$this->container] . "</td>";
        $tr .= "<td>" . $data[$this->seal] . "</td>";
        $tr .= "<td>" . $data[$this->exporter] . "</td>";
        $tr .= "<td>" . $data[$this->agency] . "</td>";
        $tr .= "<td>" . $data[$this->pallets] . "</td>";
        $tr .= "<td>" . $btnCellphone . ' ' . $data[$this->cellphonedriver] . "</td>";
        $tr .= "<td>" . $arrival . "</td>";
        $tr .= "<td>" . $departure . "</td>";
        $tr .= "<td>" . $stayTime . "</td>";
        $tr .= "<td>" . $comodity . "</td>";
        $tr .= "<td>" . $data[$this->booking] . "</td>";
        $tr .= "<td>" . $data[$this->stay] . "</td>";
        $tr .= "<td>" . $data[$this->observations] . "</td>";
        $tr .= "<td>" . $created . "</td>";
        $tr .= "<td>" . $this->findByUser($data[$this->createdby]) . "</td>";
        $tr .= $_SESSION["user"]["division"] == 'ssl' ? "<td>" . $btnAddContainerHour . ' ' . $btnEdit . ' ' . $btnDelete . "</td>" : null;
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= $_SESSION["user"]["division"] == 'ssl' ? "<td colspan='20' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>" : "<td colspan='19' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = $form . "
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Contenedores <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
              <table class='table table-bordered table-hover' style='width:max-content;'>
                " . $thead . $tr . $tbclose . "
              </table>
            </div>
          </div>
          " . $this->paginate($totalRegistros, $porPagina, $pagina, $urlBase) . "
        </div>
      </div>
    ";

    return $table;
  }

  public function downloadTableContainerExcel($nave = '', $patente = '', $guia = '', $division = '', $cliente = '')
  {
    $ship = new ship();

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

    /* División Naviera para Marval (Cool Carriers) */
    if ($division === 'shipper' && $cliente === '96.591.730-6') {
      $where .= " AND sl.rut = ?";
      $filtros[] = "$cliente";
    }

    /* División Naviera para Seatrade */
    if ($division === 'shipper' && $cliente === '77.897.180-1') {
      $where .= " AND sl.rut = ?";
      $filtros[] = "$cliente";
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id $where AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* Crear Excel */
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Contenedores');

    /* Encabezados */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Contenedor', 'Sello', 'Exportador', 'Agencia', 'Pallets', 'Teléfono', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Digitado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Agregar los datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->arrivaldate]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created   = $createdTime->format('d-m-Y H:i');
      $arrival   = $arrivalTime->format('d-m-Y H:i');
      $departure = $data[$this->departuredate] != null ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';
      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->countervessel],
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
    $ship      = new ship();
    $user      = new user();
    $adminEdit = $user->isAdminEdit($_SESSION["user"]["run"]);
    $count     = 0;

    /* Filtros */
    $filterNave     = isset($_POST['nave']) ? $_POST['nave'] : '-';
    $filterPatente  = isset($_POST['patente']) ? $_POST['patente'] : '-';
    $filterGuia     = isset($_POST['guia']) ? trim($_POST['guia']) : '';
    $filterDivision = $_SESSION['user']['division'];
    $filterCliente  = $_SESSION['user']['run'];

    /* Construir cláusulas WHERE dinámicamente */
    $conditions = ["$this->origin = 2"];
    $params     = [];

    if ($filterNave !== '-') {
      $conditions[]    = "sh.ship_id = :nave";
      $params[':nave'] = $filterNave;
    }

    if ($filterPatente !== '-') {
      $conditions[]       = "$this->carplate = :patente";
      $params[':patente'] = $filterPatente;
    }

    if ($filterGuia !== '') {
      $conditions[]    = "$this->guide LIKE :guia";
      $params[':guia'] = "%$filterGuia%";
    }

    /* División Naviera para Marval (Cool Carriers) */
    if ($filterDivision === 'shipper' && $filterCliente === '96.591.730-6') {
      $conditions[]   = "sl.rut = :rut";
      $params[':rut'] = $filterCliente;
    }

    /* División Naviera para Seatrade */
    if ($filterDivision === 'shipper' && $filterCliente === '77.897.180-1') {
      $conditions[]   = "sl.rut = :rut";
      $params[':rut'] = $filterCliente;
    }

    $whereClause = implode(' AND ', $conditions);

    /* Contador de registros */
    $countQuery = "SELECT COUNT(*) FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0";
    $countStmt  = $this->db->prepare($countQuery);
    $countStmt->execute($params);
    $totalRegistros = $countStmt->fetchColumn();

    /* Construccion total de la página y query */
    $porPagina = 25; /* Número de registros por página */
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;

    if ($_SESSION["user"]["division"] === 'ssl') {
      $urlBase = generateMkey('enter_thermo_port', 'mySSL') . '&page=';
    } else {
      $urlBase = generateMkey('enter_thermo_port', 'myPortal') . '&page=';
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC LIMIT :inicio, :porPagina";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
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
                  <label for='nave' class='text-gray-800 font-weight-bold'>Motonave</label>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'>
                    <option value='-'>Seleccione una nave...</option>
                  </select>
                </div>

                <div class='col-sm-4'>
                  <label for='patente' class='text-gray-800 font-weight-bold'>Patente</label>
                  <select class='form-control select2 form-control-user' id='patente' name='patente'>
                    <option value='-'>Seleccione una patente...</option>
                  </select>
                </div>

                <div class='col-sm-4'>
                  <label for='guia' class='text-gray-800 font-weight-bold'>N° de Guía</label>
                  <input type='text' name='guia' class='form-control' placeholder='N° de Guía' value='" . htmlspecialchars($filterGuia) . "'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user' style='margin-right:0.5%;'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Posición</th>";
    $thead .= "<th>Nave</th>";
    $thead .= "<th>Patente</th>";
    $thead .= "<th>Guía</th>";
    $thead .= "<th>Exportador</th>";
    $thead .= "<th>Pallets</th>";
    $thead .= "<th>Teléfono</th>";
    $thead .= "<th>Entrada</th>";
    $thead .= "<th>Salida</th>";
    $thead .= "<th>Tiempo de Estadia</th>";
    $thead .= "<th>Condición</th>";
    $thead .= "<th>Booking</th>";
    $thead .= "<th>Estadía</th>";
    $thead .= "<th>Obersvaciones</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Digitado Por</th>";
    $thead .= $_SESSION["user"]["division"] == 'ssl' ? "<th>Acciones</th>" : null;
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->arrivaldate]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<i class='fas fa-solid fa-exclamation-triangle text-danger'> " . $data[$this->comodity] . "</i>";
        } else {
          $comodity = "<i class='fas fa-solid fa-check text-success'> " . $data[$this->comodity] . "</i>";
        }

        if ($data[$this->departuredate] != null) {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = '<em>Sin hora de salida.</em>';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
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
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
          $stayTime = 'No disponible.';
        }

        $btnAddThermoHour = "<button type='button' class='btn btn-success btn-user btn-sm' onclick='editTermoHour(" . $data[$this->id] . ")'><i class='fas fa-solid fa-clock'></i> Salida</button>";
        $btnEdit          = $adminEdit ? "<button id='editcontainer' type='button' class='btn btn-sm btn-warning btn-user' onclick='editThermo(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pencil'></i> Editar</button>" : null;
        $btnDelete        = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deleteTruck(" . $data[$this->id] . ")'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";
        $btnCellphone     = $_SESSION["user"]["division"] === 'ssl' ? "<button type='button' class='btn btn-success btn-user btn-sm px-2 py-1' title='Llamar a +56{$data[$this->cellphonedriver]}' style='width:30px; height:30px;' onclick=\"window.location.href='tel:+56{$data[$this->cellphonedriver]}'\"><i class='fas fa-solid fa-phone'></i></button>" : null;

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->countervessel] . "</td>";
        $tr .= "<td>" . $ship->getVesselName($data[$this->vessel]) . "</td>";
        $tr .= "<td>" . $data[$this->carplate] . "</td>";
        $tr .= "<td>" . $data[$this->guide] . "</td>";
        $tr .= "<td>" . $data[$this->exporter] . "</td>";
        $tr .= "<td>" . $data[$this->pallets] . "</td>";
        $tr .= "<td>" . $btnCellphone . ' ' . $data[$this->cellphonedriver] . "</td>";
        $tr .= "<td>" . $arrival . "</td>";
        $tr .= "<td>" . $departure . "</td>";
        $tr .= "<td style='width:350px;'>" . $stayTime . "</td>";
        $tr .= "<td>" . $comodity . "</td>";
        $tr .= "<td>" . $data[$this->booking] . "</td>";
        $tr .= "<td>" . $data[$this->stay] . "</td>";
        $tr .= "<td>" . $data[$this->observations] . "</td>";
        $tr .= "<td>" . $created . "</td>";
        $tr .= "<td>" . $this->findByUser($data[$this->createdby]) . "</td>";
        $tr .= $_SESSION["user"]["division"] == 'ssl' ? "<td>" . $btnAddThermoHour . ' ' . $btnEdit . ' ' . $btnDelete . "</td>" : null;
        $tr .= "</tr>";

        $count++;
      }
    } else {
      $tr .= "<tr>";
      $tr .= $_SESSION["user"]["division"] == 'ssl' ? "<td colspan='17' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>" : "<td colspan='16' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
      $tr .= "</tr>";
    }

    $tbclose = "</tbody>";

    $table = $form . "
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Termos <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
              <table class='table table-bordered table-hover' style='width:max-content;'>
                " . $thead . $tr . $tbclose . "
              </table>
            </div>
          </div>
          " . $this->paginate($totalRegistros, $porPagina, $pagina, $urlBase) . "
        </div>
      </div>
    ";

    return $table;
  }

  public function downloadTableThermoExcel($nave = '', $patente = '', $guia = '', $division = '', $cliente = '')
  {
    $ship = new ship();

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

    /* División Naviera para Marval (Cool Carriers) */
    if ($division === 'shipper' && $cliente === '96.591.730-6') {
      $where .= " AND sl.rut = ?";
      $filtros[] = $cliente;
    }

    /* División Naviera para Seatrade */
    if ($division === 'shipper' && $cliente === '77.897.180-1') {
      $where .= " AND sl.rut = ?";
      $filtros[] = $cliente;
    }

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id $where AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Termos');

    /* Encabezados del Excel */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Exportador', 'Pallets', 'Teléfono', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Digitado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Filas de datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->arrivaldate]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created = $createdTime->format('d-m-Y H:i');
      $arrival = $arrivalTime->format('d-m-Y H:i');

      $departure = $data[$this->departuredate] != null ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->countervessel],
        $ship->getVesselName($data[$this->vessel]),
        $data[$this->carplate],
        $data[$this->guide],
        $data[$this->exporter],
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
    $ship  = new ship();
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

    /* Contador de registros */
    $countQuery = "SELECT COUNT(*) FROM $this->table as p JOIN app_ships as sh ON sh.ship_id = p.vessel_id WHERE $whereClause";
    $countStmt  = $this->db->prepare($countQuery);
    $countStmt->execute($params);
    $totalRegistros = $countStmt->fetchColumn();

    /* Construccion total de la página y query */
    $porPagina = 35; /* Número de registros por página */
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;
    $urlBase   = generateMkey('ship_report') . '&page=';

    $query = "SELECT * FROM $this->table as p JOIN app_ships as sh ON sh.ship_id = p.vessel_id WHERE $whereClause ORDER BY p.counter_vessel ASC, p.vessel_id ASC LIMIT :inicio, :porPagina";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
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
                  <label for='nave' class='text-gray-800 font-weight-bold'>Motonave</label>
                  <select class='form-control select2 form-control-user' id='nave' name='nave'></select>
                </div>
                <div class='col-sm-3'>
                  <label for='tipo' class='text-gray-800 font-weight-bold'>Tipo de Carga</label>
                  <select class='form-control select2 form-control-user' id='tipo' name='tipo'>
                    <option value='-' selected>Seleccione una tipo de carga...</option>
                    <option value='1'>Contenedores</option>
                    <option value='2'>Termos</option>
                  </select>
                </div>
                <div class='col-sm-3'>
                  <label for='desde' class='text-gray-800 font-weight-bold'>Desde</label>
                  <input type='date' class='form-control form-control-user' id='desde' name='desde'>
                </div>
                <div class='col-sm-3'>
                  <label for='hasta' class='text-gray-800 font-weight-bold'>Hasta</label>
                  <input type='date' class='form-control form-control-user' id='hasta' name='hasta'>
                </div>
              </div>

              <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-sm btn-primary btn-user' style='margin-right:0.5%;'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['tipo'] ?? "") . "', '" . ($_POST['desde'] ?? "") . "', '" . ($_POST['hasta'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
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
    $thead .= "<th>Digitado Por</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = $stayTime = null;

    if ($result !== []) {
      foreach ($result as $data) {
        $attr = null;

        $createdTime = new DateTime($data[$this->arrivaldate]);
        $arrivalTime = new DateTime($data[$this->arrivaldate]);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data[$this->comodity] == 'USDA' || $data[$this->comodity] == 'System Approach') {
          $comodity = "<button type='button' class='btn btn-danger btn-user btn-sm'><i class='fas fa-solid fa-exclamation-triangle'></i> " . $data[$this->comodity] . "</button>";
        } else {
          $comodity = "<button type='button' class='btn btn-success btn-user btn-sm'><i class='fas fa-solid fa-check'></i> " . $data[$this->comodity] . "</button>";
        }

        if ($data[$this->departuredate] != null) {
          $departure = (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i');
        } else {
          $departure = 'Sin hora de salida.';
        }

        if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
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
        } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
          $stayTime = 'No disponible.';
        }

        $tr .= "<tr " . $attr . ">";
        $tr .= "<td>" . $data[$this->countervessel] . "</td>";
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
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Cargas <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
                <table class='table table-bordered table-hover' style='width:max-content;'>
                " . $thead . $tr . $tbclose . "
              </table>
            </div>
          </div>
          " . $this->paginate($totalRegistros, $porPagina, $pagina, $urlBase) . "
        </div>
      </div>
    ";

    return $table;
  }

  public function downloadTableShipReport($nave = '', $tipo = '', $desde = '', $hasta = '')
  {
    $ship = new ship();

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

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    $stmt->execute($filtros);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Listado de Termos');

    /* Encabezados del Excel */
    $headers = [
      'Posición', 'Nave', 'Patente', 'Guía', 'Exportador', 'Pallets', 'Entrada', 'Salida', 'Tiempo de Estadía', 'Condición', 'Booking', 'Estadía', 'Observaciones', 'Creado', 'Digitado Por'
    ];
    $sheet->fromArray($headers, null, 'A1');

    /* Filas de datos */
    $row      = 2;
    $stayTime = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->arrivaldate]);
      $arrivalTime = new DateTime($data[$this->arrivaldate]);

      $created = $createdTime->format('d-m-Y H:i');
      $arrival = $arrivalTime->format('d-m-Y H:i');

      $departure = $data[$this->departuredate] != null ? (new DateTime($data[$this->departuredate]))->format('d-m-Y H:i') : 'Sin hora de salida.';

      if ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] != null) {
        $arrivalDate   = new DateTime($data[$this->arrivaldate]);
        $departureDate = new DateTime($data[$this->departuredate]);

        $interval = $arrivalDate->diff($departureDate);
        $days     = $interval->format('%d');
        $hours    = $interval->format('%h');
        $minutes  = $interval->format('%i');

        $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';

      } elseif ($data[$this->arrivaldate] != '0000-00-00 00:00:00' && $data[$this->departuredate] == null) {
        $stayTime = 'No disponible.';
      }

      $sheet->fromArray([
        $data[$this->countervessel],
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

  public function getLastSentTrucks()
  {
    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE departure_date IS NOT NULL ORDER BY row_id DESC LIMIT 5";
    $stmt  = $this->db->prepare($query);
    $stmt->execute();
    $result     = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $infoVessel = null;

    foreach ($result as $info) {
      $position = $info[$this->countervessel];
      $carplate = $info[$this->carplate];
      $vessel   = $info['vessel_name'];

      $infoVessel .= '#' . htmlspecialchars($position) . ' / ' . '<b> Patente: </b>' . htmlspecialchars($carplate) . ' / ' . '<b>Nave: </b>' . htmlspecialchars($vessel) . '<br>';
    }

    return $infoVessel;
  }

  public function getDetailByVessel(int $vesselId): string
  {
    $sql = "
      SELECT
        row_id,
        car_plate,
        container,
        pallets_quantity,
        origin,
        exporter,
        agency,
        guide_number
      FROM {$this->table}
      WHERE vessel_id = :vessel_id
      ORDER BY row_id ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':vessel_id', $vesselId, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
      return "<div class='text-center text-muted py-3'>Sin detalle para esta nave</div>";
    }

    $html = "<div class='table-responsive'>";
    $html .= "<table class='table table-bordered table-hover'>";
    $html .= "
      <thead style='background:#4e73df;color:white'>
        <tr>
          <th style='width:40px'>#</th>
          <th>Camión</th>
          <th>Exportador</th>
          <th>Agencia</th>
          <th>N° Guía</th>
          <th>Contenedor</th>
          <th class='text-end'>Pallets</th>
          <th>Origen</th>
        </tr>
      </thead>
      <tbody>
    ";

    $i     = 1;
    $total = 0;

    foreach ($rows as $r) {
      $carPlate  = htmlspecialchars($r['car_plate'] ?? '');
      $exporter  = htmlspecialchars($r['exporter'] ?? '');
      $agency    = htmlspecialchars($r['agency'] ?? '');
      $guide     = htmlspecialchars($r['guide_number'] ?? '');
      $container = htmlspecialchars($r['container'] ?? '');
      $pallets   = (int) ($r['pallets_quantity'] ?? 0);
      $origin    = (int) ($r['origin'] ?? 0);

      $total += $pallets;

      $originText = $origin === 1 ? 'Contenedor' : 'Pallets';

      $html .= "
        <tr>
          <td class='text-center'>{$i}</td>
          <td style='max-width:120px;word-break:break-word;'>{$carPlate}</td>
          <td style='max-width:180px;word-break:break-word;'>{$exporter}</td>
          <td style='max-width:160px;word-break:break-word;'>{$agency}</td>
          <td style='max-width:120px;word-break:break-word;'>{$guide}</td>
          <td style='max-width:160px;word-break:break-word;'>{$container}</td>
          <td class='text-end'>{$pallets}</td>
          <td style='max-width:120px;word-break:break-word;'>{$originText}</td>
        </tr>
      ";

      $i++;
    }

    $html .= "
      </tbody>
      <tfoot class='table-light'>
        <tr>
          <th colspan='6' class='text-end' style='text-align:end;'>Total:</th>
          <th class='text-end'>" . number_format($total, 0, ',', '.') . "</th>
          <th></th>
        </tr>
      </tfoot>
    ";

    $html .= "</table></div>";

    return $html;
  }

  public function getTableStadisticsByShips()
  {
    $ship = new ship();
    $port = new port();

    /* Paginación */
    $countStmt      = $this->db->query("SELECT COUNT(*) FROM app_ships WHERE finished = 1");
    $totalRegistros = $countStmt->fetchColumn();

    $porPagina = 25;
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;
    $urlBase   = generateMkey('stadistics_by_vessel') . '&page=';

    $query = "
      SELECT
        op.vessel_id,
        sh.pol,
        sh.pod,
        sh.eta,
        sh.etd,
        sh.ship_line,
        sh.finished_date,
        sh.voyage,
        SUM(CASE WHEN op.origin = 1 THEN 1 ELSE 0 END) AS total_containers,
        SUM(CASE WHEN op.origin = 2 THEN op.pallets_quantity ELSE 0 END) AS total_pallets,
        COUNT(op.row_id) AS total_camiones
      FROM $this->table op
      JOIN app_ships sh ON op.vessel_id = sh.ship_id
      WHERE sh.finished = 1
      GROUP BY op.vessel_id, sh.pol, sh.pod, sh.ship_line
      ORDER BY sh.ship_id ASC
      LIMIT :inicio, :porPagina
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rows      = $style      = '';
    $detailsJs = [];
    $i         = 0;

    foreach ($result as $data) {
      $i++;
      $vid = (int) $data['vessel_id'];

      $eta = (new DateTime($data['eta']))->format('d-m-Y H:i');
      $etd = (new DateTime($data['etd']))->format('d-m-Y H:i');
      $fin = (new DateTime($data['finished_date']))->format('d-m-Y H:i');

      $diff      = (new DateTime($data['eta']))->diff(new DateTime($data['etd']));
      $turnos    = ceil((($diff->days * 24) + $diff->h) / 8);
      $dias      = $turnos / 3;
      $totalCnts = number_format($data['total_containers']);
      $totalPlts = number_format($data['total_pallets']);

      $cntClass = ($data['total_containers'] > 0) ? 'text-success' : 'text-danger';
      $pltClass = ($data['total_pallets'] > 0) ? 'text-success' : 'text-danger';

      $vessel   = $ship->getVesselName($vid);
      $shipLine = $ship->getShipLineName($data['ship_line']);

      $polFlag = $port->getflagImage($port->getCountryName($data['pol']));
      $polName = $port->getPortName($data['pol']);

      $podFlag = $port->getflagImage($port->getCountryName($data['pod']));
      $podName = $port->getPortName($data['pod']);

      $rows .= "
        <tr>
          <td>{$i}</td>
          <td>{$vessel}</td>
          <td>{$shipLine}</td>
          <td>{$polFlag} {$polName}</td>
          <td>{$podFlag} {$podName}</td>
          <td>{$eta}</td>
          <td>{$etd}</td>
          <td><b class='text-success'>{$turnos}</b></td>
          <td>" . number_format($dias, 0, ',', '.') . "</td>
          <td><b>{$fin}</b></td>
          <td><b>" . number_format($data['total_camiones']) . "</b></td>
          <td class='{$cntClass}'><b>{$totalCnts}</b></td>
          <td class='{$pltClass}'><b>{$totalPlts}</b></td>
          <td class='text-center'>
            <button class='btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#detailModal' onclick='loadDetail({$vid})'><i class='fas fa-eye'></i> Detalles</button>
          </td>
        </tr>
      ";

      $detailsJs[$vid] = $this->getDetailByVessel($vid);
      $style           = "style='width:max-content'";
    }

    return "
      <div class='card shadow'>
        <div class='table-responsive'>
          <table class='table table-bordered table-hover' $style>
            <thead style='background:#4e73df;color:white'>
              <tr>
                <th>#</th>
                <th>Nave</th>
                <th>Naviera</th>
                <th>POL</th>
                <th>POD</th>
                <th>ETA</th>
                <th>ETD</th>
                <th>Turnos</th>
                <th>Días</th>
                <th>Finalizado</th>
                <th>Camiones</th>
                <th>Contenedores</th>
                <th>Pallets</th>
                <th>Detalle</th>
              </tr>
            </thead>
            <tbody>$rows</tbody>
          </table>
        </div>
      </div>

      {$this->paginate($totalRegistros, $porPagina, $pagina, $urlBase)}

      <!-- Modal -->
      <div class='modal fade' id='detailModal' tabindex='-1'>
        <div class='modal-dialog modal-xl modal-dialog-scrollable'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title'>Detalle de Carga </br>
                Nave: {$ship->getVesselName($vid)} | Viaje: {$data['voyage']}
              </h5>
              <button type='button' class='close' data-bs-dismiss='modal' aria-label='Cerrar'>
              <span>×</span>
            </div>
            <div class='modal-body' id='modalDetailBody'></div>
          </div>
        </div>
      </div>

      <script>
        const vesselDetails = " . json_encode($detailsJs) . ";
        function loadDetail(id){
          document.getElementById('modalDetailBody').innerHTML = vesselDetails[id] ?? 'Sin datos';
        }
      </script>
    ";
  }

  public function shiftsReport($shifts, $dateStart, $dateEnd)
  {
    $ship = new ship();
    $port = new port();

    list($inicio, $fin) = array_map('trim', explode(' - ', $shifts));
    $inicioDatetime     = $dateStart . ' ' . $inicio . ':00';
    $finDatetime        = $dateEnd . ' ' . $fin . ':00';
    $rows               = $style               = $stayTime               = $status               = '';
    $totalPallets       = $totalCamiones       = 0;

    $sql = "SELECT
      op.counter_vessel,
      op.car_plate,
      op.guide_number,
      op.container,
      op.seal_number,
      op.exporter,
      op.agency,
      op.pallets_quantity,
      op.arrival_date,
      op.departure_date,
      op.created,
      op.created_by,
      op.origin,
      sh.ship_id,
      sh.pol,
      sh.pod,
      sh.eta,
      sh.etd,
      sh.ship_line,
      sh.voyage
    FROM $this->table op
    JOIN app_ships sh ON op.vessel_id = sh.ship_id
    WHERE op.created BETWEEN :inicio AND :fin
    ORDER BY $this->countervessel ASC";

    $list = parent::findAllStatic($sql, ['inicio' => $inicioDatetime, 'fin' => $finDatetime]);
    if ($list->length()) {
      foreach ($list->getCollection() as $data) {
        $vessel   = $ship->getVesselName($data['ship_id']);
        $shipLine = $ship->getShipLineName($data['ship_line']);

        $polFlag = $port->getflagImage($port->getCountryName($data['pol']));
        $polName = $port->getPortName($data['pol']);

        $podFlag = $port->getflagImage($port->getCountryName($data['pod']));
        $podName = $port->getPortName($data['pod']);

        $origin     = (int) ($data['origin'] ?? 0);
        $originText = $origin === 1 ? 'Contenedor' : 'Pallets';

        $createdTime = new DateTime($data['created']);
        $arrivalTime = new DateTime($data['arrival_date']);

        $created = $createdTime->format('d-m-Y H:i');
        $arrival = $arrivalTime->format('d-m-Y H:i');

        if ($data['departure_date'] != null) {
          $departure = (new DateTime($data['departure_date']))->format('d-m-Y H:i');
        } else {
          $departure = '<em>Sin hora de salida.</em>';
        }

        if ($data['arrival_date'] != '0000-00-00 00:00:00' && $data['departure_date'] != null) {
          $arrivalDate   = new DateTime($data['arrival_date']);
          $departureDate = new DateTime($data['departure_date']);

          $interval = $arrivalDate->diff($departureDate);
          $days     = $interval->format('%d');
          $hours    = $interval->format('%h');
          $minutes  = $interval->format('%i');

          $stayTime = ($days <= 1 ? $days . ' día con ' : $days . ' días con ') . $hours . ' horas y ' . $minutes . ' minutos';
        } elseif ($data['arrival_date'] != '0000-00-00 00:00:00' && $data['departure_date'] == null) {
          $stayTime = 'No disponible.';
        }

        if ($data['arrival_date'] !== '0000-00-00 00:00:00' && $data['departure_date'] === null) {
          $status = "<i class='fas fa-arrow-up text-success'> Ingreso</i>";
        } elseif ($data['arrival_date'] !== '0000-00-00 00:00:00' && $data['departure_date'] !== null) {
          $status = "<i class='fas fa-arrow-down text-danger'> Egreso</i>";
        }

        $rows .= "
          <tr>
            <td>{$data['counter_vessel']}</td>
            <td>{$status}</td>
            <td>{$originText}</td>
            <td>{$data['car_plate']}</td>
            <td>{$data['guide_number']}</td>
            <td>{$data['container']}</td>
            <td>{$data['seal_number']}</td>
            <td>{$data['exporter']}</td>
            <td>{$data['agency']}</td>
            <td>{$data['pallets_quantity']}</td>
            <td>{$vessel}</td>
            <td>{$shipLine}</td>
            <td>{$polFlag} {$polName}</td>
            <td>{$podFlag} {$podName}</td>
            <td>{$arrival}</td>
            <td>{$departure}</td>
            <td>{$stayTime}</td>
            <td>{$this->findByUser($data['created_by'])}</td>
          </tr>
        ";

        $style = "style='width:max-content'";
        $totalPallets += (int) $data['pallets_quantity'];
        $totalCamiones++;
      }

      $rows .= "
        <tr style='font-weight:bold;background:#f8f9fc'>
          <td colspan='12' class='text-right'>Totales</td>
          <td>" . number_format($totalPallets, 0, ',', '.') . "</td>
          <td colspan='5'>Camiones: " . number_format($totalCamiones, 0, ',', '.') . "</td>
        </tr>
      ";
    } else {
      $rows .= "
        <tr>
          <td colspan='18' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>
        </tr>
      ";
    }

    return "
      <div class='card shadow'>
        <div class='table-responsive'>
          <table class='table table-bordered table-hover' $style>
            <thead style='background:#4e73df;color:white'>
              <tr>
                <th>#</th>
                <th>Estado</th>
                <th>Origen</th>
                <th>Patente</th>
                <th>N° Guia</th>
                <th>Contenedor</th>
                <th>Sello</th>
                <th>Exportador</th>
                <th>Agencia</th>
                <th>Pallets</th>
                <th>Nave</th>
                <th>Linea</th>
                <th>POL</th>
                <th>POD</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Estadía</th>
                <th>Ingresado Por</th>
              </tr>
            </thead>
            <tbody>$rows</tbody>
          </table>
        </div>
      </div>
    ";
  }

}
