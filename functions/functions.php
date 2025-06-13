<?php
function generateMkey($module, $area = 'mySSL') {
	$secretKey = "SSL-CHILE-DIEGO_2025_0517";
	$time = time();
	$random = bin2hex(random_bytes(5));
	$token = md5($secretKey . $module . $time . $random);

	return './?pag=' . $module . '&area=' . $area . '&mkey=' . $token;
}

function esLocalhost()
{
  $whitelist = ['127.0.0.1', '::1', 'localhost'];

  return in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_NAME'], $whitelist);
}