<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
	  header("HTTP/1.1 302 Found");
	  header("Location: ./login.php");
	  return;
}

$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');

$followee_user = null;
if (!empty($_GET['followee_user_id'])) {
	  $select_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
	    $select_sth->execute([
		          ':id' => $_GET['followee_user_id'],
			    ]);
	    $followee_user = $select_sth->fetch();
}
if (empty($followee_user)) {
	  header("HTTP/1.1 404 Not Found");
	    print(" No membership information exists for such user ID ");
	    return;
}

$select_sth = $dbh->prepare(
 "SELECT * FROM user_relationships"
   . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id"
);
$select_sth->execute([

    ':followee_user_id' => $followee_user [ 'id' ], // Followed party (followee target)
        ':follower_user_id' => $_SESSION [ 'login_user_id' ], // Follower is logged in member
]);
$relationship = $select_sth->fetch();
if (!empty($relationship )) { // If there is already a following relationship, display an appropriate error and exit
	  print(" You are already following. ");
	    return;
}

$insert_result = false;
if ($_SERVER [ 'REQUEST_METHOD' ] == 'POST' ) { // If POST is done using the form, perform the actual follow registration process
	  $insert_sth = $dbh->prepare(
		      "INSERT INTO user_relationships (follower_user_id, followee_user_id) VALUES (:follower_user_id, :followee_user_id)"
		        );
	    $insert_result = $insert_sth->execute([
		          ':followee_user_id' => $followee_user [ 'id' ], // Followed party (followee target)
			        ':follower_user_id' => $_SESSION [ 'login_user_id' ], // Follower is logged in member
				  ]);
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
<?php if($insert_result): ?>
<div>
  I followed <?=htmlspecialchars( $followee_user ['name']) ?> . <br> _ _
  <a href="/profile.php?user_id=<?= $followee_user['id'] ?>">
    Return to <?=htmlspecialchars( $followee_user ['name']) ?>'s profile
  </a>
</div>
<?php  else : ?>
<div>
  <form method="POST">
  Would you like to follow <?=htmlspecialchars( $followee_user [ 'name' ]) ?> ?
    <button type="submit">
      to follow
    </button>
  </form>
</div>
<?php endif; ?>
</body>
</html>
