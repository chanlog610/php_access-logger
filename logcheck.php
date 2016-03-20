<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>logcheck</title>
<style type="text/css">
body {
   font-size: 95%;
}
td {
    padding: 3px 4px 3px 4px;
}
ul {
	margin: 10px 0;
	padding: 0;
}
li {
	float: left;
	margin-right: 8px;
	list-style: none;
}
</style>
</head>

<body>
  <h1>LogCheck</h1>
<?php

require_once "access_init.php";

if (isset($_GET['log'])) {
    $preflog  = $_GET["log"];
    $open_htm = file_get_contents("$userdir$preflog.htm");
    $pv_array = explode("\n", $open_htm);
    $pv_count = 0;
    foreach ($pv_array as $pv) {
        $pv = $pv_count++;
    }
    $htm_replace1 = preg_replace("/(<tr><td>\d{4}\/\d{2}\/\d{2}\s\d{2}:\d{2}:\d{2}<\/td><td>)/", "", $open_htm);
    $htm_replace2 = preg_replace("/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*/", "$1", $htm_replace1);
    $uu_array     = explode("\n", $htm_replace2);
    $uu_unique    = array_unique($uu_array);
    $uu_count     = 0;
    foreach ($uu_unique as $uu) {
        $uu = $uu_count++;
    }
    echo "<h2>UU:$uu / PV:$pv</h2>";
}
?>
    <table border="1" style="border-collapse:collapse;">
      <tr>
        <td>DateTime </td>
        <td>IP</td>
        <td>Request</td>
        <td>Referer<br>UserAgent</td>
      </tr>
<?php
echo $open_htm;
?>
    </table>
    <hr style="margin: 20px 0">
    <h2>Link</h2>
    <ul>
<?php
$templog = "";
// ディレクトリ検索と自動リンク化
//ファイルとディレクトリ検索
$dirscan = scandir($userdir, 1);
//.htmを抽出
$pattern = ".htm";
//foreachでぶん回す
foreach ($dirscan as $loglink) {
    if (preg_match("/$pattern/", $loglink)) {
        //拡張子削除
        $loglink = preg_replace("/(.*)$pattern/", "$1", $loglink);
        //リンク作成
        $templog .= "<li><a href=\"logcheck.php?log=$loglink\">$loglink</a></li>";
    }
}
?>
<?=$templog?>
    </ul>
    <div style="clear: both;"></div>
    <hr style="margin: 20px 0">
</body>

</html>