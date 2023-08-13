<?php
	require_once __DIR__."/../lib/Util.php";

	if(php_sapi_name() != "cli"){
		exit("Please use the command line!\n");
	}

	//$argv[1] = server(id)
	//$argv[2] = action(start,stop,restart,kill,killall,cmd)
	//$argv[3] = command(if $argv[2]=="cmd")

	if($argc<3){
		exit("Wrong arguments. Please use: \"php console.php {serverid} {start|stop|restart|kill|killall|cmd} {command}\"!\n");
	}

	if($argv[2]=='cmd'&&$argc<4){
		exit("Please enter a command for action 'cmd'!\n");
	}

	$srv=Util::$db->query('SELECT * FROM servers WHERE id = ?', $argv[1])->fetchArray();

	if(!$srv){
		exit("The server specified is invalid!\n");
	}

	$dir=$srv["dir"];

	switch($argv[2]){
		case 'start':
			shell_exec(
				"cd {$dir}; ".
				sprintf(
					SCREEN_START,
					escapeshellarg(SCREEN_PREFIX.$srv['name']),
					intval($srv['ram']/2),
					$srv['ram']
				)
			);
			break;
		case 'stop':
			shell_exec(
				sprintf(
					SCREEN_CMD,
					SCREEN_PREFIX.$srv['name'],
					'stop'
				).
				';'.
				'wait $!'.

				sprintf(
					SCREEN_KILL,
					escapeshellarg(SCREEN_PREFIX.$srv['name'])
				)
			);
			break;
		case 'restart':
			//stop the server
			shell_exec(
				sprintf(
					SCREEN_CMD,
					SCREEN_PREFIX.$srv['name'],
					'stop'
				).
				';'.
				'sleep 5;'.

				sprintf(
					SCREEN_KILL,
					escapeshellarg(SCREEN_PREFIX.$srv['name'])
				)
			);

			//start the server
			shell_exec(
				"cd {$dir}; ".
				sprintf(
					SCREEN_START,
					escapeshellarg(SCREEN_PREFIX.$srv['name']),
					intval($srv['ram']/2),
					$srv['ram']
				)
			);
			break;
		case 'kill':
			shell_exec(
				sprintf(
					SCREEN_KILL,
					escapeshellarg(SCREEN_PREFIX.$srv['name'])
				)
			);
			break;
		case 'cmd':
			echo shell_exec(
				sprintf(
					SCREEN_CMD, // Base command
					SCREEN_PREFIX.$srv['name'], // Screen Name
					str_replace(array('\\','"'),array('\\\\','\\"'),((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) ? stripslashes($argv[3]) : $argv[3])) // Server command
				)
			);
			break;
		case 'killall':
			shell_exec(SCREEN_KILLALL);
			break;
		default:
			exit("Invalid action {$argv[2]}!\n");
			break;
	}