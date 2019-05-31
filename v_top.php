<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>HOME</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <div id="header"><span class="appname">Tweet Tag Finder</span></div>
    <h1>HOME</h1>
    <div class="form-wrapper">
      <form action="c_account.php" method="post">
        <input type="text" name="name" placeholder="ユーザ名" required><br>
        <p id="signup-comment"></p>
        <input type="password" name="pass" placeholder="パスワード" required><br>
        <div class="radios">
          <input type="radio" name="type" value="signup" id="bar1">
          <label for="bar1" id="bar1-label">サインアップ</label>
          <input type="radio" name="type" value="signin" id="bar2" checked>
          <label for="bar2" id="bar2-label">サインイン</label>
        </div>
        <?php
          if (isset($_SESSION["mess"])) {
            echo '<p>' . $_SESSION["mess"] . '</p>';
            $_SESSION["mess"] = "";
          }
        ?>
        <input type="submit" value="GO" id="go">
      </form>
    </div>
    <script>
      var target = document.getElementById("signup-comment");
      document.getElementById("bar1-label").addEventListener("click", function() {
        target.innerHTML = "twitterのidで登録してください";
      });
      document.getElementById("bar2-label").addEventListener("click", function() {
        target.innerHTML = "";
      });
    </script>
  </body>
</html>
