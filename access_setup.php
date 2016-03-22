<?php
// 設定ファイル読み込み
require_once "lib/access_init.php";

// 除外リストの配列を;で連結
$EXCLUDE_IP_implode         = implode(";", $EXCLUDE_IP);
$EXCLUDE_HOST_implode       = implode(";", $EXCLUDE_HOST);
$EXCLUDE_USER_AGENT_implode = implode(";", $EXCLUDE_USER_AGENT);

// ディレクトリを検出
$uri           = $_SERVER["REQUEST_URI"];
$uridir        = preg_replace("/(.*)access_setup.php/i", "$1", $uri);
$target_logdir = "'" . $uridir . "log/'";
$target_php    = "'" . $uridir . "access.php'";

// ファイル名の日付判定
$check_day_or_month = "";
if (strlen($file_name) == 6) {
    $check_day_or_month = 0;
} elseif (strlen($file_name) == 8) {
    $check_day_or_month = 1;
}

// checkedを入れるか判定
$checked_daily = "";
if ($check_day_or_month == "1") {
    $checked_daily = "checked";
}
$checked_monthly = "";
if ($check_day_or_month == "0") {
    $checked_monthly = "checked";
}
$checked_ip = "";
if ($ip_or_host == 0) {
    $checked_ip = "checked";
}
$checked_host = "";
if ($ip_or_host == 1) {
    $checked_host = "checked";
}

?>
<!DOCTYPE html>
<html lang="jp">

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>設定</title>
<style type="text/css">
body {
  line-height: 1.5;
  font-size: 93%;
}
table {
  border-collapse: collapse;
}
td {
  padding: 5px;
  border: solid 1px #aaa;
}
</style>
</head>

<body>
  <h1>設定</h1>
  <hr style="margin: 20px 0"> 各ページに以下のコードを挿入してください
  <br>
  <span style="border: solid 1px #ccc;margin: 10px;padding: 5px;">
&lt;?php require_once $_SERVER['DOCUMENT_ROOT'].<?=$target_php?>; ?&gt;
</span>
  <form method="post" id="form" action="lib/access_post.php" style="margin-top: 20px;">
    <table>
      <tr>
        <td>logファイル出力先のディレクトリ
          <br>(先に作成してください)</td>
        <td style="font-size:90%;">
          <input type="text" name="logdir" value="$_SERVER['DOCUMENT_ROOT'].<?=$target_logdir?>" style="width: 500px">
          <br>参考:access_setup.phpは<b>$_SERVER['DOCUMENT_ROOT'].'<?=$uridir?>'</b>内に設置されいます
        </td>
      </tr>
      <tr>
        <td>logファイルの出力設定
          <br>(月ごとまたは日ごと)</td>
        <td>
          <input type="radio" name="day_or_month" value="Ymd" <?=$checked_daily?>>daily
          <br>
          <input type="radio" name="day_or_month" value="Ym" <?=$checked_monthly?>>monthly
        </td>
      </tr>
      <tr>
        <td>logファイルのホスト記録設定
          <br>(IPまたはHostName)</td>
        <td>
          <input type="radio" name="ip_or_host" value="0" <?=$checked_ip?>>IP
          <br>
          <input type="radio" name="ip_or_host" value="1" <?=$checked_host?>>HostName
        </td>
      </tr>
      <tr>
        <td>IP除外リスト
          <br>(正規表現 <b>;</b>区切り)</td>
        <td>
          <input type="text" name="EXCLUDE_IP" value="<?=$EXCLUDE_IP_implode?>" style="width: 500px">
        </td>
      </tr>
      <tr>
        <td>HostName除外リスト
          <br>(正規表現 <b>;</b>区切り)</td>
        <td>
          <input type="text" name="EXCLUDE_HOST" value="<?=$EXCLUDE_HOST_implode?>" style="width: 500px">
        </td>
      </tr>
      <tr>
        <td>UserAgent除外リスト
          <br>(正規表現 <b>;</b>区切り)</td>
        <td>
          <input type="text" name="EXCLUDE_USER_AGENT" value="<?=$EXCLUDE_USER_AGENT_implode?>" style="width: 500px">
        </td>
      </tr>
    </table>
    <input type="hidden" name="send" value="send">
    <div style="text-align: center;margin: 10px auto;">
      <input type="submit" value="保存">
    </div>
  </form>
</body>

</html>