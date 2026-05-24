<?php
require_once __DIR__ . '/../config/database.php';

abstract class iQuery
{
  protected PDO $db;
  protected string $table;
  protected string $primaryKey = 'id';

  public function __construct()
  {
    $this->db = Database::get(); // debe devolver PDO
  }

  public function getDb(): PDO
  {
    return $this->db;
  }

  public function length(): int
  {
    return $this->stmtCollection->rowCount();
  }

  public function find(int $id): array
  {
    $sql  = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      throw new Exception("Registro con ID {$id} no encontrado en {$this->table}");
    }

    return $result;
  }

  protected function bindParams(PDOStatement $stmt, array $params = []): void
  {
    foreach ($params as $key => $value) {
      if (is_int($value)) {
        $type = PDO::PARAM_INT;
      } elseif (is_bool($value)) {
        $type = PDO::PARAM_BOOL;
      } elseif (is_null($value)) {
        $type = PDO::PARAM_NULL;
      } else {
        $type = PDO::PARAM_STR;
      }

      $param = is_int($key) ? $key + 1 : ":$key";
      $stmt->bindValue($param, $value, $type);
    }
  }

  public function getFirstMember(string $sql, array $params = []): ?array
  {
    $stmt = $this->db->prepare($sql);
    $this->bindParams($stmt, $params);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /**
   * findAllStatic devuelve un objeto iterable con getCollection()
   */
  public function findAllStatic(string $sql, array $params = []): self
  {
    $stmt = $this->db->prepare($sql);
    $this->bindParams($stmt, $params);
    $stmt->execute();

    // Guardamos el PDOStatement en la instancia para iterar después
    $this->stmtCollection = $stmt;

    return $this;
  }

  /**
   * Devuelve iterable para usar en foreach
   */
  public function getCollection(): iterable
  {
    if (!isset($this->stmtCollection)) {
      throw new Exception("No hay resultados. Llama primero a findAllStatic()");
    }

    while ($row = $this->stmtCollection->fetch(PDO::FETCH_ASSOC)) {
      yield $row;
    }
  }

  public function paginate($totalRegistros, $porPagina, $paginaActual, $urlBase = '?page=')
  {
    if ($totalRegistros <= 0 || $porPagina <= 0) {
      return '';
    }

    $totalPaginas = ceil($totalRegistros / $porPagina);

    if ($totalPaginas <= 1) {
      return '';
    }

    $html = '<nav aria-label="Page navigation example">';
    $html .= '<ul class="pagination justify-content-center">';

    $prevClass = ($paginaActual <= 1) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $prevClass . '">';
    $html .= '<a class="page-link" href="' . ($paginaActual > 1 ? $urlBase . ($paginaActual - 1) : '#') . '">Anterior</a>';
    $html .= '</li>';

    $rango = 2;
    for ($i = 1; $i <= $totalPaginas; $i++) {
      if ($i == 1 || $i == $totalPaginas || abs($i - $paginaActual) <= $rango) {
        $active = ($paginaActual == $i) ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '">';
        $html .= '<a class="page-link" href="' . $urlBase . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (abs($i - $paginaActual) == $rango + 1) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
    }

    $nextClass = ($paginaActual >= $totalPaginas) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $nextClass . '">';
    $html .= '<a class="page-link" href="' . ($paginaActual < $totalPaginas ? $urlBase . ($paginaActual + 1) : '#') . '">Siguiente</a>';
    $html .= '</li>';

    $html .= '</ul></nav>';

    return $html;
  }

  // Propiedad interna para almacenar PDOStatement de getCollection
  private PDOStatement $stmtCollection;
}
