<?php
  session_start();
  $_SESSION = array();
  session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login success</title>
    <link rel="stylesheet" href="bookgram.css">
  </head>
  <body>
    <div class="search">
      <h2>ログアウトしました</h2>
      <p class="article_link"><a href="top.php">TOPに戻る</a></p>
    </div>
  </body>
</html>