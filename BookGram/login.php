<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Login form</title>
  <link rel="stylesheet" href="bookgram.css">
</head>

<body>
  <div class="register">
    <h1>ログイン</h1>
    <!---フォームの送信先はlogin_submit.phpとする--->
    <form action="login_submit.php" method="get">
      <!---ユーザ名username--->
      <p>ユーザ名
        <br>
        <input type="text" name="username"><br>
        <!---パスワードpasswd--->
      <p>パスワード
        <br>
        <input type="password" name="passwd"><br>
        <br>
        <!---送信ボタン--->
        <input type=submit border=0 value="ログイン">
    </form>
    <a href="register.php">新規登録</a>
  </div>
  <div class="article_link">
    <a href="top.php">TOPに戻る</a>
    </div>
</body>

</html>