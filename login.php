<?php
  if(isset($_POST['submit'])&&isset($_POST['username'])&&isset($_POST['password'])){
    $user = Util::$db->query('SELECT * FROM users WHERE username = ? AND password = ?', array($_POST['username'], $_POST['password']))->fetchArray();

    if(!$user){
      FlashMessage::add('Username and password don\'t match');
    }else{
      Session::login($user['id']);
    }
  }
?>
<div class="background">
  <div class="shape"></div>
  <div class="shape"></div>
</div>
<form method="post">
  <h3>Login Here</h3>

  <label for="username">Username</label>
  <input type="text" placeholder="Username" id="username" name="username">

  <label for="password">Password</label>
  <input type="password" placeholder="Password" id="password" name="password">

  <button class="formbtn" name="submit">Log In</button>
</form>