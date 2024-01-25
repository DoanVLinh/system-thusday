<?php
session_start();
if (empty($_SESSION['login_user_id'])) {
	  header("HTTP/1.1 302 Found");
	    header("Location: ./login.php");
	    header("Location: /login.php");
	      return;
}
$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');
$insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$insert_sth->execute([
	    ':id' => $_SESSION['login_user_id'],
]);
$user = $insert_sth->fetch();
if (isset($_POST['name'])) {
	$insert_sth = $dbh->prepare("UPDATE users SET name = :name WHERE id = :id");
	  $insert_sth->execute([
		        ':id' => $user['id'],
			      ':name' => $_POST['name'],
			        ]);
	header("HTTP/1.1 302 Found");
	header("Location: /setting/name.php?success=1");
	return;
}
?>
<html>
<body>
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
<a href="./index.php">設定一覧に戻る</a>
<h1>名前変更</h1>
<form method="POST">
  <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
  <button type="submit">決定</button>
</form>
<?php if(!empty($_GET['success'])): ?>
<div>
  名前の変更処理が完了しました。
</div>
<?php endif; ?>
</body>
</html>
