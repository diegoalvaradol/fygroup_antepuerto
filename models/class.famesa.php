<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
class famesa extends iQuery
{
    protected string $table = 'app_famesa';
    protected string $primaryKey = 'row_id';

    public $id = 'row_id';
    public $countervessel = 'counter_vessel';
    public $vessel = 'vessel_id';
    public $carplatetruck = 'car_plate_truck';
    public $carplateramp = 'car_plate_ramp';
    public $guide = 'guide_number';
    public $maxibags = 'maxibags_quantity';
    public $category = 'category'; /* [1 => 1° Categoría, 2 => 2° Categoría] */
    public $arrivaldateport = 'arrival_date_port';
    public $departuredateport = 'departure_date_port';
    public $arrivaldatedeposit = 'arrival_date_deposit';
    public $departuredatedeposit = 'departure_date_deposit';
    public $observations = 'observations';
    public $created = 'created';
    public $createdby = 'created_by';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (counter_vessel, vessel_id, car_plate_truck, car_plate_ramp, guide_number, maxibags_quantity, category, arrival_date_port, departure_date_port, arrival_date_deposit, departure_date_deposit, observations, created, created_by)";
        $query .= 'VALUES (:countervessel, :vessel, :carplatetruck, :carplateramp, :guide, :maxibags, :category, :arrivaldateport, :departuredateport, :arrivaldatedeposit, :departuredatedeposit, :observations, :created, :createdby)';
        $stmt = $this->db->prepare($query);

        $this->countervessel = htmlspecialchars(strip_tags($this->countervessel));
        $this->vessel = htmlspecialchars(strip_tags($this->vessel));
        $this->carplatetruck = htmlspecialchars(strip_tags($this->carplatetruck));
        $this->carplateramp = htmlspecialchars(strip_tags($this->carplateramp ?? ''));
        $this->guide = htmlspecialchars(strip_tags($this->guide));
        $this->maxibags = htmlspecialchars(strip_tags($this->maxibags));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->arrivaldateport = $this->arrivaldateport;
        $this->departuredateport = $this->departuredateport;
        $this->arrivaldatedeposit = $this->arrivaldatedeposit;
        $this->departuredatedeposit = $this->departuredatedeposit;
        $this->observations = $this->observations;
        $this->created = $this->created;
        $this->createdby = htmlspecialchars(strip_tags($this->createdby));

        $stmt->bindParam(':countervessel', $this->countervessel, PDO::PARAM_INT);
        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_INT);
        $stmt->bindParam(':carplatetruck', $this->carplatetruck, PDO::PARAM_STR);
        $stmt->bindParam(':carplateramp', $this->carplateramp, PDO::PARAM_STR);
        $stmt->bindParam(':guide', $this->guide, PDO::PARAM_STR);
        $stmt->bindParam(':maxibags', $this->maxibags, PDO::PARAM_INT);
        $stmt->bindParam(':category', $this->category, PDO::PARAM_INT);
        $stmt->bindParam(':arrivaldateport', $this->arrivaldateport, PDO::PARAM_STR);
        $stmt->bindParam(':departuredateport', $this->departuredateport, PDO::PARAM_STR);
        $stmt->bindParam(':arrivaldatedeposit', $this->arrivaldatedeposit, PDO::PARAM_STR);
        $stmt->bindParam(':departuredatedeposit', $this->departuredatedeposit, PDO::PARAM_STR);
        $stmt->bindParam(':observations', $this->observations, PDO::PARAM_STR);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':createdby', $this->createdby, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET
        counter_vessel = :countervessel,
        vessel_id = :vessel,
        car_plate_truck = :carplatetruck,
        car_plate_ramp = :carplateramp,
        guide_number = :guide,
        maxibags_quantity = :maxibags,
        category = :category,
        arrival_date_port = :arrivaldateport,
        departure_date_port = :departuredateport,
        arrival_date_deposit = :arrivaldatedeposit,
        departure_date_deposit = :departuredatedeposit,
        observations = :observations
        WHERE row_id = :id";

        $stmt = $this->db->prepare($query);

        // Sanitización
        $this->id = (int) $this->id;
        $this->countervessel = (int) $this->countervessel;
        $this->vessel = (int) $this->vessel;
        $this->carplatetruck = htmlspecialchars(strip_tags($this->carplatetruck));
        $this->carplateramp = htmlspecialchars(strip_tags($this->carplateramp ?? ''));
        $this->guide = htmlspecialchars(strip_tags($this->guide));
        $this->maxibags = (int) $this->maxibags;
        $this->category = (int) $this->category;
        $this->arrivaldateport = $this->arrivaldateport ?: null;
        $this->departuredateport = $this->departuredateport ?: null;
        $this->arrivaldatedeposit = $this->arrivaldatedeposit ?: null;
        $this->departuredatedeposit = $this->departuredatedeposit ?: null;
        $this->observations = htmlspecialchars(strip_tags($this->observations));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':countervessel', $this->countervessel, PDO::PARAM_INT);
        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_INT);
        $stmt->bindParam(':carplatetruck', $this->carplatetruck, PDO::PARAM_STR);
        $stmt->bindParam(':carplateramp', $this->carplateramp, PDO::PARAM_STR);
        $stmt->bindParam(':guide', $this->guide, PDO::PARAM_STR);
        $stmt->bindParam(':maxibags', $this->maxibags, PDO::PARAM_INT);
        $stmt->bindParam(':category', $this->category, PDO::PARAM_INT);

        // Fechas: bindValue con PDO::PARAM_NULL si son null
        $stmt->bindValue(':arrivaldateport', $this->arrivaldateport ?? null, $this->arrivaldateport ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':departuredateport', $this->departuredateport ?? null, $this->departuredateport ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':arrivaldatedeposit', $this->arrivaldatedeposit ?? null, $this->arrivaldatedeposit ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':departuredatedeposit', $this->departuredatedeposit ?? null, $this->departuredatedeposit ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt->bindParam(':observations', $this->observations, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateDateTruck($field, $date)
    {
        $allowed = ['departure_date_port', 'arrival_date_deposit', 'departure_date_deposit'];

        // validar campo
        if (!in_array($field, $allowed)) {
            return false;
        }

        // convertir formato
        $rawDate = str_replace('T', ' ', $date);
        $d = DateTime::createFromFormat('Y-m-d H:i', $rawDate);

        if (!$d) {
            return false;
        }

        // FORMATEAR a string (clave)
        $dateFormatted = $d->format('Y-m-d H:i:s');

        $query = "UPDATE {$this->table} SET {$field} = :date WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = (int) $this->id;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $dateFormatted, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function updateArrivalDeposit()
    {
        $query = "UPDATE $this->table SET arrival_date_deposit = :arrivaldatedeposit WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->arrivaldatedeposit = $this->arrivaldatedeposit;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':arrivaldatedeposit', $this->arrivaldatedeposit, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function updateDepartureDeposit()
    {
        $query = "UPDATE $this->table SET departure_date_deposit = :departuredatedeposit WHERE row_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->departuredatedeposit = $this->departuredatedeposit;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':departuredatedeposit', $this->departuredatedeposit, PDO::PARAM_STR);

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

    public function getTableTrucksFamesa()
    {
        $ship = new ship();
        $user = new user();
        $adminEdit = $user->isAdminEdit($_SESSION['user']['run']);
        $count = 0;

        /* Filtros */
        $filterNave = isset($_POST['nave']) ? $_POST['nave'] : '-';
        $filterPatente = isset($_POST['patente']) ? $_POST['patente'] : '-';
        $filterGuia = isset($_POST['guia']) ? trim($_POST['guia']) : '';

        /* Construir cláusulas WHERE dinámicamente */
        $conditions = ['1'];
        $params = [];

        if ($filterNave !== '-') {
            $conditions[] = 'sh.ship_id = :nave';
            $params[':nave'] = $filterNave;
        }

        if ($filterPatente !== '-') {
            $conditions[] = "$this->carplatetruck = :patente";
            $params[':patente'] = $filterPatente;
        }

        if ($filterGuia !== '') {
            $conditions[] = "$this->guide LIKE :guia";
            $params[':guia'] = "%$filterGuia%";
        }

        $whereClause = implode(' AND ', $conditions);

        /* Contador de registros */
        $countQuery = "SELECT COUNT(*) FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($params);
        $totalRegistros = $countStmt->fetchColumn();

        /* Construccion total de la página y query */
        $porPagina = 25; /* Número de registros por página */
        $pagina = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $inicio = ($pagina - 1) * $porPagina;

        $query = "SELECT * FROM $this->table AS p JOIN app_ships AS sh ON sh.ship_id = p.vessel_id JOIN app_ship_lines AS sl ON sh.ship_line = sl.line_id WHERE $whereClause AND sh.finished = 0 ORDER BY p.counter_vessel ASC, p.vessel_id ASC LIMIT :inicio, :porPagina";
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
                            <form method='POST' class='form-container' id='filterFormTruck'>
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
                                    <button type='submit' class='btn btn-sm btn-primary btn-user' style='margin-right:0.5%;'><i class='fas fa-search'></i> Buscar</button>
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
        $thead .= '<th>Patente Camión</th>';
        $thead .= '<th>Patente Rampla</th>';
        $thead .= '<th>Guía(s)</th>';
        $thead .= '<th>Cant. Maxi Sacos</th>';
        $thead .= '<th>Categoría</th>';
        $thead .= '<th>Entrada Puerto</th>';
        $thead .= '<th>Salida Puerto</th>';
        $thead .= '<th>Entrada Depósito</th>';
        $thead .= '<th>Salida Depósito</th>';
        $thead .= '<th>Creado</th>';
        $thead .= '<th>Digitado Por</th>';
        $thead .= '<th>Acciones</th>';
        $thead .= '</tr>';
        $thead .= '</thead>';
        $thead .= '<tbody>';

        $tr = null;

        if ($result !== []) {
            foreach ($result as $data) {
                $btnAddTDeparturePort = "<button type='button' class='btn btn-success btn-user btn-sm' onclick=\"openModalHour(" . $data[$this->id] . ", 'departure_port')\"><i class='fas fa-clock'></i> Salida Puerto</button>";
                $btnAddTArrivalDeposit = "<button type='button' class='btn btn-success btn-user btn-sm' onclick=\"openModalHour(" . $data[$this->id] . ", 'arrival_depot')\"><i class='fas fa-clock'></i> Entrada Depósito</button>";
                $btnAddTDepartureDeposit = "<button type='button' class='btn btn-success btn-user btn-sm' onclick=\"openModalHour(" . $data[$this->id] . ", 'departure_depot')\"><i class='fas fa-clock'></i> Salida Depósito</button>";

                $created = formatDate($data[$this->arrivaldateport]);
                $arrivalPort = formatDate($data[$this->arrivaldateport]);
                $departurePort = $data[$this->departuredateport] != null ? formatDate($data[$this->departuredateport]) : $btnAddTDeparturePort;
                $arrivalDeposit = $data[$this->arrivaldatedeposit] != null ? formatDate($data[$this->arrivaldatedeposit]) : $btnAddTArrivalDeposit;
                $departureDeposit = $data[$this->departuredatedeposit] != null ? formatDate($data[$this->departuredatedeposit]) : $btnAddTDepartureDeposit;

                $btnEdit = $adminEdit ? "<button type='button' class='btn btn-sm btn-warning btn-user' onclick='editTruck(" . $data[$this->id] . ")'><i class='fas fa-pencil'></i> Editar</button>" : null;
                $btnDelete = "<button type='button' class='btn btn-danger btn-user btn-sm' onclick='deleteTruck(" . $data[$this->id] . ")'><i class='fas fa-trash'></i> Eliminar</button>";

                $tr .= '<td>' . $data[$this->countervessel] . '</td>';
                $tr .= '<td>' . $ship->getVesselName($data[$this->vessel]) . '</td>';
                $tr .= '<td>' . $data[$this->carplatetruck] . '</td>';
                $tr .= '<td>' . $data[$this->carplateramp] . '</td>';
                $tr .= '<td>' . $data[$this->guide] . '</td>';
                $tr .= '<td>' . $data[$this->maxibags] . '</td>';
                $tr .= '<td>' . $data[$this->category] . '</td>';
                $tr .= '<td>' . $arrivalPort . '</td>';
                $tr .= '<td>' . $departurePort . '</td>';
                $tr .= '<td>' . $arrivalDeposit . '</td>';
                $tr .= '<td>' . $departureDeposit . '</td>';
                $tr .= '<td>' . $created . '</td>';
                $tr .= '<td>' . $this->findByUser($data[$this->createdby]) . '</td>';
                $tr .= '<td>' . $btnEdit . ' ' . $btnDelete . '</td>';
                $tr .= '</tr>';

                $count++;
            }
        } else {
            $tr .= '<tr>';
            $tr .= "<td colspan='14' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>";
            $tr .= '</tr>';
        }

        $tbclose = '</tbody>';

        $table = $form . "
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='d-flex justify-content-between align-items-center mb-3 flex-wrap'>
                        <div>
                            <h1 class='h3 mb-1 text-gray-800'>Listado</h1> <em>(Total: " . $count . ")</em>
                        </div>

                        <div class='input-search'>
                            <i class='fas fa-search'></i>
                            <input type='text' id='searchFamesaTruckTable' placeholder='Buscar por nave, patente, guía...' class='form-control form-control-sm'>
                        </div>
                    </div>

                    <div class='card shadow mb-4'>
                        <div class='table-responsive' style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
                            <table id='famesaTruckTable' class='table' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
                                <thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>
                                " . str_replace("<thead style='background-color:#4e73df; color:white;'>", '', $thead) . '
                                ' . $tr . $tbclose . "
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('searchFamesaTruckTable').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase().trim();
                    let rows = document.querySelectorAll('#famesaTruckTable tbody tr');

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

    public function downloadTableTrucksFamesaExcel($nave = '-', $patente = '-', $guia = '')
    {
        $ship = new ship();

        /* Filtros */
        $conditions = ['1'];
        $params = [];

        if ($nave !== '-') {
            $conditions[] = 'sh.ship_id = :nave';
            $params[':nave'] = $nave;
        }

        if ($patente !== '-') {
            $conditions[] = 'f.car_plate_truck = :patente';
            $params[':patente'] = $patente;
        }

        if (!empty($guia)) {
            $conditions[] = 'f.guide_number LIKE :guia';
            $params[':guia'] = "%$guia%";
        }

        $whereClause = 'WHERE ' . implode(' AND ', $conditions);

        $query = "SELECT f.*, sh.*
            FROM $this->table AS f
            JOIN app_ships AS sh ON sh.ship_id = f.vessel_id
            $whereClause
            ORDER BY f.counter_vessel ASC";

        $stmt = $this->db->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Spreadsheet */
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Cabeceras */
        $headers = [
          'Posición', 'Nave', 'Patente Camión', 'Patente Rampla', 'Guía(s)',
          'Cant. Maxi Sacos', 'Categoría', 'Entrada Puerto', 'Salida Puerto',
          'Entrada Depósito', 'Salida Depósito', 'Creado', 'Digitado Por',
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        /* Datos */
        $row = 2;
        var_dump($query);
        foreach ($data as $d) {
            $sheet->setCellValue("A$row", $d[$this->countervessel]);
            $sheet->setCellValue("B$row", $ship->getVesselName($d['vessel_id']));
            $sheet->setCellValue("C$row", formatCarPlate($d[$this->carplatetruck]));
            $sheet->setCellValue("D$row", formatCarPlate($d[$this->carplateramp]));
            $sheet->setCellValue("E$row", $d[$this->guide]);
            $sheet->setCellValue("F$row", $d[$this->maxibags]);
            $sheet->setCellValue("G$row", $d[$this->category]);
            $sheet->setCellValue("H$row", formatDate($d[$this->arrivaldateport]));
            $sheet->setCellValue("I$row", formatDate($d[$this->departuredateport]));
            $sheet->setCellValue("J$row", formatDate($d[$this->arrivaldatedeposit]));
            $sheet->setCellValue("K$row", formatDate($d[$this->departuredatedeposit]));
            $sheet->setCellValue("L$row", formatDate($d[$this->created]));
            $sheet->setCellValue("M$row", $this->findByUser($d[$this->createdby]));
            $row++;
        }

        /* Descargar */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Camiones_Famesa_' . date('d-m-Y H:i:s') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }

    public function shiftsReportFamesa($shifts, $dateStart, $dateEnd)
    {
        $ship = new ship();
        $port = new port();

        list($inicio, $fin) = array_map('trim', explode(' - ', $shifts));
        $inicioDatetime = $dateStart . ' ' . $inicio . ':00';
        $finDatetime = $dateEnd . ' ' . $fin . ':00';
        $rows = $style = $stayTime = $status = '';
        $totalMaxiBags = $totalCamiones = 0;

        $sql = "SELECT
            f.counter_vessel,
            f.car_plate_truck,
            f.car_plate_ramp,
            f.guide_number,
            f.maxibags_quantity,
            f.category,
            f.arrival_date_port,
            f.departure_date_port,
            f.arrival_date_deposit,
            f.departure_date_deposit,
            f.observations,
            f.created_by,
            sh.ship_id,
            sh.pol,
            sh.pod,
            sh.eta,
            sh.etd,
            sh.ship_line,
            sh.voyage
            FROM $this->table f
            JOIN app_ships sh ON f.vessel_id = sh.ship_id
            WHERE f.arrival_date_port BETWEEN :inicio AND :fin
            ORDER BY $this->countervessel ASC
        ";

        $list = parent::findAllStatic($sql, ['inicio' => $inicioDatetime, 'fin' => $finDatetime]);
        if ($list->length()) {
            foreach ($list->getCollection() as $data) {
                $vessel = $ship->getVesselName($data['ship_id']);
                $shipLine = $ship->getShipLineName($data['ship_line']);

                $polFlag = $port->getflagImage($port->getCountryName($data['pol']));
                $polName = $port->getPortName($data['pol']);

                $podFlag = $port->getflagImage($port->getCountryName($data['pod']));
                $podName = $port->getPortName($data['pod']);

                $arrivalPort = (!empty($data[$this->arrivaldateport]) && $data[$this->arrivaldateport] !== '0000-00-00 00:00:00') ? formatDate($data[$this->arrivaldateport]) : '<em>No registra</em>';
                $departurePort = (!empty($data[$this->departuredateport]) && $data[$this->departuredateport] !== '0000-00-00 00:00:00') ? formatDate($data[$this->departuredateport]) : '<em>No registra</em>';
                $arrivalDeposit = (!empty($data[$this->arrivaldatedeposit]) && $data[$this->arrivaldatedeposit] !== '0000-00-00 00:00:00') ? formatDate($data[$this->arrivaldatedeposit]) : '<em>No registra</em>';
                $departureDeposit = (!empty($data[$this->departuredatedeposit]) && $data[$this->departuredatedeposit] !== '0000-00-00 00:00:00') ? formatDate($data[$this->departuredatedeposit]) : '<em>No registra</em>';

                if ($data[$this->arrivaldateport] !== '0000-00-00 00:00:00' && $data[$this->arrivaldateport] === null) {
                    // Llegó al puerto pero NO ha salido
                    $status = 'En Puerto';
                } elseif ($data[$this->arrivaldateport] !== '0000-00-00 00:00:00' && $data[$this->departuredateport] !== null && $data[$this->arrivaldatedeposit] === null) {
                    // Salió del puerto pero aún NO llega al depósito
                    $status = 'En Tránsito a Depósito';
                } elseif ($data[$this->arrivaldatedeposit] !== '0000-00-00 00:00:00' && $data[$this->departuredatedeposit] === null) {
                    // Llegó al depósito pero NO ha salido
                    $status = 'En Depósito';
                } elseif ($data[$this->departuredatedeposit] !== null) {
                    // Ya salió del depósito (proceso terminado)
                    $status = 'Finalizado';
                } else {
                    // Aún no entra al puerto
                    $status = 'Pendiente';
                }

                $rows .= "
                    <tr>
                        <td>{$data['counter_vessel']}</td>
                        <td>{$status}</td>
                        <td>{$data['car_plate_truck']}</td>
                        <td>{$data['car_plate_ramp']}</td>
                        <td>{$data['guide_number']}</td>
                        <td>{$data['maxibags_quantity']}</td>
                        <td>{$data['category']}</td>
                        <td>{$vessel}</td>
                        <td>{$shipLine}</td>
                        <td>{$polFlag} {$polName}</td>
                        <td>{$podFlag} {$podName}</td>
                        <td>{$arrivalPort}</td>
                        <td>{$departurePort}</td>
                        <td>{$arrivalDeposit}</td>
                        <td>{$departureDeposit}</td>
                        <td>{$this->findByUser($data[$this->createdby])}</td>
                    </tr>
                ";

                $style = "style='width:max-content'";
                $totalMaxiBags += (int) $data[$this->maxibags];
                $totalCamiones++;
            }

            $rows .= "
                <tr style='font-weight:bold;background:#f8f9fc'>
                <td colspan='9' class='text-right'>Totales</td>
                <td>Pallets: " . number_format($totalMaxiBags, 0, ',', '.') . "</td>
                <td colspan='8'>Camiones: " . number_format($totalCamiones, 0, ',', '.') . '</td>
                </tr>
            ';
        } else {
            $rows .= "
                <tr>
                <td colspan='16' class='text-center text-muted'><em>¡No se han encontrado resultados!</em></td>
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
                                <th>Patente Camión</th>
                                <th>Patente Rampla</th>
                                <th>Guía(s)</th>
                                <th>Cant. Maxi Sacos</th>
                                <th>Categoría</th>
                                <th>Nave</th>
                                <th>Linea</th>
                                <th>POL</th>
                                <th>POD</th>
                                <th>Entrada Puerto</th>
                                <th>Salida Puerto</th>
                                <th>Entrada Depósito</th>
                                <th>Salida Depósito</th>
                                <th>Digitado Por</th>
                            </tr>
                        </thead>
                        <tbody>$rows</tbody>
                    </table>
                </div>
            </div>
        ";
    }

}
