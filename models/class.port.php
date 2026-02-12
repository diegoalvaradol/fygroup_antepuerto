<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

class port extends iQuery
{
  protected string $table      = "app_ports";
  protected string $primaryKey = 'port_id';

  public $id         = "port_id";
  public $city       = "city";
  public $country    = "country";
  public $created    = "created";
  public $lastupdate = "last_update";

  public function __construct()
  {
    parent::__construct(); // usa Database::get() desde iQuery
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (city, country, created, last_update) VALUES (:city, :country, :created, :lastupdate)";
    $stmt  = $this->db->prepare($query);

    $this->city       = htmlspecialchars(strip_tags($this->city));
    $this->country    = htmlspecialchars(strip_tags($this->country));
    $this->created    = $this->created;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":city", $this->city, PDO::PARAM_STR);
    $stmt->bindParam(":country", $this->country, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET city = :city, country = :country, last_update = :lastupdate WHERE port_id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id         = htmlspecialchars(strip_tags($this->id));
    $this->city       = htmlspecialchars(strip_tags($this->city));
    $this->country    = htmlspecialchars(strip_tags($this->country));
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":city", $this->city, PDO::PARAM_STR);
    $stmt->bindParam(":country", $this->country, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE port_id = :id";
    $stmt  = $this->db->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function getPortName($portId)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
    $stmt  = $this->db->prepare($query);
    $stmt->bindParam(":id", $portId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result > 0) {
      return $result[$this->city] . ' - ' . $result[$this->country];
    } else {
      return '-';
    }
  }

  public function getCountryName($portId)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
    $stmt  = $this->db->prepare($query);
    $stmt->bindParam(":id", $portId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result > 0) {
      return $result[$this->country];
    } else {
      return '-';
    }
  }

  public function getTablePort()
  {
    $query = "SELECT * FROM $this->table WHERE 1 ORDER BY port_id ASC";
    $stmt  = $this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;

    $thead = "<thead style='background-color:#4e73df; color:white;'>";
    $thead .= "<tr>";
    $thead .= "<th>Id</th>";
    $thead .= "<th>Ciudad</th>";
    $thead .= "<th>País</th>";
    $thead .= "<th>Bandera</th>";
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

      $btnEdit   = "<button type='button' class='btn btn-warning btn-user btn-sm' onclick='editPort(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pen'></i> Editar</button>";
      $btnDelete = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deletePort(" . $data[$this->id] . ")'><i class='fas fa-solid fa-trash'></i> Eliminar</button>";

      $tr .= "<tr>";
      $tr .= "<td >" . $data[$this->id] . "</td>";
      $tr .= "<td >" . $data[$this->city] . "</td>";
      $tr .= "<td >" . $data[$this->country] . "</td>";
      $tr .= "<td >" . self::getflagImage($data[$this->country]) . "</td>";
      $tr .= "<td >" . $created . "</td>";
      $tr .= "<td >" . $lastupdate . "</td>";
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
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Puertos <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
              <table class='table table-bordered table-hover' style='width:revert-layer;'>
                " . $thead . $tr . $tbclose . "
              </table>
            </div>
          </div>
        </div>
      </div>
    ";

    return $table;
  }

  public function getflagImage($countryName)
  {
    $countryToIso = [
      'Afganistán'     => 'af',
      'Albania'        => 'al',
      'Argelia'        => 'dz',
      'Andorra'        => 'ad',
      'Angola'         => 'ao',
      'Argentina'      => 'ar',
      'Australia'      => 'au',
      'Austria'        => 'at',
      'Bélgica'        => 'be',
      'Bolivia'        => 'bo',
      'Brasil'         => 'br',
      'Canadá'         => 'ca',
      'Chile'          => 'cl',
      'China'          => 'cn',
      'Colombia'       => 'co',
      'Cuba'           => 'cu',
      'Dinamarca'      => 'dk',
      'Egipto'         => 'eg',
      'Finlandia'      => 'fi',
      'Francia'        => 'fr',
      'Alemania'       => 'de',
      'Grecia'         => 'gr',
      'India'          => 'in',
      'Indonesia'      => 'id',
      'Irlanda'        => 'ie',
      'Italia'         => 'it',
      'Japón'          => 'jp',
      'México'         => 'mx',
      'Países Bajos'   => 'nl',
      'Nueva Zelanda'  => 'nz',
      'Noruega'        => 'no',
      'Panamá'         => 'pa',
      'Paraguay'       => 'py',
      'Perú'           => 'pe',
      'Polonia'        => 'pl',
      'Portugal'       => 'pt',
      'Rusia'          => 'ru',
      'Sudáfrica'      => 'za',
      'Corea del Sur'  => 'kr',
      'España'         => 'es',
      'Suecia'         => 'se',
      'Suiza'          => 'ch',
      'Turquía'        => 'tr',
      'Ucrania'        => 'ua',
      'Reino Unido'    => 'gb',
      'Estados Unidos' => 'us',
      'Uruguay'        => 'uy',
      'Venezuela'      => 've',
      'Vietnam'        => 'vn'
    ];

    $countryName = trim($countryName);
    if (!isset($countryToIso[$countryName])) {
      return ''; // bandera no encontrada
    }

    $code = $countryToIso[$countryName];
    $path = "../flag-icons/flags/4x3/{$code}.svg"; // Ruta a imágenes de banderas

    return "<img src='$path' width='30' style='vertical-align:middle; margin-right:5px; border-radius:6px; box-shadow:0 0 2px rgba(0,0,0,.4);'>";
  }

}
