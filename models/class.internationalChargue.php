<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

class internationalChargue extends iQuery
{
    protected string $table = 'app_international_chargue';
    protected string $primaryKey = 'row_id';

    public $id = 'row_id';
    public $countervessel = 'counter_vessel';
    public $vessel = 'vessel_id';
    public $carplate = 'car_plate';
    public $container = 'container';
    public $seal = 'seal_number';
    public $guide = 'guide_number';
    public $exporter = 'exporter';
    public $pallets = 'pallets_quantity';
    public $namedriver = 'name_driver';
    public $cellphonedriver = 'cellphone_driver';
    public $digitedby = 'digited_by';
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (counter_vessel, vessel_id, car_plate, container, seal_number, guide_number, exporter, pallets_quantity, name_driver, cellphone_driver, digited_by, created, last_update)";
        $query .= ' VALUES (:countervessel, :vessel, :carplate, :container, :seal, :guide, :exporter, :pallets, :namedriver, :cellphonedriver, :digitedby, :created, :lastupdate)';
        $stmt = $this->db->prepare($query);

        $this->countervessel = htmlspecialchars(strip_tags($this->countervessel));
        $this->vessel = htmlspecialchars(strip_tags($this->vessel));
        $this->carplate = htmlspecialchars(strip_tags($this->carplate));
        $this->container = htmlspecialchars(strip_tags($this->container));
        $this->seal = htmlspecialchars(strip_tags($this->seal));
        $this->guide = htmlspecialchars(strip_tags($this->guide));
        $this->exporter = htmlspecialchars(strip_tags($this->exporter));
        $this->pallets = htmlspecialchars(strip_tags($this->pallets));
        $this->namedriver = htmlspecialchars(strip_tags($this->namedriver));
        $this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver));
        $this->digitedby = htmlspecialchars(strip_tags($this->digitedby));
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':countervessel', $this->countervessel, PDO::PARAM_INT);
        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_INT);
        $stmt->bindParam(':carplate', $this->carplate, PDO::PARAM_STR);
        $stmt->bindParam(':container', $this->container, PDO::PARAM_STR);
        $stmt->bindParam(':seal', $this->seal, PDO::PARAM_STR);
        $stmt->bindParam(':guide', $this->guide, PDO::PARAM_STR);
        $stmt->bindParam(':exporter', $this->exporter, PDO::PARAM_STR);
        $stmt->bindParam(':pallets', $this->pallets, PDO::PARAM_INT);
        $stmt->bindParam(':namedriver', $this->namedriver, PDO::PARAM_STR);
        $stmt->bindParam(':cellphonedriver', $this->cellphonedriver, PDO::PARAM_STR);
        $stmt->bindParam(':digitedby', $this->digitedby, PDO::PARAM_STR);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();

            return true;
        }

        return false;
    }

    public function update()
    {
        $query = "UPDATE $this->table SET counter_vessel = :countervessel, vessel_id = :vessel, car_plate = :carplate, container = :container, seal_number = :seal, guide_number = :guide, exporter = :exporter, pallets_quantity = :pallets, name_driver = :namedriver, cellphone_driver = :cellphonedriver, digited_by = :digitedby, last_update = :lastupdate WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->countervessel = htmlspecialchars(strip_tags($this->countervessel));
        $this->vessel = htmlspecialchars(strip_tags($this->vessel));
        $this->carplate = htmlspecialchars(strip_tags($this->carplate));
        $this->container = htmlspecialchars(strip_tags($this->container));
        $this->seal = htmlspecialchars(strip_tags($this->seal));
        $this->guide = htmlspecialchars(strip_tags($this->guide));
        $this->exporter = htmlspecialchars(strip_tags($this->exporter));
        $this->pallets = htmlspecialchars(strip_tags($this->pallets));
        $this->namedriver = htmlspecialchars(strip_tags($this->namedriver));
        $this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver));
        $this->digitedby = htmlspecialchars(strip_tags($this->digitedby));
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':countervessel', $this->countervessel, PDO::PARAM_INT);
        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_INT);
        $stmt->bindParam(':carplate', $this->carplate, PDO::PARAM_STR);
        $stmt->bindParam(':container', $this->container, PDO::PARAM_STR);
        $stmt->bindParam(':seal', $this->seal, PDO::PARAM_STR);
        $stmt->bindParam(':guide', $this->guide, PDO::PARAM_STR);
        $stmt->bindParam(':exporter', $this->exporter, PDO::PARAM_STR);
        $stmt->bindParam(':pallets', $this->pallets, PDO::PARAM_INT);
        $stmt->bindParam(':namedriver', $this->namedriver, PDO::PARAM_STR);
        $stmt->bindParam(':cellphonedriver', $this->cellphonedriver, PDO::PARAM_STR);
        $stmt->bindParam(':digitedby', $this->digitedby, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findByUser($run)
    {
        $query = 'SELECT * FROM app_users WHERE run = :run';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':run', $run, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $name = $result['name'] . ' ' . $result['last_name'];

        return $name;
    }

    public function tableContainerInternational()
    {
        $ship = new ship();
        $user = new user();
        $adminEdit = $user->isAdminEdit($_SESSION['user']['run']);
        $count = 0;

        /* Filtros */
        $filterNave = isset($_POST['nave']) ? $_POST['nave'] : '';
        $filterPatente = isset($_POST['patente']) ? $_POST['patente'] : '';
        $filterGuia = isset($_POST['guia']) ? trim($_POST['guia']) : '';

        /* Construir cláusulas WHERE dinámicamente */
        $conditions = [' 1 '];
        $params = [];

        if ($filterNave !== '') {
            $conditions[] = 'sh.ship_id = :nave';
            $params[':nave'] = $filterNave;
        }

        if ($filterPatente !== '') {
            $conditions[] = "$this->carplate = :patente";
            $params[':patente'] = $filterPatente;
        }

        if ($filterGuia !== '') {
            $conditions[] = "$this->guide LIKE :guia";
            $params[':guia'] = "%$filterGuia%";
        }

        $whereClause = implode(' AND ', $conditions);
        /*
        $query       = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
        $stmt        = $this->conexion->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         */

        /* Contador de registros */
        $countQuery = "SELECT COUNT(*) FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause AND sh.finished = 0";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($params);
        $totalRegistros = $countStmt->fetchColumn();

        /* Construccion total de la página y query */
        $porPagina = 25; /* Número de registros por página */
        $pagina = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $inicio = ($pagina - 1) * $porPagina;
        $urlBase = generateMkey('enter_container_international') . '&page=';

        $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC LIMIT :inicio, :porPagina";
        $stmt = $this->db->prepare($query);
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
            <form method='POST' class='form-container' id='filterFormInternationalChargue'>
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
                <button type='submit' class='btn btn-sm btn-primary btn-user' style='margin-right:0.5%;'><i class='fas fa-solid fa-search'></i> Buscar</button>
                <button type='button' class='btn btn-sm btn-success btn-user' style='margin-right:0.5%;' onclick=\"" . "exportExcel('" . htmlspecialchars($_POST['nave'] ?? '') . "', '" . htmlspecialchars($_POST['patente'] ?? '') . "', '" . htmlspecialchars($_POST['guia'] ?? '') . "')" . "\"><i class='fas fa-file-excel'></i> Descargar Excel</button>
                <button type='button' class='btn btn-sm btn-warning btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Recargar Filtros</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    ";

        $thead = "<thead style='background-color:#4e73df; color:white;'>";
        $thead .= '<tr>';
        $thead .= '<th>Posición</th>';
        $thead .= '<th>Nave</th>';
        $thead .= '<th>Patente</th>';
        $thead .= '<th>Guía(s)</th>';
        $thead .= '<th>Contenedor</th>';
        $thead .= '<th>Sello</th>';
        $thead .= '<th>Exportador</th>';
        $thead .= '<th>Pallets</th>';
        $thead .= '<th>Conductor</th>';
        $thead .= '<th>Teléfono</th>';
        $thead .= '<th>Creado</th>';
        $thead .= '<th>Digitado Por</th>';
        $thead .= '<th>Acciones</th>';
        $thead .= '</tr>';
        $thead .= '</thead>';
        $thead .= '<tbody>';

        $tr = null;

        if ($result !== []) {
            foreach ($result as $data) {
                $createdTime = new DateTime($data[$this->created]);
                $created = $createdTime->format('d-m-Y H:i');
                $btnEdit = $adminEdit ? "<button id='editcontainer' type='button' class='btn btn-sm btn-warning btn-user' onclick='editContainer(" . $data[$this->id] . ")'><i class='fas fa-solid fa-pencil'></i> Editar</button>" : null;

                $tr .= '<tr>';
                $tr .= '<td>' . $data[$this->countervessel] . '</td>';
                $tr .= '<td>' . $ship->getVesselName($data[$this->vessel]) . '</td>';
                $tr .= '<td>' . $data[$this->carplate] . '</td>';
                $tr .= '<td>' . $data[$this->guide] . '</td>';
                $tr .= '<td>' . $data[$this->container] . '</td>';
                $tr .= '<td>' . $data[$this->seal] . '</td>';
                $tr .= '<td>' . $data[$this->exporter] . '</td>';
                $tr .= '<td>' . $data[$this->pallets] . '</td>';
                $tr .= '<td>' . $data[$this->namedriver] . '</td>';
                $tr .= '<td>' . $data[$this->cellphonedriver] . '</td>';
                $tr .= '<td>' . $created . '</td>';
                $tr .= '<td>' . $this->findByUser($data[$this->digitedby]) . '</td>';
                $tr .= '<td>' . $btnEdit . '</td>';
                $tr .= '</tr>';

                $count++;
            }
        } else {
            $tr .= '<tr>';
            $tr .= "<td colspan='19' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
            $tr .= '</tr>';
        }

        $tbclose = '</tbody>';

        $table = $form . "
    <div class='row'>
        <div class='col-lg-12'>
          <div class='card shadow mb-4'>
            <div class='card-header bg-primary text-white'>
              <h6 class='mb-0'><i class='fas fa-list'></i> Listado de Contenedores <em>(Total de Registros: " . $count . ")</em></h6>
            </div>

            <div class='table-responsive'>
              <table class='table table-bordered table-hover' style='width:max-content;'>
                " . $thead . $tr . $tbclose . '
              </table>
            </div>
          </div>
          ' . $this->paginate($totalRegistros, $porPagina, $pagina, $urlBase) . '
        </div>
      </div>
    ';

        return $table;
    }

    public function downloadTableInternationalChargueExcel($nave = '', $patente = '', $guia = '')
    {
        $ship = new ship();

        $filtros = [];
        $where = 'WHERE 1';

        if (!empty($nave)) {
            $where .= ' AND sh.ship_id = ?';
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

        $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id $where AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($filtros);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Listado de Termos');

        // Encabezados
        $headers = ['Posición', 'Nave', 'Patente', 'Guía', 'Contenedor', 'Sello', 'Exportador', 'Pallets', 'Conductor', 'Teléfono', 'Creado', 'Digitado Por'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Cuerpo
        $row = 2;
        foreach ($result as $data) {
            $createdTime = new DateTime($data[$this->created]);
            $created = $createdTime->format('d-m-Y H:i');

            $sheet->fromArray([
              $data[$this->countervessel],
              $ship->getVesselName($data[$this->vessel]),
              $data[$this->carplate],
              $data[$this->guide],
              $data[$this->container],
              $data[$this->seal],
              $data[$this->exporter],
              $data[$this->pallets],
              $data[$this->namedriver],
              $data[$this->cellphonedriver],
              $created,
              $this->findByUser($data[$this->digitedby]),
            ], null, 'A' . $row);

            $row++;
        }

        // Enviar headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_de_Carga_Internacional_' . date('d-m-Y H:i:s') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
