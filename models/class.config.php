<?php
class cfg
{
  private $conexion;
  protected $table = "app_config";

  public $id          = "id";
  public $mark        = "mark";
  public $name        = "name";
  public $version     = "version";
  public $compilation = "compilation";
  public $goals       = "goals";
  public $created     = "created";
  public $lastupdate  = "last_update";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (mark, name, version, compilation, goals, created, last_update) VALUES (:mark, :name, :version, :compilation, :goals, :created, :lastupdate)";
    $stmt  = $this->conexion->prepare($query);

    $this->mark        = htmlspecialchars(strip_tags($this->mark));
    $this->name        = htmlspecialchars(strip_tags($this->name));
    $this->version     = htmlspecialchars(strip_tags($this->version));
    $this->compilation = htmlspecialchars(strip_tags($this->compilation));
    $this->goals       = htmlspecialchars(strip_tags($this->goals));
    $this->created     = $this->created;
    $this->lastupdate  = $this->lastupdate;

    $stmt->bindParam(":mark", $this->mark);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":version", $this->version);
    $stmt->bindParam(":compilation", $this->compilation);
    $stmt->bindParam(":goals", $this->goals);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":lastupdate", $this->lastupdate);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET mark = :mark, name = :name, version = :version, compilation = :compilation, goals = :goals, last_update = :lastupdate WHERE id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->id          = htmlspecialchars(strip_tags($this->id));
    $this->mark        = htmlspecialchars(strip_tags($this->mark));
    $this->name        = htmlspecialchars(strip_tags($this->name));
    $this->version     = htmlspecialchars(strip_tags($this->version));
    $this->compilation = htmlspecialchars(strip_tags($this->compilation));
    $this->goals       = htmlspecialchars(strip_tags($this->goals));
    $this->lastupdate  = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":mark", $this->mark);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":version", $this->version);
    $stmt->bindParam(":compilation", $this->compilation);
    $stmt->bindParam(":goals", $this->goals);
    $stmt->bindParam(":lastupdate", $this->lastupdate);

    return $stmt->execute();
  }

  public function updateGoals()
  {
    $query = "UPDATE $this->table SET goals = :goals,last_update = :lastupdate WHERE id = :id";
    $stmt  = $this->conexion->prepare($query);

    $this->goals      = htmlspecialchars(strip_tags($this->goals));
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindParam(":goals", $this->goals);
    $stmt->bindParam(":lastupdate", $this->lastupdate);

    return $stmt->execute();
  }

  public function getInfo($id)
  {
    $query = "SELECT * FROM  $this->table WHERE $this->id = :id LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return json_encode($result);
  }


  
}
