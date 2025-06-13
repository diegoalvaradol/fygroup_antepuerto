<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

class internationalChargue extends iQuery
{
  private $conexion;
  protected $table      = "app_international_chargue";
  protected $primaryKey = 'row_id';

  public $id              = "row_id";
  public $countervessel   = "counter_vessel";
  public $vessel          = "vessel_id";
  public $carplate        = "car_plate";
  public $container       = "container";
  public $seal            = "seal_number";
  public $guide           = "guide_number";
  public $exporter        = "exporter";
  public $pallets         = "pallets_quantity";
  public $namedriver      = 'name_driver';
  public $cellphonedriver = "cellphone_driver";
  public $digitedby       = "digited_by";
  public $created         = "created";
  public $lastupdate      = "last_update";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (counter_vessel, vessel_id, car_plate, container, seal_number, guide_number, exporter, pallets_quantity, name_driver, cellphone_driver, digited_by, created, last_update)";
		$query .=" VALUES (:countervessel, :vessel, :carplate, :container, :seal, :guide, :exporter, :pallets, :namedriver, :cellphonedriver, :digitedby, :created, :lastupdate)";
    $stmt  = $this->conexion->prepare($query);

		$this->countervessel   = htmlspecialchars(strip_tags($this->countervessel));
		$this->vessel          = htmlspecialchars(strip_tags($this->vessel));
		$this->carplate        = htmlspecialchars(strip_tags($this->carplate));
		$this->container       = htmlspecialchars(strip_tags($this->container));
		$this->seal            = htmlspecialchars(strip_tags($this->seal));
		$this->guide           = htmlspecialchars(strip_tags($this->guide));
		$this->exporter        = htmlspecialchars(strip_tags($this->exporter));
		$this->pallets         = htmlspecialchars(strip_tags($this->pallets));
		$this->namedriver      = htmlspecialchars(strip_tags($this->namedriver));
		$this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver));
		$this->digitedby       = htmlspecialchars(strip_tags($this->digitedby));
		$this->created         = $this->created;
		$this->lastupdate      = $this->lastupdate;

		$stmt->bindParam(":countervessel", $this->countervessel, PDO::PARAM_INT);
		$stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
		$stmt->bindParam(":carplate", $this->carplate, PDO::PARAM_STR);
		$stmt->bindParam(":container", $this->container, PDO::PARAM_STR);
		$stmt->bindParam(":seal", $this->seal, PDO::PARAM_STR);
		$stmt->bindParam(":guide", $this->guide, PDO::PARAM_STR);
		$stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_STR);
		$stmt->bindParam(":pallets", $this->pallets, PDO::PARAM_INT);
		$stmt->bindParam(":namedriver", $this->namedriver, PDO::PARAM_STR);
		$stmt->bindParam(":cellphonedriver", $this->cellphonedriver, PDO::PARAM_STR);
		$stmt->bindParam(":digitedby", $this->digitedby, PDO::PARAM_STR);
		$stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
		$stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

		if ($stmt->execute()) {
			$this->id = $this->conexion->lastInsertId();
			return true;
		}
		
		return false;
  }

  public function update()
  {
		$query = "UPDATE $this->table SET counter_vessel = :countervessel, vessel_id = :vessel, car_plate = :carplate, container = :container, seal_number = :seal, guide_number = :guide, exporter = :exporter, pallets_quantity = :pallets, name_driver = :namedriver, cellphone_driver = :cellphonedriver, digited_by = :digitedby, last_update = :lastupdate WHERE row_id = :id";
		$stmt  = $this->conexion->prepare($query);

		$this->id              = htmlspecialchars(strip_tags($this->id));
		$this->countervessel   = htmlspecialchars(strip_tags($this->countervessel));
		$this->vessel          = htmlspecialchars(strip_tags($this->vessel));
		$this->carplate        = htmlspecialchars(strip_tags($this->carplate));
		$this->container       = htmlspecialchars(strip_tags($this->container));
		$this->seal            = htmlspecialchars(strip_tags($this->seal));
		$this->guide           = htmlspecialchars(strip_tags($this->guide));
		$this->exporter        = htmlspecialchars(strip_tags($this->exporter));
		$this->pallets         = htmlspecialchars(strip_tags($this->pallets));
		$this->namedriver      = htmlspecialchars(strip_tags($this->namedriver));
		$this->cellphonedriver = htmlspecialchars(strip_tags($this->cellphonedriver));
		$this->digitedby       = htmlspecialchars(strip_tags($this->digitedby));
		$this->lastupdate      = $this->lastupdate;

		$stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
		$stmt->bindParam(":countervessel", $this->countervessel, PDO::PARAM_INT);
		$stmt->bindParam(":vessel", $this->vessel, PDO::PARAM_INT);
		$stmt->bindParam(":carplate", $this->carplate, PDO::PARAM_STR);
		$stmt->bindParam(":container", $this->container, PDO::PARAM_STR);
		$stmt->bindParam(":seal", $this->seal, PDO::PARAM_STR);
		$stmt->bindParam(":guide", $this->guide, PDO::PARAM_STR);
		$stmt->bindParam(":exporter", $this->exporter, PDO::PARAM_STR);
		$stmt->bindParam(":pallets", $this->pallets, PDO::PARAM_INT);
		$stmt->bindParam(":namedriver", $this->namedriver, PDO::PARAM_STR);
		$stmt->bindParam(":cellphonedriver", $this->cellphonedriver, PDO::PARAM_STR);
		$stmt->bindParam(":digitedby", $this->digitedby, PDO::PARAM_STR);
		$stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);
		
    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM $this->table WHERE row_id = :id";
    $stmt  = $this->conexion->prepare($query);

		$stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

		return $stmt->execute();
  }

}
