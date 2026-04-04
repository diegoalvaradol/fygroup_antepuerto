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

    $thead = "<thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>";
    $thead .= "<tr>";
    $thead .= "<th>Id</th>";
    $thead .= "<th>Ciudad</th>";
    $thead .= "<th>País</th>";
    $thead .= "<th>Bandera</th>";
    $thead .= "<th>Creado</th>";
    $thead .= "<th>Actualizado</th>";
    $thead .= "<th>Acciones</th>";
    $thead .= "</tr>";
    $thead .= "</thead><tbody>";

    $tr = "";

    foreach ($result as $data) {
      $created = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
      $updated = (new DateTime($data[$this->lastupdate]))->format('d-m-Y H:i');

      $btnEdit   = "<button class='btn btn-warning btn-sm' onclick='editPort(" . $data[$this->id] . ")'><i class='fas fa-pen'></i> Editar</button>";
      $btnDelete = "<button class='btn btn-danger btn-sm' onclick='deletePort(" . $data[$this->id] . ")'><i class='fas fa-trash'></i> Eliminar</button>";

      $tr .= "<tr>";
      $tr .= "<td>{$data[$this->id]}</td>";
      $tr .= "<td>{$data[$this->city]}</td>";
      $tr .= "<td>{$data[$this->country]}</td>";
      $tr .= "<td>" . self::getflagImage($data[$this->country]) . "</td>";
      $tr .= "<td>{$created}</td>";
      $tr .= "<td>{$updated}</td>";
      $tr .= "<td>{$btnEdit} {$btnDelete}</td>";
      $tr .= "</tr>";

      $count++;
    }

    $table = "
      <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
              <h6 class='mb-0'>
                <i class='fas fa-list'></i> Listado de Puertos
                <em>(Total: $count)</em>
              </h6>

              <div style='position:relative; max-width:250px; width:100%;'>
                <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                <input type='text' id='searchTablePort' placeholder='Buscar por ciudad' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
              </div>
            </div>

            <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
              <table id='portTable' class='table table-hover mb-0' style='min-width:800px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
                $thead
                $tr
              </table>
            </div>
          </div>
        </div>
      </div>

      <script>
      document.getElementById('searchTablePort').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase().trim();
        let rows = document.querySelectorAll('#portTable tbody tr');

        rows.forEach(row => {
          let cell = row.cells[1];
          let text = cell ? cell.innerText.toLowerCase() : '';
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
