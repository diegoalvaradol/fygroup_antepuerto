<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

class cfg extends iQuery
{
    protected string $table = 'app_config';
    protected string $primaryKey = 'id';

    public $id = 'id';
    public $mark = 'mark';
    public $name = 'name';
    public $version = 'version';
    public $compilation = 'compilation';
    public $author = 'author';
    public $released = 'released_date';
    public $update = 'update_date';
    public $goals = 'goals';
    public $created = 'created';
    public $lastupdate = 'last_update';

    public function __construct()
    {
        parent::__construct(); // usa Database::get() desde iQuery
    }

    public function save()
    {
        $query = "INSERT INTO $this->table (mark, name, version, compilation, author, released_date, update_date, goals, created, last_update) VALUES (:mark, :name, :version, :compilation, :author, :released, :update, :goals, :created, :lastupdate)";
        $stmt = $this->db->prepare($query);

        $this->mark = htmlspecialchars(strip_tags($this->mark));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->version = htmlspecialchars(strip_tags($this->version));
        $this->compilation = htmlspecialchars(strip_tags($this->compilation));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->released = $this->released;
        $this->update = $this->update;
        $this->goals = htmlspecialchars(strip_tags($this->goals));
        $this->created = $this->created;
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':mark', $this->mark);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':version', $this->version);
        $stmt->bindParam(':compilation', $this->compilation);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':released', $this->released);
        $stmt->bindParam(':update', $this->update);
        $stmt->bindParam(':goals', $this->goals);
        $stmt->bindParam(':created', $this->created);
        $stmt->bindParam(':lastupdate', $this->lastupdate);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE $this->table SET mark = :mark, name = :name, version = :version, compilation = :compilation, author = :author, released_date = :released, update_date = :update, goals = :goals, last_update = :lastupdate WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->mark = htmlspecialchars(strip_tags($this->mark));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->version = htmlspecialchars(strip_tags($this->version));
        $this->compilation = htmlspecialchars(strip_tags($this->compilation));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->released = $this->released;
        $this->update = $this->update;
        $this->goals = htmlspecialchars(strip_tags($this->goals));
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':mark', $this->mark);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':version', $this->version);
        $stmt->bindParam(':compilation', $this->compilation);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':released', $this->released);
        $stmt->bindParam(':update', $this->update);
        $stmt->bindParam(':goals', $this->goals);
        $stmt->bindParam(':lastupdate', $this->lastupdate);

        return $stmt->execute();
    }

    public function updateGoals()
    {
        $query = "UPDATE $this->table SET goals = :goals,last_update = :lastupdate WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $this->goals = htmlspecialchars(strip_tags($this->goals));
        $this->lastupdate = $this->lastupdate;

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':goals', $this->goals);
        $stmt->bindParam(':lastupdate', $this->lastupdate);

        return $stmt->execute();
    }

    public function getInfo($id)
    {
        $query = "SELECT * FROM $this->table WHERE $this->id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public function getMysqlVersion()
    {
        $sql = 'SELECT VERSION() AS version';

        try {
            $stmt = $this->db->query($sql);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getTotalTables()
    {
        $sql = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getDatabaseSize()
    {
        $sql = ' SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = DATABASE()';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn() . ' MB';
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkServiceDB()
    {
        $sql = 'SELECT 1';

        try {
            $this->db->query($sql);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkHTTPS()
    {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    public function checkSession()
    {
        return isset($_SESSION['user']);
    }

    public function getServicesStatus()
    {
        return [
            'Base de Datos' => $this->checkServiceDB(),
            'HTTPS' => $this->checkHTTPS(),
            'Sesión' => $this->checkSession(),
            'Logs' => true,
            'Correo SMTP' => true, // luego lo puedes conectar real
            'Cron Jobs' => true,
            'API Interna' => true,
            'FTP' => true,
        ];
    }

    public function checkOpenSSL()
    {
        return extension_loaded('openssl');
    }

    public function checkCookies()
    {
        return ini_get('session.use_cookies') == 1;
    }

    public function checkLogsEnabled()
    {
        return ini_get('log_errors') == 1;
    }

    public function getLastLogs(int $limit = 10): array
    {
        $file = __DIR__ . '/logs/app.log';

        if (!file_exists($file)) {
            return [];
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // tomar últimas líneas
        $lines = array_slice($lines, -$limit);

        $logs = [];

        foreach ($lines as $line) {

            // formato esperado: time|level|user|message
            $parts = explode('|', $line);

            $logs[] = [
                'time' => $parts[0] ?? '',
                'level' => $parts[1] ?? 'INFO',
                'user' => $parts[2] ?? null,
                'message' => $parts[3] ?? '',
            ];
        }

        return array_reverse($logs);
    }

    public function getSecurityStatus()
    {
        return [
            'HTTPS' => $this->checkHTTPS(),
            'OpenSSL' => $this->checkOpenSSL(),
            'Sesión' => $this->checkSession(),
            'Cookies' => $this->checkCookies(),
            'CSRF' => true, // depende de tu implementación
            'Logs' => $this->checkLogsEnabled(),
        ];
    }

    public function getDiskUsage()
    {
        $total = @disk_total_space('/');
        $free = @disk_free_space('/');

        if (!$total || !$free) {
            return [
                'percent' => 0,
                'used_gb' => 0,
                'free_gb' => 0,
                'total_gb' => 0,
            ];
        }

        $used = $total - $free;

        return [
            'percent' => round(($used / $total) * 100),
            'used_gb' => round($used / 1024 / 1024 / 1024, 2),
            'free_gb' => round($free / 1024 / 1024 / 1024, 2),
            'total_gb' => round($total / 1024 / 1024 / 1024, 2),
        ];
    }

    public function getDatabaseName()
    {
        return $this->db->query('SELECT DATABASE()')->fetchColumn();
    }

}
