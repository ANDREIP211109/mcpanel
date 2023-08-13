<!DOCTYPE html>
<html lang="en">
<head>
  <title>McPanel</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <?php
    $files = glob(Util::$rootPath . "/css/*".str_replace('.php','',$page)."*.css");
    foreach ($files as $file){
      $file=str_replace('/var/www/html','',$file);
  ?>
      <link rel="stylesheet" href="<?=$file?>?rand=<?=rand(9,99999)?>">
  <?php
    }
  ?>

  <?php
    $files = glob(Util::$rootPath . "/js/*".str_replace('.php','',$page)."*.js");
    foreach ($files as $file){
      $file=str_replace('/var/www/html','',$file);
  ?>
      <script src="<?=$file?>?rand=<?=rand(9,99999)?>"></script>
  <?php
    }
  ?>
</head>
<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>


  <?php require_once $page ?>
  <?php
    $flashMessages = FlashMessage::getMessages();
    if(count($flashMessages)){
  ?>
      <div class="container">
  <?php
        foreach($flashMessages as $m){
  ?>
          <div class="alert alert-<?=$m['type']?> alert-dismissible fade show" role="alert">
            <div><?=$m['text']?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
  <?php
        }
  ?>
      </div>
  <?php
    }
  ?>
</body>
</html>