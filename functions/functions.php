<?php
function generateMkey($module, $area = 'mySSL') {
	$secretKey = "SSL-CHILE-DIEGO_2025_0517";
	$time = time();
	$random = bin2hex(random_bytes(5));
	$token = md5($secretKey . $module . $time . $random);

	return './?pag=' . $module . '&area=' . $area . '&mkey=' . $token;
}