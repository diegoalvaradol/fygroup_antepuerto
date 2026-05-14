<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

class company extends iQuery
{
    protected string $table = 'app_company';
    protected string $primaryKey = 'id';

    public $id = 'id';
    public $name = 'name';
    public $exporter = 'exporter';
    public $agency = 'agency';
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (name, exporter, agency, created, last_update) VALUES (:name, :exporter, :agency, :created, :lastupdate)";
        $stmt = $this->db->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->exporter = $this->exporter;
        $this->agency = $this->agency;
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':exporter', $this->exporter, PDO::PARAM_INT);
        $stmt->bindParam(':agency', $this->agency, PDO::PARAM_INT);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET name = :name, exporter = :exporter, agency = :agency, last_update = :lastupdate WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = $this->id;
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->exporter = $this->exporter;
        $this->agency = $this->agency;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':exporter', $this->exporter, PDO::PARAM_INT);
        $stmt->bindParam(':agency', $this->agency, PDO::PARAM_INT);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getCompanyName($lineId)
    {
        $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $lineId, PDO::PARAM_INT);
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

        $query = "SELECT * FROM $this->table WHERE name != 'N/A' ORDER BY $this->id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = 0;

        $thead = "<thead style='background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;'>";
        $thead .= '<tr>';
        $thead .= '<th>Id</th>';
        $thead .= '<th>Nombre</th>';
        $thead .= '<th>Tipo</th>';
        $thead .= '<th>Exportador</th>';
        $thead .= '<th>Agencia</th>';
        $thead .= '<th>Creado</th>';
        $thead .= '<th>Actualizado</th>';
        $thead .= '<th>Acciones</th>';
        $thead .= '</tr>';
        $thead .= '</thead><tbody>';

        $tr = '';

        foreach ($result as $data) {
            $created = (new DateTime($data[$this->created]))->format('d-m-Y H:i');
            $updated = (new DateTime($data[$this->lastupdate]))->format('d-m-Y H:i');

            $isExporter = $arrayYesNo[$data[$this->exporter]];
            $isAgency = $arrayYesNo[$data[$this->agency]];

            $type = '';
            if ($data[$this->exporter]) {
                $type = 'Exportador';
            }

            if ($data[$this->agency]) {
                $type = 'Agencia';
            }

            if ($data[$this->exporter] && $data[$this->agency]) {
                $type = 'Exportador/Agencia';
            }

            $btnEdit = "<button class='btn btn-warning btn-sm' onclick='editCompany(" . $data[$this->id] . ")'><i class='fas fa-pen'></i> Editar</button>";
            $btnDelete = "<button class='btn btn-danger btn-sm' onclick=\"deleteCompany(" . $data[$this->id] . ",'" . $data[$this->name] . "'," . $data[$this->exporter] . ',' . $data[$this->agency] . ")\"><i class='fas fa-trash'></i> Eliminar</button>";

            $tr .= '<tr>';
            $tr .= "<td>{$data[$this->id]}</td>";
            $tr .= "<td>{$data[$this->name]}</td>";
            $tr .= "<td><b>$type</b></td>";
            $tr .= "<td><b>$isExporter</b></td>";
            $tr .= "<td><b>$isAgency</b></td>";
            $tr .= "<td>$created</td>";
            $tr .= "<td>$updated</td>";
            $tr .= "<td>$btnEdit $btnDelete</td>";
            $tr .= '</tr>';

            $count++;
        }

        $table = "
            <div class='row'>
                <div class='col-lg-12'>
                <div class='card shadow mb-4'>
                    <div class='card-header bg-primary text-white d-flex justify-content-between align-items-center'>
                    <h6 class='mb-0'>
                        <i class='fas fa-list'></i> Listado
                        <em>(Total: <span id='totalCompanies'>$count</span>)</em>
                    </h6>

                    <div class='input-search'>
                        <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                        <input type='text' id='searchCompanyTable' placeholder='Buscar por nombre' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
                    </div>
                    </div>

                    <div style='width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6;'>
                    <table id='companyTable' class='table table-hover mb-0' style='min-width:900px; white-space:nowrap;'>
                        $thead
                        $tr
                    </table>
                    </div>
                </div>
                </div>
            </div>

            <script>
                document.getElementById('searchCompanyTable').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase().trim();
                let rows = document.querySelectorAll('#companyTable tbody tr');
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

                document.getElementById('totalCompanies').innerText = visibleCount;
                });
            </script>
        ";

        return $table;
    }

}
