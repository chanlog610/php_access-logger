# PHP_Access-Logger

## これはなに？

PHPでアクセス解析を行うソフトです  
Javascriptに比べて得られる情報は少ないですが  
確実にアクセスログを取得できます

### 得られる情報
* IP/HOST
* Timestamp
* Request
* User Agent
* Referer

など

### ソフトの特徴
* Apache風のログを出力
* IP/HOST/User Agentのフィルター
* UU,PVなどを見られる簡易ビューア

### Demo
[簡易ビューア](http://ayamichanlog.xyz/dev/logcheck.php)

### 設置方法
`access_init.php`を編集し以下の項目を任意で変更します。
* $userdir

ログを出力するディレクトリです  
あらかじめ作成しておく必要があります    

初期設定では  
`$userdir = $_SERVER["DOCUMENT_ROOT"] . "/log/";`   
となってます
  
よくわからない方は  
`<?php echo $_SERVER["DOCUMENT_ROOT"]; ?>` 
を適当なコードに挿入し、DOCUMENT ROOTと  
出力したいディレクトリの位置関係を確認してください  

* $file_name

ログの出力を「月ごと」or「日ごと」に切り替えることができます  
初期設定では  
`$file_name = date('Ymd', $_SERVER["REQUEST_TIME"]);`  
となっており「日ごと」ですが`'Ym'`に変更すると「月ごと」のログを出力できます。　　

* $ip_or_host

.logファイルにIPかHostNameどちらを記録するか選べます  
IPなら0、ホスト名なら1を選択してください  
簡易ビューアのほうには両方記録しています  

* $EXCLUDE_IP
* $EXCLUDE_HOST
* $EXCLUDE_USER_AGENT 

除外するIP/HOST/User Agentを設定します  
正規表現が使えます　　　　

設定が終わったら
`access.php`を各ページで読み込みます。  
例:DOCUMENT　ROOT上に`access.php`がある場合  
`<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/access.php'; ?>`  
となります  
絶対パスなので使いまわしができ、どの階層からも読み込むこめるため管理が楽です