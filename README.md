# システム
木曜日「システム」
## はじめる

### 始め方
nginxファイルを作成する
まずディレクトリnginxを作成します
``` sh
mkdir nginx/conf.d
```
設定ファイルを作成する
``` sh
vim nginx/conf.d/default.conf
```
compose.ymlを作成する
``` sh
vim compose.yml
```
パブリックディレクトリを作成する
``` sh
mkdir public
```
PHPファイルを作成する
``` sh
vim public/shukudai.php
```
起動
``` sh
docker compose up
```
### テーブルの作成
Dockerコンテナ内のMySQLサーバーへの接続
``` sh
docker compose exec mysql mysql linh
```
MySQLクライアントで以下のSQLを実行してテーブルを作成します
``` sh
 CREATE TABLE 'book' (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `tilte` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE `book` ADD COLUMN image_filename TEXT DEFAULT NULL;
```
### ブラウザからアクセス
以下のURLからブラウザから掲示板にアクセスできます。
``` sh
http://ec2-44-204-75-62.compute-1.amazonaws.com/shukudai.php
``` 





    

