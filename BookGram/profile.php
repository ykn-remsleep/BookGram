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

if (isset($_GET["profile"])) { //issetに変更した

  $user_name = $_SESSION["user"]; //ログインしたユーザーネームを$user_nameとする
  $profile = $_GET["profile"]; //プロフィールに入力した文章を$profileとする

  $pdo = new PDO("sqlite:bookgram.sqlite");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


  $st = $pdo->query("UPDATE users SET profile ='" . $profile . "' WHERE name ='" . $user_name . "'");
  //                     このテーブルの　　　　この項目を　　　これにする　　　　/　　　この項目が　　　これのとき
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>bookgram</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>
  <h1>BookGram</h1>

  <form action="profile_edit.php" method="get">

    <?php
    if (isset($_SESSION["user"])) {
      $user_name = $_SESSION["user"];
    }

    $st = $pdo->query("SELECT * FROM users WHERE name = '" . $user_name . "'");
    //　　　　　　　　　　　　　　　　このテーブルの　　　　この項目が　　　　これのとき　　　（のデータを参照する）　
    for ($i = 1; $row = $st->fetch(); ++$i) {
      print '<div class="search">';
      print '<h2>' . h($row["name"]) . 'さんのプロフィール</h2>';
      print '<p>' . h($row["profile"]) . '</p>';
      //　　　　usersテーブルの中のどの項目を表示するのか
      print '</div>';
    }

    if (isset($_SESSION["user"])) {
      $user_name = $_SESSION["user"];
    }

    $st = $pdo->query("SELECT * FROM articles WHERE userid == '$user_name' ORDER BY id DESC");
    for ($i = 1; $row = $st->fetch(); ++$i) {
      print '<div class="article">';
      print '<h2><a href=' . $row["book"] . '>' . h($row["bookname"]) . '</a></h2>';
      print '<img src=' . $row["bookimg"] . '>';
      print '<p>' . h($row["body"]) . '</p>';
      print '</div>';
    }


    ?>
  </form>

  <div class="article_link">
    <a href="profile_edit.php">プロフィール編集</a><br>
    <a href="top.php">TOPに戻る</a>
  </div>

</body>

</html>