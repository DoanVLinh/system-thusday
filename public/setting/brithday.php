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

if (isset($_POST['date_birth'])) {

  $update_sth = $dbh->prepare("UPDATE users SET date_birthy = :date_birth WHERE id = :id");
  $update_sth->execute([
      ':id' => $user['id'],
      ':date_birthy' => $_POST['date_birth'],
  ]);

  header("HTTP/1.1 302 Found");
  header("Location: ./brithday.php?success=1");
  return;
}
?>
<html>
<style>
body{
  background:#3c69f8;
  font-family:Helvetica;
  color: #fff;
}
h1{
  text-align: center;
  background: #42bff5;
  padding: 10px;
  font-size: 1.4em;
  font-weight: bold;
  color: #fff;
}
form {
 position: relative;
  z-index: 1;
  background: #42bff5;
  max-width: 500px;
  margin: 0 auto 100px;
  padding: 30px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
  color: #333;
}

a {
 color: #fff;
 font-size: 16px;
}
</style>
<body>
<a href="./index.php">設定一覧に戻る</a>

<h1>生年月日</h1>
<form method="POST">
  <input type="date" name="date_birth" value="<?= htmlspecialchars($user['date_birth']) ?>">
  <button type="submit">決定</button>
</form>

<?php if(!empty($_GET['success'])): ?>
<div>
</div>
<?php endif; ?>
</body>
</html>
