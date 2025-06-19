<?php
require_once __DIR__ . '/../config/database.php';

abstract class iQuery {
	protected $db;
  protected $table;
  protected $primaryKey = 'id'; /* Id por defecto */

  public function __construct($conexion) {
    $this->db = $conexion;
  }

	public function length(): bool {
		$sql = "SELECT 1 FROM {$this->table} LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return (bool) $stmt->fetch();
	}

  public function find(int $id): array {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if (!$result) {
			throw new Exception("Registro con ID {$id} no encontrado en {$this->table}");
		}
	
		return $result;
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