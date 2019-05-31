<?php
  if (!isset($_SESSION)) session_start();
  if (!isset($_SESSION["name"]) || $_SESSION["name"] == "") {
    $_SESSION["mess"] = "ユーザ名またはパスワードが違います";
    header("Location: v_top.php");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>My Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="./font-awesome.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <div id="header">
      <span class="appname">Tweet Tag Finder</span>
      <span id="youkoso">ようこそ、<?php echo $_SESSION["name"]; ?>さん</span>
      <form id="signout" action="c_account.php" method="post">
        <input type="hidden" name="type" value="signout">
        <button type="submit" class="signout_button">
            <i class="fa fa-sign-out fa-1.5x fa-out_button" aria-hidden="true"></i>
            サインアウト
        </button>
      </form>
    </div>
    <div id="menu">
      <ul>
        <li>
          <a class="menu-button" href="m_home.php"><i class="fa fa-home" aria-hidden="true"></i> ホーム</a>
        </li>
        <li>
          <a class="menu-button" href="m_favorite.php"><i class="fa fa-star" aria-hidden="true"></i> お気に入り</a>
        </li>
        <li>
          <a class="menu-button" href="m_search.php"><i class="fa fa-search" aria-hidden="true"></i> 検索</a>
        </li>
     </ul>
    </div>
    <div id="contentsArea">
      <h1> My Page</h1>
      <?php
        mb_regex_encoding("UTF-8");
        $tw_count = 0;
        $tg_count = 0;
        $tg_array = array();
        if ($_GET["page"] == "home") {
          echo '<div id="sidebar"><span class="tag_mess">'. $_SESSION["tag_mess"] . '</span>';
          foreach ($_SESSION["result"]->statuses as $tw_temp) {
            if (preg_match("/#TTF_(\w[ぁ-んァ-ヶー]+)/u", $tw_temp->text, $tag)) {  /* 漢字あり "/#TTF_(\w[ぁ-んァ-ヶー一-龠]+)/u" */
              array_push($tg_array, $tag[1]);
            }
            $tweet[$tw_count] = $tw_temp;
            $tw_count++;
          }

          $tg_unique = array_unique($tg_array);
          $alignedUnique = array_values($tg_unique);
          
          foreach ($alignedUnique as $tw_temp) {
              echo '<form class="tag_form" action="m_home.php" method="post" name="tag_form">';
              echo '<input type="hidden" name="tag_name" value="'. $tw_temp .'">';
              echo '<a class="tag" href="javascript:tag_form['. $tg_count .'].submit()">'. $tw_temp .'</a>';
              echo '</form>';
              $tg_count++;
            }          
          echo '</div>';
        } elseif ($_GET["page"] == "favorite") {
          foreach ($_SESSION["result"]->id as $tw_temp) {
            $tweet[$tw_count] = $tw_temp;
            $tw_count++;
          }
        } else {
          echo '<div class="form-wrapper">';
          echo '<form action="m_search.php" method="post">';
          echo '<input type="text" name="keyword" required>';
          echo '<button type="submit" class="search_button">
                 <i class="fa fa-search fa-2x fa-button" aria-hidden="true"></i>
               </button>';
          echo '</form>';
          echo '</div>';
          foreach ($_SESSION["result"]->statuses as $tw_temp) {
            $tweet[$tw_count] = $tw_temp;
            $tw_count++;
          }
        }

        for ($i=0; $i<$tw_count; $i++) {
          echo '<div class="tweet">';
          /* username & timestamp */
          echo '<div class="tweet-username">';
          echo $tweet[$i]->user->name . "さんのツイート";
          echo '<span class="tweet-time">' . date('Y-m-d H:i:s', strtotime($tweet[$i]->created_at)) . '<span>';
          echo '</div>';
          /* username & timestamp */
          /* text & images */
          echo '<div class="tweet-text">';
          echo nl2br($tweet[$i]->text) . '<br>';
          if (isset($tweet[$i]->extended_entities)) {
            foreach ($tweet[$i]->extended_entities->media as $media) {
              if ($media->type == 'photo') {
                echo '<img src="' . $media->media_url . '" alt="">';
              }
            }
          }
          /* text & images */
          /* favorite function */
          echo '<div class="tweet-favorite">';
          echo '<form class="favorite-form" method="post">';
          echo '<input class="favorite-hidden" type="hidden" name="tweet_id" value="' . $tweet[$i]->id_str . '">';
          echo '<button type="submit" class="favorite_button">
                <i class="fa fa-star-o fa-2x fa-favo_button" aria-hidden="true"></i>
                </button>';
          echo '</form>';
          echo '</div>';
          /* favorite function */
          echo '</div>';
          echo '</div>';
        }
      ?>
    </div>
    <script>
      $(function() {
        $(".favorite-form").on("submit", function(e) {
          $.ajax({
              url:  "c_favorite.php"
            , type: "POST"
            , data: {
                    "tweet_id": $(this).find(".favorite-hidden").val()
                    }
          }).done(function (response) {
            alert(response);
          }).fail(function () {
            alert("通信に失敗しました");
          });
        });
      });
    </script>
  </body>
</html>