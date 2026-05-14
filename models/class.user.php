<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

class user extends iQuery
{
    protected string $table = 'app_users';
    protected string $primaryKey = 'user_id';

    public $id = 'user_id';
    public $run = 'run';
    public $name = 'name';
    public $lastname = 'last_name';
    public $email = 'email';
    public $password = 'password';
    public $division = 'division'; /* Indica si el usuario pertenece a FYGroup o Portal clientes */
    public $isadmin = 'is_admin'; /* Indica si el usuario es un usuario administrador */
    public $isadminedit = 'is_admin_edit'; /* Indica si el usuario es un usuario administrador editor */
    public $isactive = 'is_active'; /* Indica si el usuario se encuentra habilitado */
    public $lastsession = 'last_session'; /* Indica la hora del inicion de sesion (SOLO APLICA PARA USUARIOS PORTAL DE CLIENTE) */
    public $token = 'reset_token'; /* Token temporal al reestablecer contraseña */
    public $tokenexpiration = 'token_expiration'; /* Duración del token proporcionado (duración: 1 hora) */
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (run, name, last_name, email, password, division, created, last_update) VALUES (:run, :name, :lastname, :email, :password, :division, :created, :lastupdate)";
        $stmt = $this->db->prepare($query);

        $this->run = htmlspecialchars(strip_tags($this->run));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->division = htmlspecialchars(strip_tags($this->division));
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':run', $this->run, PDO::PARAM_STR);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':division', $this->division, PDO::PARAM_STR);
        $stmt->bindParam(':created', $this->created, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET password = :password, last_update = :lastupdate WHERE run = :run";

        $stmt = $this->db->prepare($query);

        $this->run = htmlspecialchars(strip_tags($this->run));

        $stmt->bindParam(':run', $this->run, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function login()
    {
        if (!$this->run || !$this->password) {
            return false;
        }

        $query = "SELECT run, name, last_name, email, password, division, is_active FROM $this->table WHERE run = :run AND division = :division AND is_active = 1 LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':run', $this->run, PDO::PARAM_STR);
        $stmt->bindParam(':division', $this->division);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            session_regenerate_id(true);

            unset($user['password']);

            $_SESSION['user'] = $user;
            $_SESSION['last_session'] = time();

            $updateQuery = 'UPDATE app_users SET last_session = NOW() WHERE run = :run';
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':run', $this->run, PDO::PARAM_STR);
            $updateStmt->execute();

            return $user;
        }

        return false;
    }

    public function setResetToken($email, $token, $expiration)
    {
        $query = "UPDATE $this->table SET reset_token = :token, token_expiration = :expiration WHERE email = :email";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expiration', $expiration, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function resetPassword($token, $newPassword)
    {
        $query = "SELECT * FROM $this->table WHERE reset_token = :token AND token_expiration > NOW() LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $update = "UPDATE $this->table SET password = :password, reset_token = :token, token_expiration = :expiration WHERE user_id = :id";
            $stmt2 = $this->db->prepare($update);

            $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
            $token = '';
            $expiration = '0000-00-00 00:00:00'; // Limpiar el token y la expiración después de restablecer la contraseña

            $stmt2->bindParam(':id', $user['user_id'], PDO::PARAM_STR); // o $user[$this->id] si está bien definido
            $stmt2->bindParam(':password', $this->password, PDO::PARAM_STR);
            $stmt2->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt2->bindParam(':expiration', $expiration, PDO::PARAM_STR);

            return $stmt2->execute();
        }

        return false;
    }

    public function isAdmin($run)
    {
        $query = "SELECT is_admin FROM {$this->table} WHERE run = :run AND division = 'FY' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':run', $run, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && (int) $result['is_admin'] === 1;
    }

    public function isAdminEdit($run)
    {
        $query = "SELECT is_admin_edit FROM {$this->table} WHERE run = :run AND division = 'FY' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':run', $run, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && (int) $result['is_admin_edit'] === 1;
    }

    public function avatarIniciales($nombre, $size = 40)
    {
        $partes = explode(' ', trim($nombre));
        $iniciales = '';

        foreach ($partes as $p) {
            $iniciales .= strtoupper(substr($p, 0, 1));
            if (strlen($iniciales) == 2) {
                break;
            }
        }

        /* Color según nombre */
        $hash = md5($nombre);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $color = "rgb($r,$g,$b)";

        $img = '
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold me-2"
            style="width:' . $size . 'px; height:' . $size . 'px; background:' . $color . '; font-size:' . ($size * 0.45) . 'px;">
            ' . $iniciales . '
            </div>
        ';

        return $img;
    }

    public function changeStatus()
    {
        $query = "UPDATE $this->table SET is_active = :isactive, last_update = :lastupdate WHERE run = :run";

        $stmt = $this->db->prepare($query);

        $this->run = htmlspecialchars(strip_tags($this->run));

        $stmt->bindParam(':run', $this->run, PDO::PARAM_STR);
        $stmt->bindParam(':isactive', $this->isactive, PDO::PARAM_INT);
        $stmt->bindParam(':lastupdate', $this->lastupdate, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getTableUser()
    {
        $arrayYesNo = get::arrayYesNo();
        $arrayDivision = get::getDivisionName();
        $count = 0;
        $tr = '';

        $query = "SELECT * FROM $this->table WHERE 1 AND $this->division = 'fy' ORDER BY user_id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $thead = "<thead style='background-color:#4e73df; color:white; top:0; z-index:1;'>";
        $thead .= '<tr>';
        $thead .= '<th>Id</th>';
        $thead .= '<th>RUN</th>';
        $thead .= '<th>Nombre</th>';
        $thead .= '<th>Apellido</th>';
        $thead .= '<th>Email</th>';
        $thead .= '<th>Contraseña</th>';
        $thead .= '<th>División</th>';
        $thead .= '<th>Administrador</th>';
        $thead .= '<th>Administrador Editor</th>';
        $thead .= '<th>¿Activo?</th>';
        $thead .= '<th>Últ. Sesión</th>';
        $thead .= '<th>Acciones</th>';
        $thead .= '</tr>';
        $thead .= '</thead><tbody>';

        foreach ($result as $data) {
            $lastSession = (new DateTime($data[$this->lastsession]))->format('d-m-Y H:i');
            $colorAdmin = $data[$this->isadmin] ? 'text-success' : 'text-danger';
            $colorAdminEdit = $data[$this->isadminedit] ? 'text-success' : 'text-danger';
            $colorActive = $data[$this->isactive] ? 'text-success' : 'text-danger';

            $btnRefresh = "<button class='btn btn-info btn-sm' onclick=\"resetPassword('{$data[$this->run]}')\"><i class='fas fa-refresh'></i> Resetar</button>";

            $btnDeshabilite = $data[$this->isactive] == 1
            ? "<button class='btn btn-danger btn-sm' onclick=\"changeStatusUser('{$data[$this->run]}', 0)\"><i class='fas fa-lock'></i> Deshabilitar</button>"
            : "<button class='btn btn-success btn-sm' onclick=\"changeStatusUser('{$data[$this->run]}', 1)\"><i class='fas fa-lock-open'></i> Habilitar</button>";

            $tr .= '<tr>';
            $tr .= "<td>{$data[$this->id]}</td>";
            $tr .= "<td>{$data[$this->run]}</td>";
            $tr .= "<td>{$data[$this->name]}</td>";
            $tr .= "<td>{$data[$this->lastname]}</td>";
            $tr .= "<td>{$data[$this->email]}</td>";
            $tr .= '<td>••••••••</td>';
            $tr .= "<td>{$arrayDivision[$data[$this->division]]}</td>";
            $tr .= "<td class='{$colorAdmin}'>{$arrayYesNo[$data[$this->isadmin]]}</td>";
            $tr .= "<td class='{$colorAdminEdit}'>{$arrayYesNo[$data[$this->isadminedit]]}</td>";
            $tr .= "<td class='{$colorActive}'>{$arrayYesNo[$data[$this->isactive]]}</td>";
            $tr .= "<td>{$lastSession}</td>";
            $tr .= "<td>{$btnRefresh} {$btnDeshabilite}</td>";
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
                        <em>(Total: <span id='totalUsers'>$count</span>)</em>
                    </h6>

                    <div class='input-search'>
                        <i class='fas fa-search' style='position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#6c757d; font-size:13px;'></i>
                        <input type='text' id='searchTableShip' placeholder='Buscar por run, nnombre' class='form-control form-control-sm' style='border-radius:20px; padding-left:30px;'>
                    </div>
                    </div>

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
                    const text = (
                    (row.cells[1]?.innerText || '') + ' ' +
                    (row.cells[2]?.innerText || '')
                    ).toLowerCase();

                    let match = text.includes(filter);

                    if (filter.includes(' ')) {
                    const words = filter.split(' ').filter(Boolean);
                    match = words.every(w => text.includes(w));
                    }

                    row.style.display = match ? '' : 'none';

                    if (match) visibleCount++;
                });

                document.getElementById('totalUsers').innerText = visibleCount;
                });
            </script>
        ";

        return $table;
    }

}
