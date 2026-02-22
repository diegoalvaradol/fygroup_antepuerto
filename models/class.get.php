<?php
class get
{
  /**
   * Method arrayItemTracking //Item de tracking
   *
   * @return array
   */
  public static function arrayItemTracking()
  {
    $items = [
      1  => 'Despacho Vacio',
      2  => 'Frontera Ida',
      3  => 'Planta Cliente',
      4  => 'Despacho Planta Cliente',
      5  => 'Frontera Vuelta',
      6  => 'Antepuerto (Ingreso)',
      7  => 'Antepuerto (Salida)',
      8  => 'Stacking',
      9  => 'Zarpe',
      10 => 'Destino'
    ];

    return $items;
  }

  /**
   * Method arrayTypeOuterPort //Tipo de carga en antepuerto (Contenedor o Termo)
   *
   * @return void
   */
  public static function arrayTypeOuterPort()
  {
    $typeCharge = [
      1 => 'Contenedor',
      2 => 'Termo'
    ];

    return $typeCharge;
  }

  /**
   * Method getDivisionName //Devuelve el nombre de la División
   *
   * @return void
   */
  public static function getDivisionName()
  {
    $typesDivision = [
      'ssl'      => 'Personal SSL',
      'terminal' => 'Terminal',
      'shipper'  => 'Naviera'
    ];

    return $typesDivision;
  }

  /**
   * Method arrayYesNo //Array Si/No
   *
   * @return void
   */
  public static function arrayYesNo()
  {
    $arrayYesNo = [
      1 => 'Si',
      0 => 'No'
    ];

    return $arrayYesNo;
  }

  /**
   * Method arrayShifts //Array de turnos portuarios
   *
   * @return void
   */
  public static function arrayShifts()
  {
    $arrayShifts = [
      '08:00 - 15:30' => '1° Turno',
      '15:30 - 23:00' => '2° Turno',
      '23:00 - 08:00' => '3° Turno'
    ];

    return $arrayShifts;
  }

  /**
   * Method arrayTypeSchedule //Array de turnos portuarios
   *
   * @return void
   */
  public static function arrayTypeSchedule()
  {
    $arrayTypeSchedule = [
      'ARR' => 'Arrivados',
      'DEP' => 'Despachados'
    ];

    return $arrayTypeSchedule;
  }

}
