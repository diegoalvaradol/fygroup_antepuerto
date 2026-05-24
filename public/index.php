<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png" />
    <title>Sistema FYGroup | Antepuerto Panul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: #f8f9fc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            flex: 1;
        }

        .hero {
            background: #3787ba;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .logo {
            width: 280px;
            height: 280px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            margin-top: 40px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <!-- HERO -->
        <div class="hero">
            <div class="logo">
                <img src="../images/logo-fygroup-circle-bg-removed.png"
                    style="max-width:280px; width:90%; height:auto;">
            </div>

            <h2>Antepuerto Panul</h2>
            <h4>Sistema FYGroup</h4>
        </div>

        <!-- CONTENIDO -->
        <div class="container mt-4 mb-5">
            <div class="card p-4 mb-3">
                <p>
                    Sistema web para la gestión del Antepuerto Panul, enfocado en el control de ingreso de camiones
                    y en la generación de reportes operacionales del flujo logístico.
                </p>

                <p>
                    La plataforma centraliza información relevante de la operación del antepuerto, permitiendo
                    registrar movimientos, monitorear la actividad y obtener reportes que apoyen la gestión
                    y la toma de decisiones.
                </p>
            </div>

            <h5 class="section-title">Funcionalidades</h5>

            <div class="card p-4 mb-3">
                <ul>
                    <li>Control y registro de ingreso de camiones</li>
                    <li>Gestión de turnos de atención</li>
                    <li>Reporte de naves</li>
                    <li>Reporte de termos</li>
                    <li>Reporte de contenedores</li>
                    <li>Reporte de turnos</li>
                    <li>Liquidación de naves</li>
                    <li>Proforma de naves</li>
                    <li>Dashboard con indicadores de operación</li>
                    <li>Panel administrativo para la gestión del sistema</li>
                </ul>
            </div>

            <h5 class="section-title">Integraciones</h5>

            <div class="card p-4 mb-3">
                <p>El sistema consume APIs de distintas compañías:</p>

                <ul>
                    <li>Maersk</li>
                    <li>MSC</li>
                    <li>EPCO</li>
                    <li>TPC</li>
                    <li>Seatrade</li>
                    <li>Cool Carriers</li>
                </ul>

                <p>
                    Estas integraciones permiten mantener información actualizada sobre naves,
                    cargas y movimientos logísticos.
                </p>
            </div>

            <h5 class="section-title">Interfaz</h5>

            <div class="card p-4 mb-3">
                <p>
                    Basada en el template SB Admin 2 sobre Bootstrap,
                    proporcionando una estructura moderna y responsiva.
                </p>

                <p>
                    <strong>Template:</strong><br>
                    <a href="https://startbootstrap.com/theme/sb-admin-2/" target="_blank">
                        SB Admin 2
                    </a>
                </p>

                <p>
                    <strong>Framework UI:</strong><br>
                    <a href="https://getbootstrap.com/" target="_blank">
                        Bootstrap
                    </a>
                </p>
            </div>

            <h5 class="section-title">Tecnologías utilizadas</h5>

            <div class="card p-4 mb-3">
                <ul>
                    <li>PHP</li>
                    <li>JavaScript</li>
                    <li>Bootstrap</li>
                    <li>jQuery</li>
                    <li>PHPMyAdmin</li>
                    <li>Integración con APIs externas</li>
                </ul>
            </div>

            <h5 class="section-title">Objetivo</h5>

            <div class="card p-4 mb-3">
                <p>
                    Centralizar y administrar la información operativa del Antepuerto Panul,
                    permitiendo controlar el acceso de camiones y generar reportes de la operación logística.
                </p>
            </div>

            <h5 class="section-title">Licencia del Template</h5>

            <div class="card p-4 mb-5">
                <p>
                    El diseño utiliza el template SB Admin 2,
                    liberado bajo licencia MIT por Start Bootstrap.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
