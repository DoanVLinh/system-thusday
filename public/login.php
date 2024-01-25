<?php
$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
	$select_sth = $dbh->prepare("SELECT * FROM users WHERE email = :email ORDER BY id DESC LIMIT 1");
	$select_sth->execute([
		':email' => $_POST['email'],
	]);
	$user = $select_sth->fetch();

	 if (empty($user)) {
		     header("HTTP/1.1 302 Found");
		         header("Location: ./login.php?error=1");
		         return;		   
	 }

        $correct_password = password_verify($_POST['password'],$user['password']);
         if (!$correct_password) {
	     header("HTTP/1.1 302 Found");
	         header("Location: ./login.php?error=1");
	         return;
	 }
	

	session_start();
	$_SESSION["login_user_id"] = $user['id'];

	 header("HTTP/1.1 302 Found");
                 header("Location: ./login_finish.php");
                 return;
	
}
?>
<html>
<style>
body{
  background:#3c69f8;
  font-family:Helvetica;
}
h1{
  text-align: center;
  background: #42bff5;
  padding: 10px;
  font-size: 1.4em;
  font-weight: bold;
  color: #fff;
}
.login {
  width: 500px;
  padding 8% 0 0;
  margin: auto;
}
form {
 position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 500px;
  margin: 0 auto 100px;
  padding: 30px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
input[type="email"], input[type="password"] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  background: #ccc
}
button {
  width: 100%;
  padding: 14px 20px;
  background-color:#42bff5;
  margin: 8px 0;
  cursor: pointer;
  color:#fff;
  font-weight: bold;
}
a {
color:#fff;
}
button:hover {
  background:#3c69f8;
}
</style>
<body>
<div class="login">
<h1> Login </h1>
<form method="POST">
    <input type="email" name="email" placeholder="Email">
  <br>
    <input type="password" name="password" min="6" autocomplete="new-password" placeholder="Password">
  <br>
  <button type="submit">決定</button>
  <button type="submit"><a href =" /signup.php ">登録</a></button>
</form>
</div>

<?php  if (!empty( $_GET [ 'error' ])): // Display error message if there is a query parameter for error ?>
<div style="color: red; ">
  Your email address or password is incorrect.
</div>
<?php endif; ?>
<body>
</html>
