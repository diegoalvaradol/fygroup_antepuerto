<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

class shipLine extends iQuery
{
    protected string $table = 'app_ship_lines';
    protected string $primaryKey = 'line_id';

    public $id = 'line_id';
    public $name = 'name';
    public $rut = 'rut';
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (name, rut, created, last_update) VALUES (:name, :rut, :created, :lastupdate)";
        $stmt = $this->db->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->rut = htmlspecialchars(strip_tags($this->rut));
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':rut', $this->rut, PDO::PARAM_STR);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET name = :name, /*rut = :rut,*/ last_update = :lastupdate WHERE line_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        //$this->rut        = htmlspecialchars(strip_tags($this->rut));
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        //$stmt->bindParam(":rut", $this->rut, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table WHERE line_id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getLineName($lineId)
    {
        $query = "SELECT * FROM  $this->table WHERE $this->id = :lineId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lineId', $lineId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result > 0) {
            return $result[$this->name];
        } else {
            return '-';
        }
    }

    public function getIdByName($name)
    {
        $searchName = "%{$name}%";

        $query = "SELECT line_id FROM  $this->table WHERE $this->name LIKE :name LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $searchName, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result > 0) {
            return $result[$this->id];
        } else {
            return null;
        }
    }

    public function getTableShipLine(): string
    {
        $query = "SELECT * FROM {$this->table} WHERE 1 ORDER BY {$this->id} ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = count($result);

        ob_start();
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <div>
                        <h1 class="h3 mb-1 text-gray-800 d-inline">
                            Listado
                        </h1>

                        <em>
                            (Total:
                            <span id="totalShipLines">
                                <?= number_format($count, 0, ',', '.') ?>
                            </span>)
                        </em>
                    </div>

                    <div class="input-search">
                        <i class="fas fa-search"></i>
                        <input type="text"  id="searchTableShipLine" placeholder="Buscar por nombre" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="table-responsive"
                        style="width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;">

                        <table id="shipLineTable" class="table" style="min-width:700px; white-space:nowrap; border-collapse:separate; border-spacing:0;">
                            <thead style="background-color:#4e73df; color:white; position:sticky; top:0; z-index:1;">
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>R.U.T</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($result as $data): ?>
                                    <?php
                                    $created = formatDate($data[$this->created]);
                                    $updated = formatDate($data[$this->lastupdate]);
                                    ?>

                                    <tr>
                                        <td><?= $data[$this->id] ?></td>
                                        <td><?= htmlspecialchars($data[$this->name]) ?></td>
                                        <td><?= htmlspecialchars($data[$this->rut]) ?></td>
                                        <td><?= $created ?></td>
                                        <td><?= $updated ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editShipLine(<?= $data[$this->id] ?>)">
                                                <i class="fas fa-pen"></i> Editar
                                            </button>

                                            <button class="btn btn-danger btn-sm" onclick="deleteShipLine(<?= $data[$this->id] ?>)">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('searchTableShipLine').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase().trim();
                let rows = document.querySelectorAll('#shipLineTable tbody tr');
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

                    if (match) {
                        visibleCount++;
                    }
                });

                document.getElementById('totalShipLines').innerText = visibleCount;
            });
        </script>
        <?php

        return ob_get_clean();
    }
}
