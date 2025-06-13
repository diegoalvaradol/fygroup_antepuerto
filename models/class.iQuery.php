<?php
require_once __DIR__ . '/../config/database.php';

abstract class iQuery {
	protected $db;
  protected $table;
  protected $primaryKey = 'id'; /* Id por defecto */

  public function __construct($pdo) {
		$this->db = $pdo;
	}

	public function length(): bool {
		$sql = "SELECT 1 FROM {$this->table} LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return (bool) $stmt->fetch();
	}

  public function find($id) {
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

	public function getFirstMember(string $sql, array $params = []): ?array {
		$stmt = $this->db->prepare($sql);
		foreach ($params as $key => $value) {
			$stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
		}

		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function findAllStatic(string $sql, array $params = []): array {
		$stmt = $this->db->prepare($sql);
		foreach ($params as $key => $value) {
			$stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
		}

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}