<html>

<head>
    <title>users</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bookgram.css">
</head>

<body>
    <h1> ユーザー登録 </h1>
    <?php
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    if (isset($_GET['username']))
        $username = h($_GET['username']);
    if (isset($_GET['password']))
        $password = h($_GET['password']);
    if (isset($_GET['passwordcheck']))
        $password2 = h($_GET['passwordcheck']);

    $db = new PDO("sqlite:bookgram.sqlite");

    //どちらかが空欄だったら
    if ($username == "" || $password == "" || $password2 == "") {
        echo '<div class="register">';
        echo "ユーザーネームとパスワードが空欄です";
        echo '<br><a href="register.php">ユーザー登録に戻る</a>';
        echo "</div>";
    }
    //パスワードと確認用パスワードが違ったら
    else if ($password != $password2) {
        echo '<div class="register">';
        echo "パスワードが一致しません";
        echo '<br><a href="register.php">ユーザー登録に戻る</a>';
        echo "</div>";
    }
    //情報が入力されていたら
    else {
        //ユーザーネームが被っていないか確認
        $user = $db->query("SELECT name FROM users");

        //ユーザーネームが被っていたらnumを1にする
        for ($i = 1; $row = $user->fetch(); ++$i) {
            if ($row['name'] == $username) {
                $num = 1;
                break;
            }
        }

        //ユーザーネームが被っていた場合
        if ($num == 1) {
            echo '<div class="register">';
            echo "このユーザー名は既に使われています";
            echo '<br><a href="register.php">ユーザー登録に戻る</a>';
            echo "</>";
        }

        //新規ユーザーネームの場合
        else {
            $result = $db->query("INSERT INTO users VALUES (null, '$username', '$password', null)");
            echo '<div class="register">';
            echo "ユーザー情報を登録しました";
            echo "</div>";
        }
    }
    ?>

    <br><div class="article_link"><a href="top.php">TOPに戻る</a></div>
</body>

</html>