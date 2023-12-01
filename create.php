<?php
  if(isset($_POST['submit'])&&isset($_POST['name'])&&isset($_POST['ram'])&&isset($_POST['version'])&&$_POST['version']!=""){
    //create server
  }

  $url_api = "https://launchermeta.mojang.com/mc/game/version_manifest.json";
  $api_data = file_get_contents($url_api);
  $api_data = json_decode($api_data);
  $versions = $api_data->versions;
  // echo "<pre>";
  // print_r($versions);
  // echo "</pre>";

?>
<form method="post" class="shadow-lg mb-5 bg-body-tertiary rounded col-4 p-3 border border-2 border-secondary-subtle fs-3 position-absolute top-50 start-50 translate-middle">
  <div class="mb-3">
    <label for="name" class="form-label">Server Name</label>
    <input id="textin" type="text" class="form-control" id="name" placeholder="Server Name" name="name">
  </div>
  <div class="mb-3">
    <label for="ram" class="form-label">Server Ram</label>
    <input id="numin" type="number" step="1024" value="1024" min="1024" max="5120" class="form-control" id="ram" placeholder="Server Ram" name="ram">
  </div>
  <div class="mb-3">
    <label for="version" class="form-label">Server Version</label>
    <select id="version" class="form-select" aria-label="Version Select" name="version">
      <option value="" selected>Select Version</option>
      <?php foreach ($versions as $ver) { ?>
        <?php if($ver->type == "release"){ ?>
          <option value="<?=$ver->id?>"><?=$ver->id?></option>
        <?php } ?>  
      <?php } ?>
    </select>
  </div>
  <button name="submit" type="submit" class="btn btn-primary btn-lg">Submit</button>
</form>
<script type="text/javascript">
  $('body').addClass('bg-secondary bg-gradient');

  const input = document.querySelector("#numin");
  input.addEventListener("keypress", (evt) => {
    const theEvent = evt || window.event;
    let key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    const regex = /[0-9]|\./;
    if (!regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) theEvent.preventDefault();
    }
  });

  $("#textin").on({
    keydown: function(e) {
      if (e.which === 32)
        return false;
    },
    change: function() {
      this.value = this.value.replace(/\s/g, "");
    }
  });
</script>