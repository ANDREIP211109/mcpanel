<?php

	define('SCREEN_PREFIX','mcpanel-');// + server name + server id
	define('SCREEN_START','/usr/bin/screen -dmS %s /usr/bin/java -Xms%sM -Xmx%sM -jar server.jar nogui');
	define('SCREEN_CMD','/usr/bin/screen -S %s -p 0 -X stuff "%s$(printf \\\\r)"');
	define('SCREEN_KILL','/usr/bin/screen -X -S %s quit');
	define('SCREEN_KILLALL','killall /usr/bin/screen');
	define('SERVER_DIR','/mcpanel/servers/');// + server name
	define('SERVER_JAR','https://download.getbukkit.org/spigot/spigot-%s.jar');