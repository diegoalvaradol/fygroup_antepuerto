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
    $types = [
      1 => 'Contenedor',
      2 => 'Termo'
    ];

    return $types;
  }

  /**
   * Method getDivisionName //Devuelve el nombre de la División
   *
   * @return void
   */
  public static function getDivisionName()
  {
    $types = [
      'ssl'      => 'Personal SSL',
      'terminal' => 'Terminal',
      'shipper'  => 'Naviera'
    ];

    return $types;
  }

}
