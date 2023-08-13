<?php
	if(!Session::getUser()){
		FlashMessage::add("Please Login!");
		Http::redirect("{$wwwRoot}login");
	}

	$servers=Util::$db->query('SELECT * FROM servers WHERE userid = ?', Session::getUser())->fetchAll();
	if(!isset(Util::$url[1])){
		$currsrv=array('id'=> 0, 'name' => 'N/A', 'userid' => 'N/A', 'ram' => 'N/A', 'port' => 'N/A', 'ip' => 'N/A', 'dir' => 'N/A', 'version' => 'N/A');
	}else{
		$currsrv=Util::$db->query('SELECT * FROM servers WHERE id = ?', Util::$url[1])->fetchArray();
	}

	$currstatus = "<i style=\"color: red;\" class=\"bi bi-circle-fill\"></i>";
	if(!!strpos(`screen -ls`, SCREEN_PREFIX . $currsrv['name'])){
		$currstatus = "<i style=\"color: green;\" class=\"bi bi-circle-fill\"></i>";
	}

?>

<div style="display:none;" id="srvid"><?=$currsrv['id']?></div>
<div style="display:none;" id="srvdir"><?=$currsrv['dir']?></div>
<div style="display:none;" id="srvname"><?=$currsrv['name']?></div>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                  <li class="nav-item">
                    <i class="fs-4"></i> <span class="fs-5 ms-1 d-none d-sm-inline">Server Name: <?=$currsrv['name']?></span>
                  </li>
                  <li class="nav-item">
                    <i class="fs-4"></i> <span class="fs-5 ms-1 d-none d-sm-inline">Server IP: <?=$currsrv['ip']?></span>
                  </li>
                  <li class="nav-item">
                    <i class="fs-4"></i> <span class="fs-5 ms-1 d-none d-sm-inline">Server RAM: <?=$currsrv['ram']?> mb</span>
                  </li>
                  <li class="nav-item">
                    <i class="fs-4"></i> <span id="status" class="fs-5 ms-1 d-none d-sm-inline">Server Status: <?=$currstatus?></span>
                  </li>
                  <li class="nav-item">
                    <div class="btn-toolbar">
											<div class="btn-group">
												<button class="btn btn-large btn-primary ht" id="btn-srv-start" title="Start" disabled><i class="fs-4 bi bi-play-fill"></i></button>
												<button class="btn btn-large btn-danger ht" id="btn-srv-stop" title="Stop" disabled><i class="fs-4 bi bi-stop-fill"></i></button>
											</div>
											<div class="btn-group">
												<button class="btn btn-large btn-warning ht" id="btn-srv-restart" title="Restart" disabled><i class="fs-4 bi bi-arrow-clockwise"></i></button>
											</div>
										</div>
                  </li>
                </ul>
            </div>
        </div>
        <div class="col-10">  
					<nav>
					  <div class="nav nav-tabs" id="nav-tab" role="tablist">
					  	<?php foreach($servers as $srv){ ?>
					    	<button class="nav-link active show" id="nav-<?=$srv['name']?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?=$srv['name']?>" type="button" role="tab" aria-controls="nav-<?=$srv['name']?>" aria-selected="true" onclick='window.location.href="<?=Util::$wwwRoot?>home/<?=$srv['id']?>"'><?=$srv['name']?></button>
					  	<?php } ?>
					  </div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<?php foreach($servers as $srv){ ?>
					  	<div class="tab-pane fade show active" id="nav-<?=$srv['name']?>" role="tabpanel" aria-labelledby="nav-<?=$srv['name']?>-tab">
					  		<!-- console -->
					  		<div class="span7">
									<pre id="log" class="well well-small "></pre>
									<form id="frm-cmd" method="post">
										<input type="text" id="cmd" name="cmd" maxlength="250" placeholder="Enter a command" autofocus>
									</form>

									<script type="text/javascript">
										function refreshLog() {
											$.post('<?=Util::$wwwRoot?>ajax.php', {
												req: 'server_log',
												serverdir: $('#srvdir').html()
											}, function (data) {
												if ($('#log').scrollTop() == $('#log')[0].scrollHeight) {
													$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
												} else {
													$('#log').html(data);
												}
												window.setTimeout(refreshLog, 4000);
											});
										}
										function refreshLogOnce() {
											$.post('<?=Util::$wwwRoot?>ajax.php', {
												req: 'server_log',
												serverdir: $('#srvdir').html()
											}, function (data) {
												$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
											});
										}

										function updateStatus(once) {
											$.post('<?=Util::$wwwRoot?>ajax.php', {
												req: 'server_running',
												servername: $('#srvname').html()
											}, function (data) {
												console.log(data);
												if (data) {
													$('#status').html("Server Status: <i style=\"color: green;\" class=\"bi bi-circle-fill\"></i>");
													$('#btn-srv-start').prop('disabled', true);
													$('#btn-srv-stop,#btn-srv-restart').prop('disabled', false);
													$('#cmd').prop('disabled', false);
												} else {
													$('#status').html("Server Status: <i style=\"color: red;\" class=\"bi bi-circle-fill\"></i>");
													$('#btn-srv-start').prop('disabled', false);
													$('#btn-srv-stop,#btn-srv-restart').prop('disabled', true);
													$('#cmd').prop('disabled', true);
												}
											}, 'json');
											if (!once)
												window.setTimeout(updateStatus, 5000);
										}

										$(document).ready(function () {
											updateStatus();
											//updatePlayers();
											$('button.ht').tooltip();
											$('#btn-srv-start').click(function () {
												$.post('<?=Util::$wwwRoot?>ajax.php', {
													req: 'server_start',
													serverid: $('#srvid').html()
												}, function () {
													$(this).prop('disabled', true).tooltip('hide');
													refreshLogOnce();
												});
											});
											$('#btn-srv-stop').click(function () {
												$.post('<?=Util::$wwwRoot?>ajax.php', {
													req: 'server_stop',
													serverid: $('#srvid').html()
												}, function () {
													$(this).prop('disabled', true).tooltip('hide');
													refreshLogOnce();
												});
											});
											$('#btn-srv-restart').click(function () {
												$.post('<?=Util::$wwwRoot?>ajax.php', {
													req: 'server_stop',
													serverid: $('#srvid').html()
												}, function () {
													refreshLogOnce();
												});

												$.post('<?=Util::$wwwRoot?>ajax.php', {
													req: 'server_start',
													serverid: $('#srvid').html()
												}, function () {
													$('').prop('disabled', true).tooltip('hide');
													refreshLogOnce();
												});
											});
											$('#frm-cmd').submit(function () {
												$.post('<?=Util::$wwwRoot?>ajax.php', {
													req: 'server_cmd',
													cmd: $('#cmd').val(),
													serverid: $('#srvid').html()
												}, function () {
													$('#cmd').val('').prop('disabled', false).focus();
													refreshLogOnce();
												});
												$('#cmd').prop('disabled', true);
												return false;
											});

											$('#log').css('height', $(window).height() - 200 + 'px');

											// Initialize log
											$.post('<?=Util::$wwwRoot?>ajax.php', {
												req: 'server_log',
												serverdir: $('#srvdir').html()
											}, function (data) {
												$('#log').html(data).scrollTop($('#log')[0].scrollHeight);
												window.setTimeout(refreshLog, 4000);
											});

											// Keep sizing correct
											$(document).resize(function () {
												$('#log').css('height', $(window).height() - 200 + 'px');
											});
										});
									</script>
								</div>
					  	</div>
					  <?php } ?>
					</div>
        </div>
    </div>
</div>