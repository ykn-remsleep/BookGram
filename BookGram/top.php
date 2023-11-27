<?php
ini_set('display_errors', "On");
error_reporting(E_ALL);
session_start();

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

$pdo = new PDO("sqlite:bookgram.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$st = $pdo->query("select * from articles");
$articledata = $st->fetchAll();
$st = $pdo->query("select * from users");
$userdata = $st->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Bookgram</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>
  <h1>Bookgram</h1>
  <?php
  if (isset($_SESSION["user"])) {
    print 'ようこそ' . h($_SESSION["user"]) . 'さん！';
    print '[<a href="logout.php">ログアウト</a>]<br>';
  } else {
    print '[<a href="login.php">ログイン</a>]<br>';
  }
  foreach (array_reverse($articledata) as $article) {
    print '<div class="article">';
    print '<h2><a href=' . $article["book"] . '>' . h($article["bookname"]) . '</a></h2>';
    print '<img src=' . $article["bookimg"] . '>';
    print '<p>' . h($article["body"]) . '</p>';
    print '</div>';
  }


  if (isset($_SESSION["user"])) {
    echo "
  <p class='article_link'>
    <a href='book_form.php'>記事投稿</a>
  </p>";
    echo "
  <p class='article_link'>
    <a href='profile.php'>個人ページ</a>
  </p>";
  }
  echo "
  <p class='article_link'>
    <a href='search.php'>投稿検索</a>
  </p>";
  ?>
</body>

</html>