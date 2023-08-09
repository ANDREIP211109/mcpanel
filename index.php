<?php

require_once 'lib/Util.php';

$page=(Util::$url[0] ?? 'home').'.php';


if(!file_exists($page)){
	exit('Error 404: Not Found');
}

require_once 'base.php';