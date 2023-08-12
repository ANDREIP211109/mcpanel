<div class="background">
  <div class="shape"></div>
  <div class="shape"></div>
</div>
<form method="post">
  <h3>Signup Here</h3>

  <label for="username">Username</label>
  <input type="text" placeholder="Username" id="username" name="username">

  <label for="email">Email</label>
  <input type="email" placeholder="Email" id="email" name="email">

  <label for="password">Password</label>
  <input type="password" placeholder="Password" id="password" name="password">

  <button name="submit">Sign Up</button>
</form>

<?php
  if(isset($_POST['submit'])&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['email'])){
    $result = Util::$db->query('INSERT INTO users (username,password,email) VALUES (?,?,?)', $_POST['username'], $_POST['password'], $_POST['email']);
  }
?>