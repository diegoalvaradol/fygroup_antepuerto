<?php
session_start();
session_unset();
session_destroy();

//header("Location: ../portal/login.php");
//exit();
header("Location: login.php");
exit();
