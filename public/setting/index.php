<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
  header("HTTP/1.1 302 Found");
  header("Location: /login.php");
  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');
$select_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$select_sth->execute([
	    ':id' => $_SESSION['login_user_id'],
]);
$user = $select_sth->fetch();
?>
<html>
<style>
body{
  background:#3c69f8;
  font-family:Helvetica;
  color:#fff;
}
h1{
  text-align: center;
  background: #42bff5;
  padding: 10px;
  font-size: 1.4em;
  font-weight: bold;
  color: #fff;
}
.setting {
  width: 600px;
  padding 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #42bff5;
  max-width: 600px;
  margin: 0 auto 100px;
  padding: 30px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.info{
  display:flex;
  flex-wrap: wrap;
  width:100%;
}
.info dt,
.info dd{
  padding: 6px;
}
.info dt {
  width: 25%;
}
.info dd {
margin-left: 0;
width: 65%;
}

p {
  text-align: center;
  font-size: 1.2em;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #42bff5;
}
a {
 color: #fff;
 font-size: 16px;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}
li a:hover {
  background-color: #3c69f8;
}
</style>
<body>
<a href="/timeline.php">タイムラインに戻る</a>

<h1>設定画面</h1>
<div class="setting">
<div class="form">
<p>
  現在の設定
</p>
<dl class="info"> <!-- 登録情報を出力する際はXSS防止のため htmlspecialchars() を必ず使いましょう -->
  <dt>ID</dt>
  <dd><?= htmlspecialchars($user['id']) ?></dd>
  <dt>メールアドレス</dt>
  <dd><?= htmlspecialchars($user['email']) ?></dd>
  <dt>名前</dt>
  <dd><?= htmlspecialchars($user['name']) ?></dd>
</dl>
</div>
</div>
<ul>
  <li><a href="./name.php">名前設定</a></li>
  <li><a href="./icon.php">アイコン設定</a></li>
<li><a href="./cover.php">カバー画像設定</a></li>
  <li><a href="./introduction.php">自己紹介文設定</a></li>
<li><a href="./brithday.php">生年月日設定</a></li>
<li><a href="../user_follow_list.php">Follow</a></li>
<li><a href="../follow_list.php">Following</a></li>
</ul>
</body>
</html>
