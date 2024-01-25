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
if (isset($_POST['introduction'])) {
	$update_sth = $dbh->prepare("UPDATE users SET introduction = :introduction WHERE id = :id");
	  $update_sth->execute([
		        ':id' => $user['id'],
			      ':introduction' => $_POST['introduction'],
			        ]);
	header("HTTP/1.1 302 Found");
	  header("Location: ./introduction.php?success=1");
	  return;
}
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
a {
 color: #fff;
 font-size: 16px;
}
</style>
<body>
<a href="./index.php">設定一覧に戻る</a>

<h1>自己紹介設定</h1>
<form method="POST">
  <textarea type="text" name="introduction" rows="5"
    ><?= htmlspecialchars($user['introduction'] ?? '') ?></textarea>
  <button type="submit">決定</button>
</form>
<?php if(!empty($_GET['success'])): ?>
<div>
  自己紹介文の設定処理が完了しました。
</div>
<?php endif; ?>
</body>
</html>
