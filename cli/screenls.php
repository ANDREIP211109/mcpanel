<?php

if(isset($_GET['start'])){
	echo shell_exec("php console.php 1 start");
}

if(isset($_GET['stop'])){
	echo shell_exec("php console.php 1 stop");
}

if(isset($_GET['cmd'])){
	echo shell_exec("php console.php 1 cmd {$_GET['cmd']}");
}

echo shell_exec("screen -ls");