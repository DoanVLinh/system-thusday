<?php
$dbh= new PDO('mysql:host=mysql;dbname=linh', 'root', '');

$select_sth = $dbh->prepare('SELECT * FROM users ORDER BY id DESC');
$select_sth->execute();
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
.icon {
 display:flex;
 justify-content: start;
 align-items: center;
 padding: 1em 2em;
}
.icon:nth-of-type(even) {
  background: #42bff5;
}
</style>
<body>
  <h1>会員一覧</h1>
<div style="margin-bottom: 1em;">
    <a href="/setting/index.php">設定画面</a>
    /
    <a href="/timeline.php">タイムライン</a>
  </div>
  <?php foreach($select_sth as $user): ?>
    <div class="icon" >
      <?php if(empty($user['icon_filename'])): ?>
        <div style="height: 2em; width: 2em;"></div>
      <?php else: ?>
        <img src="/image/<?= $user['icon_filename'] ?>"
          style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
      <?php endif; ?>
      <a href="/profile.php?user_id=<?= $user['id'] ?>" style="margin-left: 1em;">
        <?= htmlspecialchars($user['name']) ?>
      </a>
    </div>
    <hr style="border: none; border-bottom: 1px solid gray;">
  <?php endforeach; ?>
</body>
