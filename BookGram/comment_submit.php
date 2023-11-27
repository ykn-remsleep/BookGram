<?php
ini_set('display_errors', "On");
error_reporting(E_ALL);
session_start();

$user_id = $_SESSION["user"];
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

if (isset($_GET["url"]) && isset($_GET["body"]) && !empty($_GET["url"]) && !empty($_GET["body"])) {

  $url = $_GET["url"];
  $body = $_GET["body"];
  $bookname = $_GET["bookname"];
  $bookimg = $_GET["bookimg"];

  $pdo = new PDO("sqlite:bookgram.sqlite");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  $st = $pdo->prepare("INSERT INTO articles( ID ,userid ,book ,body ,bookname ,bookimg) VALUES(?, ?, ?, ?, ?, ?)");
  $st->execute(array(null, "$user_id", "$url", "$body", "$bookname", "$bookimg"));

  $result = "登録しました。";
} else {
  $result = "記事の内容がありません。";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>BookGlam - 感想登録</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>
  <div class="article">
    <?php
    print '<p>' . $result . '</p>';
    ?>
    <a href="top.php">TOPに戻る</a>




</body>

</html>