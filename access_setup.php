<?php
// 設定ファイル読み込み
require_once "access_init.php";

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


// POSTを取得したら実行
if (isset($_POST['send'])) {

// 設定ファイル読み込み
    $open_init = file_get_contents("access_init.php");
// 改行で配列に
    $init_array = explode("\n", $open_init);

// データ取得
    $post_logdir                = $_POST['logdir'];
    $post_day_or_month          = $_POST['day_or_month'];
    $post_ip_or_host            = $_POST['ip_or_host'];
    $post_EXCLUDE_IP            = $_POST['EXCLUDE_IP'];
    $replace_EXCLUDE_IP         = '("' . preg_replace("/;/", "\",\"", $post_EXCLUDE_IP) . '")';
    $post_EXCLUDE_HOST          = $_POST['EXCLUDE_HOST'];
    $replace_EXCLUDE_HOST       = '("' . preg_replace("/;/", "\",\"", $post_EXCLUDE_HOST) . '")';
    $post_EXCLUDE_USER_AGENT    = $_POST['EXCLUDE_USER_AGENT'];
    $replace_EXCLUDE_USER_AGENT = '("' . preg_replace("/;/", "\",\"", $post_EXCLUDE_USER_AGENT) . '")';

    $init_output = "";
// 配列をforeachでぶん回して置換
    foreach ($init_array as $init_key => $init_value) {
        if (preg_match('/^\$userdir = .*;/', $init_value)) {
            $init_output .= preg_replace('/(^\$userdir = )(.*);/', "$1$post_logdir;", $init_value) . "\n";
        } elseif (preg_match('/^\$file_name = .*;/', $init_value)) {
            $init_output .= preg_replace('/(Ymd|Ym)/', "$post_day_or_month", $init_value) . "\n";
        } elseif (preg_match('/^\$ip_or_host = .*;/', $init_value)) {
            $init_output .= preg_replace('/(0|1)/', "$post_ip_or_host", $init_value) . "\n";
        } elseif (preg_match('/^\$EXCLUDE_IP = .*;/', $init_value)) {
            $init_output .= preg_replace('/(^\$EXCLUDE_IP = array).*;/', "$1$replace_EXCLUDE_IP;", $init_value) . "\n";
        } elseif (preg_match('/^\$EXCLUDE_HOST = .*;/', $init_value)) {
            $init_output .= preg_replace('/(^\$EXCLUDE_HOST = array).*;/', "$1$replace_EXCLUDE_HOST;", $init_value) . "\n";
        } elseif (preg_match('/^\$EXCLUDE_USER_AGENT = .*;/', $init_value)) {
            $init_output .= preg_replace('/(^\$EXCLUDE_USER_AGENT = array).*;/', "$1$replace_EXCLUDE_USER_AGENT;", $init_value) . "\n";
        } elseif (preg_match('/^\?\>/', $init_value)) {
            $init_output .= "?>";
        } else {
            $init_output .= $init_value . "\n";
        }
    }
// 書き込む
    file_put_contents("access_init.php", $init_output);
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
  <form method="post" id="form" action="" style="margin-top: 20px;">
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
      <a href="javascript:location.reload(true);" style="text-decoration: none;">
        <input type="button" value="結果を反映させる">
      </a>
    </div>
  </form>
</body>

</html>