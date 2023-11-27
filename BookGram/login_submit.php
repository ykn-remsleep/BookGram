<?php
session_start();
//フォームから受信したusernameとパスワードを取り出す
if (isset($_GET["username"]) && isset($_GET["passwd"])) {
  $username = $_GET["username"];
  $passwd = $_GET["passwd"];
  $pdo = new PDO("sqlite:bookgram.sqlite");
  //$st = $pdo->prepare("SELECT * FROM  user (title, body, time) VALUES(?, ?, ?)");
  $st = $pdo->prepare("SELECT * FROM  users where name=?");
  $st->execute(array($username));
  $user_on_db = $st->fetch();
  //＊＊＊認証処理＊＊＊
  if (!$user_on_db) {
    $result = "指定されたユーザが存在しません。";
    $url = "<p class='article_link'><a href=login.php>ログインページに戻る</a></p>";
    //header("Location: login_form.php");
    //header("Location: login.php");
    // exit;
  }
  //$passwdがデータべーズ上のpassword属性と等しいかチェック
  else if ($passwd == $user_on_db["password"]) {
    // 等しければ
    //セッション変数$_SESSION["user"]にユーザ名を登録
    $_SESSION["user"] = $username;
    //$result = "ようこそ" . $_SESSION["user"] . "さん。ログインに成功しました。";
    $result = "ログインに成功しました。";
    $url = "<p class='article_link'><a href=top.php>Topに戻る</a></p>";
    header("refresh:5;url=top.php");
    //exit;
  } else {
    // そうでなければ
    $result = "パスワードが違います。";
    $url = "<p class='article_link'><a href=login.php>ログインページに戻る</a></p>";
    //header("Location: login.php");
  }
}
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
    <h2>
      <?php print $result; ?>
    </h2>
    <p><?php print $url; ?>
  </div>
</body>

</html>