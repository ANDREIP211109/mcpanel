<?php
	require_once "lib/Util.php";

	$console = "cli/console.php";
	if($_POST['req']=='server_cmd'){
		echo shell_exec("php {$console} {$_POST['serverid']} cmd {$_POST['cmd']} 2>&1");
	}else if($_POST['req']=='server_log'){
		if(is_file($_POST['serverdir'] . "/logs/latest.log")) {
			// 1.7 logs
			echo McLogParse::logparse2(McLogParse::file_backread($_POST['serverdir'] . '/logs/latest.log', 100000000));
		} elseif(is_file($_POST['serverdir'] . "/server.log")) {
			// 1.6 and earlier
			echo McLogParse::logparse2(McLogParse::file_backread($_POST['serverdir'] . '/server.log', 100000000));
		} elseif(is_file($_POST['serverdir'] . "/proxy.log.0")) {
			// BungeeCord
			echo McLogParse::logparse2(McLogParse::file_backread($_POST['serverdir'] . '/proxy.log.0', 100000000));
		} else {
			echo "No log file found.";
		}
	}else if($_POST['req']=='server_running'){
		echo json_encode(!!strpos(`screen -ls`, SCREEN_PREFIX . $_POST['servername']));
	}else if($_POST['req']=='server_start'){
		echo shell_exec("php {$console} {$_POST['serverid']} start 2>&1");
	}else if($_POST['req']=='server_stop'){
		echo shell_exec("php {$console} {$_POST['serverid']} stop 2>&1");
	}

?>