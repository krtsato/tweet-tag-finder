<?php
if (!isset($_SESSION)) session_start();

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey = "sorry, secret.";
$consumerSecret = "sorry, secret.";
$accessToken = "sorry, secret.";
$accessTokenSecret = "sorry, secret.";
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$pdo = new PDO("sqlite:data.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$user_id = $_SESSION["user_id"];

$st = $pdo->query("SELECT * FROM favorites WHERE user_id = ".$user_id." ORDER BY id DESC");
$data = $st->fetchAll();
$tweet_id_array = array_column($data, 'tweet_id');
$tweet_id_sum = implode(",", $tweet_id_array);
$param = [
    "id"               => $tweet_id_sum
  , "include_entities" => true
  , "map"              => true
];
$res = $twitter->get("statuses/lookup", $param);

if ($twitter->getLastHttpCode() == 200) {
  $_SESSION["result"] = $res;
} else {
  $_SESSION["result"] = [];
}

header("Location: v_mypage.php?page=favorite");
