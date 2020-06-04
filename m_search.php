<?php
if (!isset($_SESSION)) session_start();

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey = "sorry, secret.";
$consumerSecret = "sorry, secret.";
$accessToken = "sorry, secret.";
$accessTokenSecret = "sorry, secret.";
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

if (isset($_POST["keyword"])) {
  $keyword = $_POST["keyword"];
} else {
  $keyword = "TTF_";
}

$param = [
    "q"                => $keyword //"TTF_" . $_POST["keyword"]
  , "count"            => 100
  , "result_type"      => "recent"
  , "include_entities" => true
];
$res = $twitter->get("search/tweets", $param);

if ($twitter->getLastHttpCode() == 200) {
  $_SESSION["result"] = $res;
} else {
  $_SESSION["result"] = [];
}

header("Location: v_mypage.php?page=search");
