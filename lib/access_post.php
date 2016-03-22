<?php
// POSTを取得したら実行
if ($_POST['send'] == "send") {

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
<title>設定を書き込みました</title>
</head>

<body>
    <p>設定を書き込みました</p>
    <p><a href="../access_setup.php">戻る</a></p>
</body>

</html>