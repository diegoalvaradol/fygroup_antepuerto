<?php

declare(strict_types=1);
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
          1 => 'Despacho Vacio',
          2 => 'Frontera Ida',
          3 => 'Planta Cliente',
          4 => 'Despacho Planta Cliente',
          5 => 'Frontera Vuelta',
          6 => 'Antepuerto (Ingreso)',
          7 => 'Antepuerto (Salida)',
          8 => 'Stacking',
          9 => 'Zarpe',
          10 => 'Destino',
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
          2 => 'Termo',
        ];

        return $typeCharge;
    }

    /**
     * Method getDivisionName //Devuelve el nombre de la División
     *
     * @return String
     */
    public static function getDivisionName()
    {
        $typesDivision = [
          'fy' => 'Personal FYGroup',
          'terminal' => 'Terminal',
          'shipper' => 'Naviera',
        ];

        return $typesDivision;
    }

    /**
     * Method arrayYesNo //Array Si/No
     *
     * @return String
     */
    public static function arrayYesNo()
    {
        $arrayYesNo = [
          1 => 'Si',
          0 => 'No',
        ];

        return $arrayYesNo;
    }

    /**
     * Method arrayShifts //Array de turnos portuarios
     *
     * @return String
     */
    public static function arrayShifts()
    {
        $arrayShifts = [
          '08:00 - 15:30' => '1° Turno',
          '15:30 - 23:00' => '2° Turno',
          '23:00 - 08:00' => '3° Turno',
        ];

        return $arrayShifts;
    }

    /**
     * Method arrayShifts //Array de turnos portuarios
     *
     * @return void
     */
    public static function arrayShiftsFamesa()
    {
        $arrayShiftsFamesa = [
          '08:00 - 20:00' => '1° Turno',
          '20:00 - 08:00' => '2° Turno',
        ];

        return $arrayShiftsFamesa;
    }

    /**
     * Method arraySeasons //Array de temporadas
     *
     * @return Object
     */
    public static function arraySeasons()
    {
        $arraySeasons = [
            [
                'start' => '2025-01-01',
                'end' => '2025-03-31',
                'label' => 'Temporada 24/25',
                'season' => 'summer',
            ],
            [
                'start' => '2025-05-01',
                'end' => '2025-08-15',
                'label' => 'Cítricos 2025',
                'season' => 'citrus',
            ],
            [
                'start' => '2026-01-01',
                'end' => '2026-02-28',
                'label' => 'Temporada 25/26',
                'season' => 'summer',
            ],
            [
                'start' => '2026-05-08',
                'end' => '2026-07-15',
                'label' => 'Cítricos 2026',
                'season' => 'citrus',
            ],
        ];

        return $arraySeasons;
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
          'DEP' => 'Despachados',
        ];

        return $arrayTypeSchedule;
    }
}
