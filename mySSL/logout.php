<?php
session_start();
session_unset();
session_destroy();

header("Location: ../mySSL/login.php");
exit();
