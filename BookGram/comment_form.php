<?php
ini_set('display_errors', "On");
error_reporting(E_ALL);
session_start();
// if (isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
//   $result = "ようこそ" . $username . "さん。[<a href='logout_form.php'>ログアウト</a></div>]";
// } else {
//   $result = '[<a href="login_form.php">ログイン</a></div>]';
// }
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>BookGlam 感想投稿</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>

  <div class="article">
    <h2>感想を入力してください</h2>

    <form action="comment_submit.php" method="get">

      <?php
      $url = $_GET["url"];
      $src = $_GET["src"];
      $bookname = $_GET["bookname"];
      ?>

      <img src=<?php print $src ?> style="width:70px;">
      <p>感想</P>
      <textarea name="body" cols="40" rows="4"></textarea>
      <input type="hidden" name="url" value=<?php print $url ?>><br>
      <input type="submit" value="送信">
      <input type="hidden" name="bookname" value=<?php print $bookname ?>>
      <input type="hidden" name="bookimg" value=<?php print $src ?>>
      </from>
  </div>

  <div class="article_link">
    <a href="top.php">TOPに戻る</a>
  </div>


</body>

</html>