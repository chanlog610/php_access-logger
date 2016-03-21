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
サーバーにアップロードし、`access_setup.php`にアクセスし各項目を変更します。