<?php

require_once "access_init.php";

//チェック用
$ex_check = 0;

//アクセスしてきたIP
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
//foreachでぶん回す(IP)
foreach ($EXCLUDE_IP as $value) {
//$ex_checkが1になったら処理とめる
    if ($ex_check > 0) {break;}
//アドレスのパターンパッチ開始
    if (preg_match("/$value/", $REMOTE_ADDR)) {
        $ex_check++;
    }
}

//アクセスしてきたホスト
$REMOTE_HOST = $_SERVER["REMOTE_HOST"];
    if (empty($REMOTE_HOST)) {
        $REMOTE_HOST = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if (empty($REMOTE_HOST)) {
            $REMOTE_HOST = $REMOTE_ADDR;
        }
    }
//foreachでぶん回す(ホスト)
foreach ($EXCLUDE_HOST as $value) {
//$ex_checkが1になったら処理とめる
    if ($ex_check > 0) {break;}
//アドレスのパターンパッチ開始
    if (preg_match("/$value/i", $REMOTE_HOST)) {
        $ex_check++;
    }
}

//アクセスしてきたUA
$HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"];
//foreachでぶん回す(ホスト)
foreach ($EXCLUDE_USER_AGENT as $value) {
//$ex_checkが1になったら処理とめる
    if ($ex_check > 0) {break;}
//アドレスのパターンパッチ開始
    if (preg_match("/$value/i", $HTTP_USER_AGENT)) {
        $ex_check++;
    }
}


//$ex_checkが0ならアクセスログゲット
if ($ex_check == 0) {

// 情報取得
    $SCRIPT_NAME     = $_SERVER["SCRIPT_NAME"];
    $SERVER_PROTOCOL = $_SERVER["SERVER_PROTOCOL"];
    $REQUEST_METHOD  = $_SERVER["REQUEST_METHOD"];
    $REQUEST_TIME    = $_SERVER["REQUEST_TIME"];
    $HTTP_REFERER    = $_SERVER["HTTP_REFERER"];if (empty($HTTP_REFERER)) {$HTTP_REFERER    = "-";}
    $REMOTE_USER     = $_SERVER["REMOTE_USER"];if (empty($REMOTE_USER)) {$REMOTE_USER     = "- -";}
    $SCRIPT_FILENAME = $_SERVER["SCRIPT_FILENAME"];
    $REQUEST_URI     = $_SERVER["REQUEST_URI"];

//apacheもどきのデータで記録するアドレス(IP=0 ホスト名=1)
    if ($ip_or_host == 0) {
        $apacheaddress = $REMOTE_ADDR;
    } else {
        $apacheaddress = $REMOTE_HOST;
    }

// apacheもどきのデータ作成
    $apachedatatime = date('d/M/Y:H:i:s', $_SERVER["REQUEST_TIME"]) . " " . $timedelay;
    $tempLog .= "$apacheaddress $REMOTE_USER [$apachedatatime] \"$REQUEST_METHOD $REQUEST_URI $SERVER_PROTOCOL\" 200 - \"$HTTP_REFERER\" \"$$HTTP_USER_AGENT\"\n";
// LogCheckでの画面リストデータ作成
    $htmldatatime = date('Y/m/d H:i:s', $_SERVER["REQUEST_TIME"]);
    $tempHtm .= "<tr><td>$htmldatatime</td><td>$REMOTE_ADDR<br>$REMOTE_HOST</td><td>$REQUEST_METHOD $REQUEST_URI</td><td>$HTTP_REFERER<br>$HTTP_USER_AGENT</td></tr>\n";

// .logの出力
    $outputlog = "$userdir$file_name.log";
    $fplog     = fopen($outputlog, 'a+');
    if (fwrite($fplog, $tempLog) === false) {
        print('ファイル書き込みに失敗しました');
    }
    flock($fplog, LOCK_UN);
    fclose($fplog);

// logcheck.php用.htmの出力
    $outputhtml = "$userdir$file_name.htm";
    $fphtml     = fopen($outputhtml, 'a+');
    if (fwrite($fphtml, $tempHtm) === false) {
        print('ファイル書き込みに失敗しました');
    }
    flock($fphtml, LOCK_UN);
    fclose($fphtml);
}

?>
