<?php
require_once __DIR__ . '/../config/includes.php';

class company extends iQuery
{
  protected string $table      = "app_company";
  protected string $primaryKey = 'id';

  public $id         = "id";
  public $name       = "name";
  public $exporter   = "exporter";
  public $agency     = "agency";
  public $created    = "created";
  public $lastupdate = "last_update";

  public function __construct()
  {
    parent::__construct(); // usa Database::get() desde iQuery
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (name, exporter, agency, created, last_update) VALUES (:name, :exporter, :agency, :created, :lastupdate)";
    $stmt  = $this->db->prepare($query);

    $this->name       = htmlspecialchars(strip_tags($this->name));
    $this->exporter   = $this->exporter;
    $this->agency     = $this->agency;
    $this->created    = $this->created;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_INT);
    $stmt->bindParam(":agency", $this->agency, PDO::PARAM_INT);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET /* name = :name, */ exporter = :exporter, agency = :agency, last_update = :lastupdate WHERE id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id = $this->id;
    /* $this->name       = htmlspecialchars(strip_tags($this->name)); */
    $this->exporter   = $this->exporter;
    $this->agency     = $this->agency;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    /*$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);*/
    $stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_INT);
    $stmt->bindParam(":agency", $this->agency, PDO::PARAM_INT);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function getCompanyName($lineId)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
    $stmt  = $this->db->prepare($query);
    $stmt->bindParam(":id", $lineId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result > 0) {
      return $result[$this->name];
    } else {
      return '-';
    }
  }

  public function getTableCompany()
  {
    $arrayYesNo = get::arrayYesNo();

    /* Contador de registros */
    $countQuery = "SELECT COUNT(*) FROM $this->table";
    $countStmt  = $this->db->prepare($countQuery);
    $countStmt->execute();
    $totalRegistros = $countStmt->fetchColumn();

    /* Construccion total de la página y query */
    $porPagina = 25; /* Número de registros por página */
    $pagina    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $inicio    = ($pagina - 1) * $porPagina;
    $urlBase   = generateMkey('enter_company', 'mySSL') . '&page=';

    $query = "SELECT * FROM $this->table WHERE 1 ORDER BY $this->id ASC LIMIT :inicio, :porPagina";
    $stmt  = $this->db->prepare($query);
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = 0;

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Id</th>";
    $thead .= "<th>Nombre</th>";
    $thead .= "<th>Tipo</th>";
    $thead .= "<th>Exportador</th>";
    $thead .= "<th>Agencia</th>";
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

      $isExporter = $arrayYesNo[$data[$this->exporter]];
      $isAgency   = $arrayYesNo[$data[$this->agency]];

      if ($data[$this->exporter]) {
        $type = 'Exportador';
      }if ($data[$this->agency]) {
        $type = 'Agencia';
      }if ($data[$this->exporter] && $data[$this->agency]) {
        $type = 'Exportador/Agencia';
      }

      $btnEdit   = "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editCompany(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pen'></i> Editar</button>";
      $btnDelete = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick=\"deleteCompany(" . $data[$this->id] . ", '" . $data[$this->name] . "', " . $data[$this->exporter] . ", " . $data[$this->agency] . ")\"'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";

      $tr .= "<tr>";
      $tr .= "<td>" . $data[$this->id] . "</td>";
      $tr .= "<td>" . $data[$this->name] . "</td>";
      $tr .= "<td><b>" . $type . "</b></td>";
      $tr .= "<td><b>" . $isExporter . "</b></td>";
      $tr .= "<td><b>" . $isAgency . "</b></td>";
      $tr .= "<td>" . $created . "</td>";
      $tr .= "<td>" . $lastupdate . "</td>";
      $tr .= "<td>" . $btnEdit . ' ' . $btnDelete . "</td>";
      $tr .= "</tr>";

      $count++;
    }

    $tbclose = "</tbody>";

    $table = "
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Empresas <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
              <table class='table table-bordered table-hover' style='width:revert-layer;'>
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
