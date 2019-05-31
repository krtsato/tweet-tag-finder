<?php
if (!isset($_SESSION)) session_start();

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey = "5nTKiNGUnfocfoNxx5GgfJ1TO";
$consumerSecret = "fHREOdEARgrfIOZoczmmvRoHSUsuY8U7POLWq2XWb706zYKZx1";
$accessToken = "871225294245437440-35QWwt42ptzqoCw3UviJTTKkBCbgOHC";
$accessTokenSecret = "0ubgaHKMyTzl2S7H3P1npfrDfTaAVRUOeHElZ3LYLTa2G";
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

if (isset($_POST["tag_name"])) {
  $keyword = $_POST["tag_name"];
  $_SESSION["tag_mess"] = "選択中のタグ：";
} else {
  $keyword = $_POST["keyword"];
  $_SESSION["tag_mess"] = "今までにツイートしたタグ：";
}

mb_regex_encoding("UTF-8");

$param = [
    "q"                => "from:" . $_SESSION["name"] . " TTF_" . $keyword
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

header("Location: v_mypage.php?page=home");
