<?php
/** rakutenBooks.php*/

session_start();
// if (isset($_SESSION["user"])) {
//   $result = "ようこそ" . $_SESSION["user"] . "さん。[<a href='logout.php'>ログアウト</a></div>]";
//   }
//   else {
//   $result= '[<a href="login_form.php">ログイン</a></div>]';
//   }
if (!isset($_SESSION["user"])) {
	header("Location: login_form.php");
	exit;
}

function h($str)
{
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}



define('INTERNAL_ENCODING', 'UTF-8');
mb_internal_encoding(INTERNAL_ENCODING);
mb_regex_encoding(INTERNAL_ENCODING);
define('MYSELF', basename($_SERVER['SCRIPT_NAME']));
define('TITLE', 'BookGlam');
define('WIDTH', 600);

require_once('rakutenbooks.php');


$encode = INTERNAL_ENCODING;
$title = TITLE;
$width = WIDTH;
$HtmlHeader = <<<EOT
<!DOCTYPE html>
<html>
<head>
<meta charset="{$encode}">
<title>{$title}</title>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('table.stripe_table').css('border-color', '#FFFFFF');
	$('table.stripe_table').css('border-collapse', 'collapse');
	$('table.stripe_table th').css('background-color', '#FFBB00');
	$('table.stripe_table th').css('text-align', 'center');
	$('table.stripe_table th').css('padding', '4px');
	$('table.stripe_table th').css('font-size', 'small');
	$('table.stripe_table tr:odd').css('background-color', '#FFDD88');
	$('table.stripe_table tr:even' ).css('background-color', '#FFFFFF');
	$('table.stripe_table td').css('padding', '4px');
	$('table.stripe_table td').css('font-size', '11px');
	$('table.stripe_table td.title').css('font-size', '14px');
});
</script>
</head>

EOT;

$HtmlFooter = <<<EOT
</html>
EOT;

function myErrorHandler($errno, $errmsg, $filename, $linenum)
{
	echo 'Sorry, system error occured !';
	exit(1);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);



function isButton($btn)
{
	if (isset($_GET[$btn]) && $_GET[$btn] != '')
		return TRUE;
	if (isset($_POST[$btn]) && $_POST[$btn] != '')
		return TRUE;
	return FALSE;
}

function getParam($key, $auto = TRUE, $def = '')
{
	if (isset($_GET[$key]))
		$param = $_GET[$key];
	else if (isset($_POST[$key]))
		$param = $_POST[$key];
	else
		$param = $def;
	if ($auto)
		$param = mb_convert_encoding($param, INTERNAL_ENCODING, 'auto');
	return $param;
}


function roundSalesDate($str)
{
	if (preg_match('/([0-9]+)年([0-9]+)月([0-9]+)日/iu', $str, $arr) > 0) {
		$res = ($arr[1] + 0) . '年' . ($arr[2] + 0) . '月';
	} else if (preg_match('/([0-9]+)年([0-9]+)月/iu', $str, $arr) > 0) {
		$res = ($arr[1] + 0) . '年' . ($arr[2] + 0) . '月';
	} else {
		$res = '(不詳)';
	}
	return $res;
}


function makeCommonBody($mode, $items, $query, $author, $webapi, $errmsg)
{
	$myself = MYSELF;
	$title = TITLE;

	$width = WIDTH;

	$res = '';

	if ($errmsg != '') {
		$res = <<<EOT
<p style="color:red">
エラー：{$errmsg}．
</p>

EOT;
	} else if (isset($items) && (count($items) > 0)) {
		$res = <<<EOT
		<table class="stripe_table" style="width:{$width}px; margin-top:10px; color: black;">
			<tr>
				<th>書名</th>
				<th>著者</th>
				<th>出版社</th>
				<th>発売日</th>
				<th>価格<br />（税込）</th>
				<th>ISBN</th>
			</tr>

EOT;
	foreach ($items as $val) {
		$price = number_format($val['itemPrice']) . '円';
		$yyyymm = roundSalesDate($val['salesDate']);
		$res .= <<<EOT
		<form action = "comment_form.php" method="get">
			<tr>
				<td  class="title"><a href="{$val['affiliateUrl']}">{$val['title']}</a></td>
				<td>{$val['author']}</td>
				<td>{$val['publisherName']}</td>
				<td>{$yyyymm}</td>
				<td style="text-align:right;">{$price}</td>
				<td style="text-align:center;">{$val['isbn']}</td>
			</tr>
			<tr>
				<td><img src="{$val['mediumImageUrl']}" style="width:70px;" /></td>
				<td colspan="6">{$val['itemCaption']}</td>
				<td>
					<a href="comment_form.php?url= '{$val['affiliateUrl']}' & bookname='{$val['title']}' & src='{$val['mediumImageUrl']}' ">コメントする</a>
				</td>
			</tr>
		</form>
		EOT;
		}
		$res .= "</table>\n";
	}

	if ($mode == 0) {
		$html = <<<EOT
<body>
<h1>BookGram<h1>
<link rel="stylesheet" href="bookgram.css"> 
<form name="myform" method="post" action="{$myself}" enctype="multipart/form-data">

<div class="submit">

<table style="padding:4px;">
<tr>
<td>書名またはISBNコード</td>
<td><input type="text" name="query" size="40" value="{$query}" /></td>
</tr>
<tr>
<td>著者名</td>
<td><input type="text" name="author" size="40" value="{$author}" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="exec" value="検索" />　
<input type="submit" name="clear" value="リセット" />
</td>
</tr>
</table>
</form>


{$res}

</form>
<a href="top.php">Topに戻る</a>
</body>

EOT;
	} else {
		$html = <<<EOT
<body>
{$res}
</body>

EOT;
	}

	return $html;
}


$prk = new rakutenbooks();


$query = getParam('query', TRUE, ''); //query:検索キー
$author = getParam('author', TRUE, ''); 
$mode = (!isButton('exec') && !isButton('clear') && ($query != '')) ? 1 : 0;
$items = array();
$errmsg = $webapi = '';


if (isButton('clear')) {
	$query = $author = '';

	
} else if ($query != '' || $author != '') {
	$prk->searchBooks($query, $author, $items);
	$errmsg = $prk->geterror();
	$webapi = $prk->webapi;
}


$HtmlBody = makeCommonBody($mode, $items, $query, $author, $webapi, $errmsg);
echo $HtmlHeader;
echo $HtmlBody;
echo $HtmlFooter;

$prk = NULL;


?>