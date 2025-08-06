<?php
require_once __DIR__ . '/../config/database.php';

abstract class iQuery
{
  protected $db;
  protected $table;
  protected $primaryKey = 'id'; /* Id por defecto */

  public function __construct($conexion)
  {
    $this->db = $conexion;
  }

  public function length(): bool
  {
    $sql  = "SELECT 1 FROM {$this->table} LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return (bool) $stmt->fetch();
  }

  public function find(int $id): array
  {
    $sql  = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      throw new Exception("Registro con ID {$id} no encontrado en {$this->table}");
    }

    return $result;
  }

  public function getFirstMember(string $sql, array $params = []): ?array
  {
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
      $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function findAllStatic(string $sql, array $params = []): array
  {
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
      $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function paginate($totalRegistros, $porPagina, $paginaActual, $urlBase = '?pagina=')
  {
    $totalPaginas = ceil($totalRegistros / $porPagina);

    $html = '<nav aria-label="Page navigation example"><ul class="pagination">';

    /* Anterior */
    $prevClass = ($paginaActual <= 1) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $prevClass . '">';
    $html .= '<a class="page-link" href="' . ($paginaActual > 1 ? $urlBase . ($paginaActual - 1) : '#') . '">Anterior</a>';
    $html .= '</li>';

    $rango = 2;

    for ($i = 1; $i <= $totalPaginas; $i++) {
      if (
        $i == 1 ||                        // siempre mostrar primera
        $i == $totalPaginas ||            // siempre mostrar última
        abs($i - $paginaActual) <= $rango // rango alrededor de la actual
      ) {
        $active = ($paginaActual == $i) ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '">';
        $html .= '<a class="page-link" href="' . $urlBase . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (
        abs($i - $paginaActual) == $rango + 1
      ) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
    }

    // Siguiente
    $nextClass = ($paginaActual >= $totalPaginas) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $nextClass . '">';
    $html .= '<a class="page-link" href="' . ($paginaActual < $totalPaginas ? $urlBase . ($paginaActual + 1) : '#') . '">Siguiente</a>';
    $html .= '</li>';

    $html .= '</ul>';
    $html .= '</nav>';

    return $html;
  }

}
