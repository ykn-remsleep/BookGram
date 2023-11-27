<html>

<head>
    <title>search</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bookgram.css">
</head>

<body>
    <h1> 投稿検索 </h1>
    <form class="search" action=search.php method=get>
        <input type=text size=20 name=search>
        <input type=submit border=0 value="検索">
        </table>
    </form>
    <div class="article_link">
        <a href="top.php">TOPに戻る</a>
    </div>

    <?php
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    if (isset($_GET['search']))
        $search = h($_GET['search']);

    $db = new PDO("sqlite:bookgram.sqlite");

    if (isset($search)) {
        $result = $db->query("SELECT * FROM articles WHERE bookname like '%$search%' ORDER BY id DESC");
    }

    for ($i = 1; $row = $result->fetch(); ++$i) {
        print '<div class="article">';
        print '<h2><a href=' . $row["book"] . '>' . h($row["bookname"]) . '</a></h2>';
        print '<img src=' . $row["bookimg"] . '>';
        print '<p>' . h($row["body"]) . '</p>';
        print '</div>';
    }
    ?>
</body>

</html>