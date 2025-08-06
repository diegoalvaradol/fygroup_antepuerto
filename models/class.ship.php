<?php
require_once __DIR__ . '/../config/includes.php';
class ship extends iQuery
{
  private $conexion;
  protected $table      = "app_ships";
  protected $primaryKey = 'ship_id';

  public $id           = "ship_id";
  public $vessel       = "vessel_name";
  public $voyage       = "voyage";
  public $port         = "port_discharge";
  public $line         = "ship_line";
  public $finished     = "finished"; /* Indica si el emabrque de la motonave finalizo [0 => No, 1 => Si] */
  public $finisheddate = "finished_date"; /* Fecha de finalizacion del embarque */
  public $eta          = "eta";
  public $etd          = "etd";
  public $created      = "created";
  public $lastupdate   = "last_update";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO  $this->table (vessel_name, ship_line, voyage, port_discharge, eta, etd, created, last_update) VALUES (:vessel, :shipline, :voyage, :portdischarge, :eta, :etd, :created, :lastupdate)";
    $stmt  = $this->conexion->prepare($query);

    $this->vessel     = htmlspecialchars(strip_tags($this->vessel));
    $this->line       = htmlspecialchars(strip_tags($this->line));
    $this->voyage     = htmlspecialchars(strip_tags($this->voyage));
    $this->port       = htmlspecialchars(strip_tags($this->port));
    $this->eta        = $this->eta;
    $this->etd        = $this->etd;
    $this->created    = $this->created;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_STR);
    $stmt->bindParam(":shipline", $this->line, PDO::PARAM_INT);
    $stmt->bindParam(":voyage", $this->voyage, PDO::PARAM_STR);
    $stmt->bindParam(":portdischarge", $this->port, PDO::PARAM_INT);
    $stmt->bindParam(":eta", $this->eta, PDO::PARAM_STR);
    $stmt->bindParam(":etd", $this->etd, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET vessel_name = :vessel, ship_line = :shipline, voyage = :voyage, port_discharge = :portdischarge, eta = :eta, etd = :etd, last_update = :lastupdate WHERE ship_id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id         = htmlspecialchars(strip_tags($this->id));
    $this->vessel     = htmlspecialchars(strip_tags($this->vessel));
    $this->line       = htmlspecialchars(strip_tags($this->line));
    $this->voyage     = htmlspecialchars(strip_tags($this->voyage));
    $this->port       = htmlspecialchars(strip_tags($this->port));
    $this->eta        = $this->eta;
    $this->etd        = $this->etd;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_STR);
    $stmt->bindParam(":shipline", $this->line, PDO::PARAM_INT);
    $stmt->bindParam(":voyage", $this->voyage, PDO::PARAM_STR);
    $stmt->bindParam(":portdischarge", $this->port, PDO::PARAM_INT);
    $stmt->bindParam(":eta", $this->eta, PDO::PARAM_STR);
    $stmt->bindParam(":etd", $this->etd, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE ship_id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function endStacking()
  {
    $query = "UPDATE $this->table SET finished = :finished, finished_date = :finisheddate, last_update = :lastupdate WHERE ship_id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id           = htmlspecialchars(strip_tags($this->id));
    $this->finished     = htmlspecialchars(strip_tags($this->finished));
    $this->finisheddate = $this->finisheddate;
    $this->lastupdate   = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":finished", $this->finished, PDO::PARAM_INT);
    $stmt->bindParam(":finisheddate", $this->finisheddate, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function getVesselName($vesselId)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":id", $vesselId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result > 0) {
      return $result[$this->vessel];
    } else {
      return '-';
    }
  }

  public function getTableShip()
  {
    $port     = new port($this->conexion);
    $shipLine = new shipLine($this->conexion);
    $count    = 0;

    /* Contador de registros */
    $countQuery = "SELECT COUNT(*) FROM $this->table WHERE 1";
    $countStmt  = $this->conexion->prepare($countQuery);
    $countStmt->execute();
    $totalRegistros = $countStmt->fetchColumn();

    /* Construccion total de la página y query */
    $porPagina = 25; /* Número de registros por página */
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;
    $urlBase   = generateMkey('enter_ship') . '&page=';

    $query = "SELECT * FROM $this->table WHERE 1 ORDER BY ship_id ASC LIMIT :inicio, :porPagina";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Id</th>";
    $thead .= "<th>Nave</th>";
    $thead .= "<th>Linea</th>";
    $thead .= "<th>Viaje</th>";
    $thead .= "<th>Puerto de Destino</th>";
    $thead .= "<th>Bandera</th>";
    $thead .= "<th>Arrivo</th>";
    $thead .= "<th>Zarpe</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Actualizado</th>";
    $thead .= "<th>Embarque</th>";
    $thead .= "<th>Acciones</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = null;
    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->created]);
      $updateTime  = new DateTime($data[$this->lastupdate]);
      $etaTime     = new DateTime($data[$this->eta]);
      $etdTime     = new DateTime($data[$this->etd]);

      $created    = $createdTime->format('d-m-Y H:i');
      $lastupdate = $updateTime->format('d-m-Y H:i');
      $eta        = $etaTime->format('d-m-Y H:i');
      $etd        = $etdTime->format('d-m-Y H:i');

      if ($data[$this->finisheddate] != '0000-00-00 00:00:00') {
        $finishDateTime = new DateTime($data[$this->finisheddate]);
        $finish         = $finishDateTime->format('d-m-Y H:i');
      } else {
        $finish = 'Por estimar.';
      }

      $btnFinishedDate = '<i class="fas fa-info-circle text-info" title="Fecha de Cierre: ' . $finish . '" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right"></i>';
      $btnEndStacking  = $data[$this->finished] == 0 ? "<button type='button' class='btn btn-danger btn-sm' onclick='stackingShip(" . $data[$this->id] . ", \"" . $data[$this->vessel] . "\", \"" . $data[$this->voyage] . "\", 1)'><i class='fas fa-solid fa-lock'></i> Cerrar</button>" : "<button type='button' class='btn btn-success btn-sm' onclick='stackingShip(" . $data[$this->id] . ", \"" . $data[$this->vessel] . "\", \"" . $data[$this->voyage] . "\", 0)'><i class='fas fa-solid fa-lock-open'></i> Abrir</button>";
      $btnEdit         = "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editShip(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pen'></i> Editar</button>";
      $btnDelete       = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deleteShip(" . $data[$this->id] . ")'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";

      $tr .= "<tr>";
      $tr .= "<td >" . $data[$this->id] . "</td>";
      $tr .= "<td >" . $data[$this->vessel] . "</td>";
      $tr .= "<td >" . $shipLine->getLineName($data[$this->line]) . "</td>";
      $tr .= "<td >" . $data[$this->voyage] . "</td>";
      $tr .= "<td >" . $port->getPortName($data[$this->port]) . "</td>";
      $tr .= "<td >" . $port->getflagImage($port->getCountryName($data[$this->port])) . "</td>";
      $tr .= "<td >" . $eta . "</td>";
      $tr .= "<td >" . $etd . "</td>";
      $tr .= "<td >" . $created . "</td>";
      $tr .= "<td >" . $lastupdate . "</td>";
      $tr .= "<td >" . $btnEndStacking . ' ' . $btnFinishedDate . "</td>";
      $tr .= "<td >" . $btnEdit . ' ' . $btnDelete . "</td>";
      $tr .= "</tr>";

      $count++;
    }

    $tbclose = "</tbody>";

    $table = "
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Naves <em>(Total de Registros: " . $count . ")</em></h6>
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

}
