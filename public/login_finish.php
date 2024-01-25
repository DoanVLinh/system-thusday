<?php
session_start();
           if(empty($_SESSION['login_user_id'])) {
                 header("HTTP/1.1 302 Found");
                 header("Location: ./login.php");
		 return;
		}
	    
	    $dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');

	    $insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
	    $insert_sth->execute([
		        ':id' => $_SESSION['login_user_id'],
			]);
	    $user = $insert_sth->fetch();
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
.login {
  width: 600px;
  padding 8% 0 0;
  margin: auto;
}
.form {
 position: relative;
  z-index: 1;
  background: #42bff5;
  max-width: 500px;
  margin: 0 auto 100px;
  padding: 30px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
a {
 color: #fff;
 font-size: 16px;
}
.info{
  display:flex; 
  flex-wrap: wrap; 
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
</style>
<body>
<div class= "login">
<h1> Login completed </h1>

<p>
  Login completed! <br>
  <a href ="/timeline.php">タイムラインはこちら</a> 
</p>
<div class = "form">
<p>
  In addition, the member information for which you are currently logged in is as follows.
</p>

<dl class="info">  <!-- When outputting registration information, be sure to use htmlspecialchars() to prevent XSS -->
  <dt>ID</dt>
  <dd><?= htmlspecialchars($user['id']) ?></dd>
  <dt> Email address </dt>
  <dd><?= htmlspecialchars($user['email']) ?></dd>
  <dt> Name </dt>
  <dd><?= htmlspecialchars($user['name']) ?></dd>
</dl>
</div>
</div>
</body>
</html>
