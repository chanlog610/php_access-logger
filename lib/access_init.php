<?php

///////////////////////////////////////////////////////////////////////設定ここから

// *重要* ログファイルを出力すディレクトリ。
// サンプルではDOCUMENT_ROOTからみたパスになっています。
// あらかじめ作成しておく必要があります。
$userdir = $_SERVER['DOCUMENT_ROOT'].'/access/log/';

// 表記するUTCからの時差。タイムスタンプには影響ありません。
$timedelay = "+0900";

// 月ごとor日ごとにログの出力する 'Ym'だと月ごと、'Ymd'だと日ごとに出力できます。
$file_name = date('Ymd', $_SERVER["REQUEST_TIME"]);

//.logファイルに記録するアドレス(IP=0 ホスト名=1)
$ip_or_host = 0;

//アクセスログから除外するIP (正規表現)
$EXCLUDE_IP = array("192.168.*","127.0.0.1");
//アクセスログから除外するホスト (正規表現)
$EXCLUDE_HOST = array("google","yahoo.net","sogou.com","msn.com","data-hotel.net","ahrefs.com","baidu.com");
//アクセスログから除外するUSER AGENT (正規表現)
$EXCLUDE_USER_AGENT = array("bot","spider");

///////////////////////////////////////////////////////////////////////設定ここまで

?>