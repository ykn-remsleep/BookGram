<?php

session_start();
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>bookgram プロフィール編集</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>
  <div class="article">
    <h2>プロフィールに書き込みたいことを入力してください</h2>
    <form action="profile.php" method="get">

      <p>プロフィール</P><!--ここプロフィールにした-->
      <input type="text" name="profile" value=""><br> <!--ここのnameをprofileにした-->
      <input type="submit" value="送信">
     
      </from>

  </div>
</body>

</html>