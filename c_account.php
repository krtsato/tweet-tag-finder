<?php
if (!isset($_SESSION)) session_start();



if ($_POST["name"] != "" && $_POST["pass"] != "" && $_POST["type"] != "") {
  $name = $_POST["name"];
  $pass = $_POST["pass"];
  $type = $_POST["type"];

  $pdo = new PDO("sqlite:data.sqlite");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

  switch ($type) {
    case "signup":
      $st = $pdo->prepare("INSERT INTO users VALUES(NULL, ?, ?)");
      $st->execute([$name, $pass]);
      $_SESSION["mess"] = "アカウントを作成しました";
      header("Location: v_top.php");
      break;
    case "signin":
      $st = $pdo->prepare("SELECT * FROM users WHERE name = ?");
      $st->execute([$name]);
      $data = $st->fetchAll();
      foreach ($data as $row) {
        if ($pass == $row["password"]) {
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["name"] = $row["name"];
          break;
        }
      }
      header("Location: m_home.php");
      break;
  }
} elseif ($_POST["type"] == "signout") {
  $_SESSION = [];
  session_destroy();

  session_start();
  $_SESSION["mess"] = "ログアウトしました";
  header("Location: v_top.php");
} else {
  $_SESSION["mess"] = "ユーザ名とパスワードを入力してください";
  header("Location: v_top.php");
}
