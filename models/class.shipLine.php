<?php
require_once __DIR__ . '/../config/includes.php';

class shipLine extends iQuery
{
  private $conexion;
  protected $table      = "app_ship_lines";
  protected $primaryKey = 'line_id';

  public $id         = "line_id";
  public $name       = "name";
  public $created    = "created";
  public $lastupdate = "last_update";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (name, created, last_update) VALUES (:name, :created, :lastupdate)";
    $stmt  = $this->conexion->prepare($query);

    $this->name       = htmlspecialchars(strip_tags($this->name));
    $this->created    = $this->created;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET name = :name, last_update = :lastupdate WHERE line_id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id         = htmlspecialchars(strip_tags($this->id));
    $this->name       = htmlspecialchars(strip_tags($this->name));
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE line_id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function getLineName($lineId)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :lineId LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":lineId", $lineId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result > 0) {
      return $result[$this->name];
    } else {
      return '-';
    }
  }

  public function getTableShipLine()
  {
    $query = "SELECT * FROM $this->table WHERE 1 ORDER BY line_id ASC";
    $stmt  = $this->conexion->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = 0;

    $thead = "<thead style='background-color:#2653d4; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Id</th>";
    $thead .= "<th>Nombre</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Actualizado</th>";
    $thead .= "<th>Acciones</th>";
    $thead .= "</tr>";
    $thead .= "</thead>";
    $thead .= "<tbody>";

    $tr = null;

    foreach ($result as $data) {
      $createdTime = new DateTime($data[$this->created]);
      $updateTime  = new DateTime($data[$this->lastupdate]);

      $created    = $createdTime->format('d-m-Y H:i');
      $lastupdate = $updateTime->format('d-m-Y H:i');

      $btnEdit   = "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editShipLine(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pen'></i> Editar</button>";
      $btnDelete = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deleteShipLine(" . $data[$this->id] . ")'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";

      $tr .= "<tr>";
      $tr .= "<td >" . $data[$this->id] . "</td>";
      $tr .= "<td >" . $data[$this->name] . "</td>";
      $tr .= "<td >" . $created . "</td>";
      $tr .= "<td >" . $lastupdate . "</td>";
      $tr .= "<td >" . $btnEdit . ' ' . $btnDelete . "</td>";
      $tr .= "</tr>";

      $count++;
    }

    $tbclose = "</tbody>";

    $table = "
    <div class='container-fluid'>
      <div class='table-responsive'>
        <table class='table table-bordered table-hover'>
          <h6 class='h3 mb-1 text-gray-800'>Listado de Lineas Navieras</h6>
          <h6> Total de Registros: " . $count . "</h6>
          " . $thead . $tr . $tbclose . "
        </table>
      </div>
    </div>
    ";

    return $table;
  }

}
