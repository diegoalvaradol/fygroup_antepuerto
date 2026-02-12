<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

class tracking extends iQuery
{
  protected string $table      = "app_tracking";
  protected string $primaryKey = 'id';

  public $id         = "id";
  public $chargueid  = 'id_chargue';
  public $status     = 'status';
  public $statusdate = 'status_date';
  public $created    = 'created';

  public function __construct()
  {
    parent::__construct(); // usa Database::get() desde iQuery
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (id_chargue, status, status_date, created) VALUES (:chargueid, :status, :statusdate, :created)";
    $stmt  = $this->db->prepare($query);

    $this->chargueid  = htmlspecialchars(strip_tags($this->chargueid));
    $this->status     = htmlspecialchars(strip_tags($this->status));
    $this->statusdate = $this->statusdate;
    $this->created    = $this->created;

    $stmt->bindParam(":chargueid", $this->chargueid, PDO::PARAM_INT);
    $stmt->bindParam(":status", $this->status, PDO::PARAM_INT);
    $stmt->bindParam(":statusdate", $this->statusdate, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET id_chargue = :idchargue, status = :status, status_date = :statusdate WHERE id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id         = htmlspecialchars(strip_tags($this->id));
    $this->chargueid  = htmlspecialchars(strip_tags($this->chargueid));
    $this->status     = htmlspecialchars(strip_tags($this->status));
    $this->statusdate = htmlspecialchars(strip_tags($this->statusdate));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":idchargue", $this->chargueid, PDO::PARAM_INT);
    $stmt->bindParam(":status", $this->status, PDO::PARAM_INT);
    $stmt->bindParam(":statusdate", $this->statusdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE id_chargue = :idchargue AND status = 0";
    $stmt  = $this->db->prepare($query);

    $this->chargueid = htmlspecialchars(strip_tags($this->chargueid));

    $stmt->bindParam(":idchargue", $this->chargueid, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function getTableTracking($id)
  {
    $query = "SELECT * FROM $this->table AS t JOIN app_international_chargue AS ic ON t.id_chargue = ic.row_id JOIN app_ships AS sh ON sh.ship_id = ic.vessel_id WHERE t.id_chargue = :id";
    $stmt  = $this->db->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result !== []) {
      $fechas = [];
      foreach ($result as $row) {
        $fechas[(int) $row[$this->status]] = (new DateTime($row[$this->statusdate]))->format('d-m-Y H:i');
        $container                         = htmlspecialchars($row["container"]);
        $vessel                            = htmlspecialchars($row["vessel_name"]);
        $voyage                            = htmlspecialchars($row["voyage"]);
        $chargueid                         = $row[$this->chargueid];
        $currentStatus                     = $row[$this->status];
      }

      foreach ($result as $data) {
        $etapas = get::arrayItemTracking();

        $table = '
				<div class="card shadow rounded-4">
					<div class="card-body">
						<h5 class="card-title mb-4">Tracking del Contenedor: ' . $container . '</h5>
						<h6 class="card-title mb-4">Nave: ' . $vessel . ' - ' . 'Viaje: ' . $voyage . '</h6>
						<ul class="timeline list-unstyled">
				';

        foreach ($etapas as $index => $nombre) {
          $isActive   = ($data[$this->status] >= $index);
          $color      = $isActive ? 'success' : 'secondary';
          $icon       = $isActive ? 'fas' : 'far';
          $statusDate = $isActive ? $fechas[$index] : 'Por estimar.';

          $button = '';
          if ($currentStatus + 1 == $index) {
            $button = '<button class="btn btn-sm btn-primary mt-2" onclick="registrarFecha(' . $index . ', \'' . $nombre . '\', ' . $chargueid . ')">Registrar Item</button>';
          }

          $table .= '
					<li class="mb-4 d-flex">
						<div class="me-3 text-' . $color . '"><i class="' . $icon . ' fa-circle"></i></div>
						<div><strong>' . $nombre . '</strong><br><small class="text-muted">' . $statusDate . '</small><br>' . $button . '</div>
					</li>';
        }

        $table .= '
						</ul>
					</div>
				</div>
				';
      }

      return $table;
    }

    return '';
  }

}
