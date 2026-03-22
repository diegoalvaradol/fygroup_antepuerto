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

  public function updateDepartureDate()
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

    $where = ["p.departure_date IS NULL"];
    $joins = "";

    if (!$admin) {
      $division = $_SESSION["user"]["division"];
      $run      = $_SESSION["user"]["run"];

      if ($division === 'terminal') {
        $joins .= " JOIN app_ships AS sh ON sh.ship_id = p.vessel_id";
        $where[] = "sh.finished = 0";
      }

      if ($division === 'shipper' && in_array($run, ['96.591.730-6', '77.897.180-1'])) {
        $joins .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
      ";
        $where[]        = "sh.finished = 0";
        $where[]        = "sl.rut = :rut";
        $params[':rut'] = $run;
      }
    }

    $query .= $joins . " WHERE " . implode(" AND ", $where);

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

    $where = ["p.departure_date IS NULL"];
    $joins = "";

    if (!$admin) {
      $division = $_SESSION["user"]["division"];
      $run      = $_SESSION["user"]["run"];

      if ($division === 'terminal') {
        $joins .= " JOIN app_ships AS sh ON sh.ship_id = p.vessel_id";
        $where[] = "sh.finished = 0";
      }

      if ($division === 'shipper' && in_array($run, ['96.591.730-6', '77.897.180-1'])) {
        $joins .= "
        JOIN app_ships AS sh ON sh.ship_id = p.vessel_id
        JOIN app_ship_lines AS sl ON sl.line_id = sh.ship_line
      ";
        $where[]        = "sh.finished = 0";
        $where[]        = "sl.rut = :rut";
        $params[':rut'] = $run;
      }
    }

    $query .= $joins . " WHERE " . implode(" AND ", $where);

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
    $from   = " FROM $this->table AS p";
    $joins  = "";
    $where  = ["1=1"];
    $params = [];

    if (!$admin) {

      if ($_SESSION["user"]["division"] === 'terminal') {
        $joins .= " JOIN app_ships sh ON sh.ship_id = p.vessel_id";
        $where[] = "sh.finished = 0";
      }

      if ($_SESSION["user"]["division"] === 'shipper') {
        $joins .= " JOIN app_ships sh ON sh.ship_id = p.vessel_id
                  JOIN app_ship_lines sl ON sl.line_id = sh.ship_line";

        $where[]        = "sh.finished = 0";
        $where[]        = "sl.rut = :rut";
        $params[':rut'] = $_SESSION["user"]["run"];
      }
    }

    $whereAll = " WHERE " . implode(' AND ', $where);

    /* Total arribados */
    $sqlTotal = "SELECT COUNT(*) AS total" . $from . $joins . $whereAll;

    /* Antepuerto (sin salida) */
    $sqlAntepuerto = $sqlTotal . " AND p.departure_date IS NULL";

    $stmt = $this->db->prepare($sqlTotal);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v);
    }
    $stmt->execute();
    $totalArrivado = (int) $stmt->fetchColumn();

    $stmt = $this->db->prepare($sqlAntepuerto);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v);
    }
    $stmt->execute();
    $totalAntepuerto = (int) $stmt->fetchColumn();

    return number_format($totalArrivado - $totalAntepuerto, 0, ',', '.');
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

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }

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
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Exportar XLS</button>
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
    $thead .= "<th>Guía(s)</th>";
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
            <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
              <h6 class='mb-0'>
                <i class='fas fa-list'></i> Listado de Contenedores
                <em>(Total: " . $count . ")</em>
              </h6>

              <div style='position:relative; max-width:250px; width:100%;'>
                <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                <input type='text' id='searchContainerTable' placeholder='Buscar por nave, patente, guía...' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
              </div>
            </div>

            <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
              <table id='containerTable' class='table table-hover mb-0' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
                <thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>
                  " . str_replace("<thead style='background-color:#4e73df; color:white;'>", "", $thead) . "
                  " . $tr . $tbclose . "
              </table>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.getElementById('searchContainerTable').addEventListener('keyup', function() {
          let filter = this.value.toLowerCase().trim();
          let rows = document.querySelectorAll('#containerTable tbody tr');

          rows.forEach(row => {
            let text = (
              (row.cells[1]?.innerText || '') + ' ' +
              (row.cells[2]?.innerText || '') + ' ' +
              (row.cells[3]?.innerText || '') + ' ' +
              (row.cells[4]?.innerText || '') + ' ' +
              (row.cells[6]?.innerText || '') + ' ' +
              (row.cells[7]?.innerText || '')
            ).toLowerCase();

            let match = text.includes(filter);

            if (filter.includes(' ')) {
              let words = filter.split(' ');
              match = words.every(w => text.includes(w));
            }

            row.style.display = match ? '' : 'none';
          });
        });
      </script>
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

    $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }
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
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['patente'] ?? "") . "', '" . htmlspecialchars($_POST['guia'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Exportar XLS</button>
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
    $thead .= "<th>Guía(s)</th>";
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
            <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
              <h6 class='mb-0'>
                <i class='fas fa-list'></i> Listado de Termos
                <em>(Total: " . $count . ")</em>
              </h6>

              <div style='position:relative; max-width:250px; width:100%;'>
                <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                <input type='text' id='searchThermoTable' placeholder='Buscar por nave, patente, guía...' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
              </div>
            </div>

            <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
              <table id='thermoTable' class='table table-hover mb-0' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
                <thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>
                  " . str_replace("<thead style='background-color:#4e73df; color:white;'>", "", $thead) . "
                  " . $tr . $tbclose . "
              </table>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.getElementById('searchThermoTable').addEventListener('keyup', function() {
          let filter = this.value.toLowerCase().trim();
          let rows = document.querySelectorAll('#thermoTable tbody tr');

          rows.forEach(row => {
            let text = (
              (row.cells[1]?.innerText || '') + ' ' +
              (row.cells[2]?.innerText || '') + ' ' +
              (row.cells[3]?.innerText || '') + ' ' +
              (row.cells[4]?.innerText || '')
            ).toLowerCase();

            let match = text.includes(filter);

            if (filter.includes(' ')) {
              let words = filter.split(' ');
              match = words.every(w => text.includes(w));
            }

            row.style.display = match ? '' : 'none';
          });
        });
      </script>
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

    $query = "SELECT * FROM $this->table as p JOIN app_ships as sh ON sh.ship_id = p.vessel_id WHERE $whereClause ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
    $stmt  = $this->db->prepare($query);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }
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
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? "") . "', '" . htmlspecialchars($_POST['tipo'] ?? "") . "', '" . ($_POST['desde'] ?? "") . "', '" . ($_POST['hasta'] ?? "") . "')" . "\"><i class='fas fa-solid fa-download'></i> Exportar XLS</button>
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
    $thead .= "<th>Guía(s)</th>";
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
            <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
              <h6 class='mb-0'>
                <i class='fas fa-list'></i> Listado
                <em>(Total: " . $count . ")</em>
              </h6>

              <div style='position:relative; max-width:250px; width:100%;'>
                <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                <input type='text' id='searchShipReportTable' placeholder='Buscar por nave, patente, guía...' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
              </div>
            </div>

            <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
              <table id='shipReportTable' class='table table-hover mb-0' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>

                <thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>
                  " . str_replace("<thead style='background-color:#4e73df; color:white;'>", "", $thead) . "
                  " . $tr . $tbclose . "
              </table>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.getElementById('searchShipReportTable').addEventListener('keyup', function() {
          let filter = this.value.toLowerCase().trim();
          let rows = document.querySelectorAll('#shipReportTable tbody tr');

          rows.forEach(row => {
            let text = (
              (row.cells[1]?.innerText || '') + ' ' +
              (row.cells[2]?.innerText || '') + ' ' +
              (row.cells[3]?.innerText || '') + ' ' +
              (row.cells[4]?.innerText || '')
            ).toLowerCase();

            let match = text.includes(filter);

            if (filter.includes(' ')) {
              let words = filter.split(' ');
              match = words.every(w => text.includes(w));
            }

            row.style.display = match ? '' : 'none';
          });
        });
      </script>
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
    $html .= "<table id='detailTable' class='table table-hover'>";
    $html .= "
      <thead style='background:#4e73df;color:white'>
        <tr>
          <th>#</th>
          <th>Camión</th>
          <th>Exportador</th>
          <th>Agencia</th>
          <th>Guía(s)</th>
          <th>Contenedor</th>
          <th>Pallets</th>
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
          <td>{$i}</td>
          <td>{$carPlate}</td>
          <td>{$exporter}</td>
          <td>{$agency}</td>
          <td>{$guide}</td>
          <td>{$container}</td>
          <td>{$pallets}</td>
          <td>{$originText}</td>
        </tr>
      ";

      $i++;
    }

    $html .= "
      </tbody>
      <tfoot>
        <tr>
          <th colspan='6'>Total</th>
          <th>" . number_format($total, 0, ',', '.') . "</th>
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

    $query = "
      SELECT
        op.vessel_id,
        sh.pol,
        sh.pod,
        sh.eta,
        sh.etd,
        sh.ship_line,
        sh.voyage,
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
    ";

    $stmt = $this->db->prepare($query);
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
          <td>{$data['voyage']}</td>
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
    }

    return "
      <div class='card shadow mb-4'>
        <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2'>
          <h6 class='mb-0'>
            <i class='fas fa-chart-bar me-1'></i> Estadísticas por Nave
            <small class='opacity-75'>(Total: {$i})</small>
          </h6>

          <div style='position:relative; max-width:250px; width:100%;'>
            <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
            <input type='text' id='searchStadisticsByShipTable' placeholder='Buscar nave, naviera, puerto...' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
          </div>
        </div>

        <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
          <table id='stadisticsByShipTable' class='table table-hover mb-0 align-middle' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
            <thead style='background:#4e73df; color:white; position:sticky; top:0; z-index:2;'>
              <tr>
                <th>#</th>
                <th>Nave</th>
                <th>Viaje</th>
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
                <th class='text-center'>Detalle</th>
              </tr>
            </thead>
            <tbody>$rows</tbody>
          </table>
        </div>
      </div>

      <!-- Modal -->
      <div class='modal fade' id='detailModal' tabindex='-1'>
        <div class='modal-dialog modal-xl modal-dialog-scrollable'>
          <div class='modal-content'>
            <div class='modal-header' style='flex-direction:column; align-items:flex-start;'>
              <h5 class='modal-title'>Desgloce de Carga</h5>
              <h6 id='modalTitleDetail' class='modal-title'></h6>
              <button type='button' class='close' data-bs-dismiss='modal' aria-label='Cerrar' style='position:absolute; right:15px; top:15px;'>
                <span>×</span>
              </button>
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

        document.getElementById('searchStadisticsByShipTable').addEventListener('keyup', function() {
          const filter = this.value.toLowerCase().trim();
          const rows   = document.querySelectorAll('#stadisticsByShipTable tbody tr');

          rows.forEach(row => {
            const text = (
              (row.cells[1]?.innerText || '') + ' ' +
              (row.cells[2]?.innerText || '') + ' ' +
              (row.cells[3]?.innerText || '') + ' ' +
              (row.cells[4]?.innerText || '')
            ).toLowerCase();

            let match = text.includes(filter);

            if (filter.includes(' ')) {
              const words = filter.split(' ').filter(Boolean);
              match = words.every(w => text.includes(w));
            }

            row.style.display = match ? '' : 'none';
          });
        });
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
    WHERE op.arrival_date BETWEEN :inicio AND :fin
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
          <td colspan='9' class='text-right'>Totales</td>
          <td>Pallets: " . number_format($totalPallets, 0, ',', '.') . "</td>
          <td colspan='8'>Camiones: " . number_format($totalCamiones, 0, ',', '.') . "</td>
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
      <div class='card shadow mb-4'>
        <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
          <h6 class='mb-0'>
            <i class='fas fa-clock'></i> Reporte de Turnos
            <em>(Total camiones: " . number_format($totalCamiones, 0, ',', '.') . ")</em>
          </h6>

          <div style='position:relative; max-width:250px;'>
            <i class='fas fa-search'style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d;'></i>
            <input type='text' id='seacrhShiftsTable' placeholder='Buscar...' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
          </div>
        </div>

        <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
          <table id='shiftsTable' class='table table-hover mb-0'style='min-width:1300px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
            <thead style='background:#4e73df;color:white; position:sticky; top:0; z-index:1;'>
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

      <script>
        document.getElementById('seacrhShiftsTable').addEventListener('keyup', function() {
          let filter = this.value.toLowerCase().trim();
          let rows = document.querySelectorAll('#shiftsTable tbody tr');

          rows.forEach(row => {
            let text = row.innerText.toLowerCase();

            let match = text.includes(filter);

            if (filter.includes(' ')) {
              let words = filter.split(' ');
              match = words.every(w => text.includes(w));
            }

            row.style.display = match ? '' : 'none';
          });
        });
      </script>
    ";
  }

}
