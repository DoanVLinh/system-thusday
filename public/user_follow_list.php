<?php 
session_start();

if (empty($_SESSION['login_user_id'])) {
	  header("HTTP/1.1 302 Found");
	    header("Location: /login.php");
	    return;
}

$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');

$select_sth = $dbh->prepare('Select user_relationships.*, users.name AS follower_user_name, users.icon_filename AS follower_user_icon_filename'
  . ' FROM user_relationships INNER JOIN users ON user_relationships.follower_user_id = users.id'
  . ' WHERE user_relationships.followee_user_id = :followee_user_id'
  . ' ORDER BY user_relationships.id DESC'
);
$select_sth->execute([
	    ':followee_user_id' => $_SESSION['login_user_id'],
]);
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
<h1> Follower list </h1>

	<ul>
	  <?php foreach($select_sth as $relationship): ?>
	    <a href="/profile.php?user_id=<?= $relationship['follower_user_id'] ?>">
	        <?php  if (!empty($relationship [ 'follower_user_icon_filename' ])): // Display icon image if available ?>
		    <img src="/image/<?= $relationship['follower_user_icon_filename'] ?>"
		          style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
			      <?php endif; ?>

    <?= htmlspecialchars($relationship['follower_user_name']) ?>
    (ID: <?= htmlspecialchars($relationship['follower_user_id']) ?>)

</a>
  ( follow <?= $relationship [ 'created_at' ] ?> )
  <?php endforeach; ?>
</ul>
</body>
</html>
