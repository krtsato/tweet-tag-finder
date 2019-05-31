<?php
if (!isset($_SESSION)) session_start();

$pdo = new PDO("sqlite:data.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$user_id = $_SESSION["user_id"];
$tweet_id = $_POST["tweet_id"];

$st = $pdo->query("SELECT COUNT(*) FROM favorites WHERE user_id = {$user_id} AND tweet_id = {$tweet_id}");
$num = $st->fetchColumn();

if ($num != 0) {
  $_st = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND tweet_id = ?");
  $_st->execute([$user_id, $tweet_id]);
  $res = "お気に入り：解除";
} else {
  $_st = $pdo->prepare("INSERT INTO favorites VALUES(NULL, ?, ?)");
  $_st->execute([$user_id, $tweet_id]);
  $res = "お気に入り：登録";
}
echo $res;
