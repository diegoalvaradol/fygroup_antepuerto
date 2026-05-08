<?php

class Modals
{
    private $infoCfg;
    private $arrayDivision;
    private $releasedTime;
    private $updateTime;

    public function __construct($infoCfg, $arrayDivision, $releasedTime, $updateTime)
    {
        $this->infoCfg = $infoCfg;
        $this->arrayDivision = $arrayDivision;
        $this->releasedTime = $releasedTime;
        $this->updateTime = $updateTime;
    }

    public function render()
    {
        ob_start();
        ?>
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white py-2 px-3">
                        <h6 class="modal-title font-weight-bold mb-0" id="logoutModalLabel">¿Deseas cerrar sesión?</h6>
                        <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Selecciona 'Cerrar sesión' si realmente deseas hacerlo.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        <a class="btn btn-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de ajustes-->
        <div class="modal fade" id="goalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white py-2 px-3">
                        <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Configurar Capacidad de Antepuerto</h6>
                        <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addGoalForm">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                <label>Capacidad:</label>
                                <input type="text" class="form-control form-control-user" id="goals" name="goals" value="<?php echo $this->infoCfg['goals']; ?>">
                                </div>
                            </div>

                            <button type="button" name="savenewgoals" class="btn btn-success btn-user btn-sm" onclick="saveNewGoals()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal del perfil de usuario-->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white py-2 px-3">
                        <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Perfil de: <?php echo $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . '.'; ?></h6>
                        <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="row justify-content-center">
                        <h6 class="modal-title" id="exampleModalLabel">División: <?php echo $this->arrayDivision[$_SESSION['user']['division']]; ?></h6>
                    </div>
                    <div class="modal-body">
                        <form id="editUserInfoForm">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="alert custom-alert-info" role="alert" style="font-size:85%;"> <i class="fa-solid fa-circle-info"></i> ¡Para guardar los cambios deberás ingresar tu contraseña actual!</div>
                                </div>
                                <div class="col-sm-12">
                                    <label>RUN:</label>
                                    <input type="text" class="form-control form-control-user" disabled value="<?php echo $_SESSION['user']['run']; ?>">
                                    <label>Nombre:</label>
                                    <input type="text" class="form-control form-control-user" id="name" name="name" value="<?php echo $_SESSION['user']['name']; ?>">
                                    <label>Apellido:</label>
                                    <input type="text" class="form-control form-control-user" id="lastname" name="lastname" value="<?php echo $_SESSION['user']['last_name']; ?>">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control form-control-user" id="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>">
                                    <label>Contraseña:</label>
                                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingresa tu contraseña actual" autocomplete="current-password">
                                </div>
                            </div>

                            <input type="hidden" id="run" name="run" value="<?php echo $_SESSION['user']['run']; ?>">
                            <input type="hidden" id="division" name="division" value="<?php echo $_SESSION['user']['division']; ?>">
                            <button type="button" name="saveinfouser" class="btn btn-success btn-user btn-sm" onclick="saveInfoUser()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal licencia del software-->
        <div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white py-2 px-3">
                        <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Licencia de Uso de Software</h6>
                        <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="container mt-4 p-3 border rounded" style="background-color: #f9f9f9;">
                            <h4 class="text-center mb-3">Licencia de Uso</h4>

                            <p><strong>Software:</strong> <?php echo $this->infoCfg['name']; ?></p>
                            <p><strong>Compilación:</strong> <?php echo $this->infoCfg['compilation']; ?></p>
                            <p><strong>Versión:</strong> <?php echo $this->infoCfg['version']; ?></p>
                            <p><strong>Titular:</strong> <?php echo $this->infoCfg['author']; ?></p>
                            <p><strong>Lanzamiento:</strong> <?php echo $this->releasedTime->format('d-m-Y H:i'); ?></p>
                            <p><strong>Últ. actualización:</strong> <?php echo $this->updateTime->format('d-m-Y H:i'); ?></p>

                            <hr>

                            <h6>1. Objeto</h6>
                            <p>
                            Esta licencia regula el uso del sistema desarrollado en PHP, JavaScript y MySQL,
                            destinado a la gestión operativa del cliente.
                            </p>

                            <h6>2. Licencia</h6>
                            <p>
                            Se concede una licencia <strong>no exclusiva, intransferible y revocable</strong>,
                            únicamente para uso interno. Cualquier otro uso requiere autorización escrita.
                            </p>

                            <h6>3. Derechos</h6>
                            <p>
                            El código fuente, estructura y diseño son propiedad de
                            <strong><?php echo $this->infoCfg['author']; ?></strong>.
                            </p>

                            <h6>4. Restricciones</h6>
                            <ul>
                            <li>No copiar, modificar ni distribuir el software.</li>
                            <li>No revender ni sublicenciar.</li>
                            <li>No realizar ingeniería inversa.</li>
                            <li>No usar en servicios que compitan directamente.</li>
                            </ul>

                            <h6>5. Condiciones de Pago y Soporte</h6>
                            <p>
                            Todo desarrollo, modificación o soporte solicitado deberá ser pagado
                            según lo acordado previamente entre las partes.
                            El acceso a nuevas versiones y soporte depende del cumplimiento de pagos.
                            </p>

                            <h6>6. Bloqueo por Incumplimiento de Pago</h6>
                            <p>
                            En caso de <strong>mora o incumplimiento en el pago</strong> de desarrollos,
                            modificaciones o servicios asociados:
                            </p>
                            <ul>
                            <li>El titular podrá <strong>suspender total o parcialmente el sistema</strong>.</li>
                            <li>Se podrá limitar acceso a funcionalidades críticas.</li>
                            <li>Se podrá bloquear el acceso hasta regularizar la deuda.</li>
                            <li>No se garantiza continuidad operativa durante el periodo de incumplimiento.</li>
                            </ul>
                            <p>
                            La reactivación del sistema estará sujeta al pago total de la deuda pendiente.
                            </p>

                            <h6>7. Garantía</h6>
                            <p>
                            El software se entrega "tal cual", sin garantías de funcionamiento continuo.
                            </p>

                            <h6>8. Terminación</h6>
                            <p>
                            El incumplimiento de esta licencia implica su término inmediato y la obligación
                            de dejar de usar el sistema.
                            </p>

                            <h6>9. Legislación</h6>
                            <p>
                            Regido por las leyes de Chile.
                            </p>

                            <p class="mt-4">
                            <strong>Firmado:</strong><br>
                            <?php echo $this->infoCfg['author']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }
}
