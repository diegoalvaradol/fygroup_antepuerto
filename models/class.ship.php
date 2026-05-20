<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
class ship extends iQuery
{
    protected string $table = 'app_ships';
    protected string $primaryKey = 'ship_id';

    public $id = 'ship_id';
    public $vessel = 'vessel_name';
    public $voyage = 'voyage';
    public $pol = 'pol';
    public $pod = 'pod';
    public $line = 'ship_line';
    public $finished = 'finished'; /* Indica si el emabrque de la motonave finalizo [0 => No, 1 => Si] */
    public $finisheddate = 'finished_date'; /* Fecha de finalizacion del embarque */
    public $eta = 'eta';
    public $etd = 'etd';
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (vessel_name, ship_line, voyage, pol, pod, eta, etd, finished, created, last_update) VALUES (:vessel, :shipline, :voyage, :pol, :pod, :eta, :etd, :finished, :created, :lastupdate)";
        $stmt = $this->db->prepare($query);

        $this->vessel = htmlspecialchars(strip_tags($this->vessel));
        $this->line = htmlspecialchars(strip_tags($this->line));
        $this->voyage = htmlspecialchars(strip_tags($this->voyage));
        $this->pol = htmlspecialchars(strip_tags($this->pol));
        $this->pod = htmlspecialchars(strip_tags($this->pod));
        $this->eta = $this->eta;
        $this->etd = $this->etd;
        $this->finished = $this->finished;
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_STR);
        $stmt->bindParam(':shipline', $this->line, PDO::PARAM_INT);
        $stmt->bindParam(':voyage', $this->voyage, PDO::PARAM_STR);
        $stmt->bindParam(':pol', $this->pol, PDO::PARAM_INT);
        $stmt->bindParam(':pod', $this->pod, PDO::PARAM_INT);
        $stmt->bindParam(':eta', $this->eta, PDO::PARAM_STR);
        $stmt->bindParam(':etd', $this->etd, PDO::PARAM_STR);
        $stmt->bindParam(':finished', $this->finished, PDO::PARAM_INT);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET vessel_name = :vessel, ship_line = :shipline, voyage = :voyage, pol = :pol, pod = :pod, eta = :eta, etd = :etd, last_update = :lastupdate WHERE ship_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->vessel = htmlspecialchars(strip_tags($this->vessel));
        $this->line = htmlspecialchars(strip_tags($this->line));
        $this->voyage = htmlspecialchars(strip_tags($this->voyage));
        $this->pol = htmlspecialchars(strip_tags($this->pol));
        $this->pod = htmlspecialchars(strip_tags($this->pod));
        $this->eta = $this->eta;
        $this->etd = $this->etd;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':vessel', $this->vessel, PDO::PARAM_STR);
        $stmt->bindParam(':shipline', $this->line, PDO::PARAM_INT);
        $stmt->bindParam(':voyage', $this->voyage, PDO::PARAM_STR);
        $stmt->bindParam(':pol', $this->pol, PDO::PARAM_INT);
        $stmt->bindParam(':pod', $this->pod, PDO::PARAM_INT);
        $stmt->bindParam(':eta', $this->eta, PDO::PARAM_STR);
        $stmt->bindParam(':etd', $this->etd, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table WHERE ship_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function endStacking()
    {
        $query = "UPDATE $this->table SET finished = :finished, finished_date = :finisheddate, last_update = :lastupdate WHERE ship_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->finished = htmlspecialchars(strip_tags($this->finished));
        $this->finisheddate = $this->finisheddate;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':finished', $this->finished, PDO::PARAM_INT);
        $stmt->bindParam(':finisheddate', $this->finisheddate, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getVesselName($vesselId)
    {
        $query = "SELECT * FROM $this->table WHERE $this->id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $vesselId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result > 0 ? $result[$this->vessel] : '-';
    }

    public function getShipLineName($shipLineId)
    {
        $query = 'SELECT * FROM `app_ship_lines` WHERE line_id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $shipLineId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result > 0 ? $result['name'] : '-';
    }

    public function getTableShip()
    {
        $port = new port();
        $shipLine = new shipLine();
        $count = 0;

        $query = "SELECT * FROM $this->table WHERE 1 ORDER BY ship_id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $thead = "<thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>";
        $thead .= '<tr>';
        $thead .= '<th>Id</th>';
        $thead .= '<th>Nave</th>';
        $thead .= '<th>Linea</th>';
        $thead .= '<th>Viaje</th>';
        $thead .= '<th>Puerto de Carga</th>';
        $thead .= '<th>Puerto de Descarga</th>';
        $thead .= '<th>Arrivo</th>';
        $thead .= '<th>Zarpe</th>';
        $thead .= '<th>Creado</th>';
        $thead .= '<th>Actualizado</th>';
        $thead .= '<th>Embarque</th>';
        $thead .= '<th>Acciones</th>';
        $thead .= '</tr>';
        $thead .= '</thead><tbody>';

        $tr = '';

        foreach ($result as $data) {
            $created = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
            $updated = (new DateTime($data[$this->lastupdate]))->format('d-m-Y H:i');
            $eta = (new DateTime($data[$this->eta]))->format('d-m-Y H:i');
            $etd = (new DateTime($data[$this->etd]))->format('d-m-Y H:i');

            $finish = ($data[$this->finisheddate]) ? (new DateTime($data[$this->finisheddate]))->format('d-m-Y H:i') : 'Por estimar.';

            $btnFinishedDate = '
                <i class="fas fa-info-circle text-info"
                role="button"
                tabindex="0"
                data-toggle="popover"
                data-html="true"
                data-trigger="hover focus"
                data-placement="right"
                data-content="<b>Cierre:</b> ' . htmlspecialchars($finish) . '">
                </i>
            ';

            $btnEndStacking = $data[$this->finished] == 0
            ? "<button class='btn btn-danger btn-sm' onclick='stackingShip(" . $data[$this->id] . ',"' . $data[$this->vessel] . '","' . $data[$this->voyage] . "\",1)'><i class='fas fa-lock'></i> Cerrar</button>"
            : "<button class='btn btn-success btn-sm' onclick='stackingShip(" . $data[$this->id] . ',"' . $data[$this->vessel] . '","' . $data[$this->voyage] . "\",0)'><i class='fas fa-lock-open'></i> Abrir</button>";

            $btnEdit = "<button class='btn btn-warning btn-sm' onclick='editShip(" . $data[$this->id] . ")'><i class='fas fa-pen'></i> Editar</button>";
            $btnDelete = "<button class='btn btn-danger btn-sm' onclick='deleteShip(" . $data[$this->id] . ")'><i class='fas fa-trash'></i> Eliminar</button>";

            $tr .= '<tr>';
            $tr .= "<td>{$data[$this->id]}</td>";
            $tr .= "<td>{$data[$this->vessel]}</td>";
            $tr .= '<td>' . $shipLine->getLineName($data[$this->line]) . '</td>';
            $tr .= "<td>{$data[$this->voyage]}</td>";
            $tr .= '<td>' . $port->getflagImage($port->getCountryName($data[$this->pol])) . ' ' . $port->getPortName($data[$this->pol]) . '</td>';
            $tr .= '<td>' . $port->getflagImage($port->getCountryName($data[$this->pod])) . ' ' . $port->getPortName($data[$this->pod]) . '</td>';
            $tr .= "<td>{$eta}</td>";
            $tr .= "<td>{$etd}</td>";
            $tr .= "<td>{$created}</td>";
            $tr .= "<td>{$updated}</td>";
            $tr .= "<td>{$btnEndStacking} {$btnFinishedDate}</td>";
            $tr .= "<td>{$btnEdit} {$btnDelete}</td>";
            $tr .= '</tr>';

            $count++;
        }

        $table = "
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='d-flex justify-content-between align-items-center mb-3 flex-wrap'>
                        <div>
                            <h1 class='h3 mb-1 text-gray-800 d-inline'>
                                Listado
                            </h1>

                            <em>
                                (Total:
                                <span id='totalShips'>" . number_format($count, 0, ',', '.') . "</span>)
                            </em>
                        </div>

                        <div class='input-search'>
                            <i class='fas fa-search'></i>
                            <input type='text' id='searchTableShip' placeholder='Buscar por nave' class='form-control form-control-sm'>
                        </div>
                    </div>

                    <div class='card shadow mb-4'>
                        <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;'>
                            <table id='shipTable' class='table table-hover mb-0' style='min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;'>
                                $thead
                                $tr
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('searchTableShip').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase().trim();
                    let rows = document.querySelectorAll('#shipTable tbody tr');
                    let visibleCount = 0;

                    rows.forEach(row => {
                        let cell = row.cells[1];
                        let text = cell ? cell.innerText.toLowerCase() : '';
                        let match = text.includes(filter);

                        if (filter.includes(' ')) {
                        let words = filter.split(' ');
                        match = words.every(w => text.includes(w));
                        }

                        row.style.display = match ? '' : 'none';

                        if (match) visibleCount++;
                    });

                    document.getElementById('totalShips').innerText = visibleCount;
                });
            </script>
        ";

        return $table;
    }
}
