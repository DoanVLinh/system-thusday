<?php
$dbh=new PDO('mysql:host=mysql;dbname=linh', 'root', '');

session_start();
if (empty($_SESSION['login_user_id'])) {
          header("HTTP/1.1 302 Found");
            header("Location: /login.php");
            return;
}

$user_select_sth = $dbh->prepare("SELECT * from users WHERE id = :id");
$user_select_sth->execute([':id' => $_SESSION['login_user_id']]);
$user = $user_select_sth->fetch();

if (isset($_POST['body']) && !empty($_SESSION['login_user_id'])) {
	$image_filename = null;
  if (!empty($_POST['image_base64'])) {

    $base64 = preg_replace('/^data:.+base64,/', '', $_POST['image_base64']);


    $image_binary = base64_decode($base64);


    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.png';
    $filepath =  '/var/www/upload/image/' . $image_filename;
    file_put_contents($filepath, $image_binary);
  }


  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (user_id, body, image_filename) VALUES (:user_id, :body, :image_filename)");
  $insert_sth->execute([
      ':user_id' => $_SESSION['login_user_id'],
      ':body' => $_POST['body'],
      ':image_filename' => $image_filename,
  ]);


  header("HTTP/1.1 302 Found");
  header("Location: ./timeline.php");
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

dt {
 background-color: #42bff5;
 font-size: 1.2em;
 font-family:bold;

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
button:hover {
  background:#3c69f8;
}
</style>
<body>
<div>
 <h1> 現在 <?= htmlspecialchars($user['name']) ?> (ID: <?= $user['id'] ?>) さんでログイン中</h1>
</div>
<div style="margin-bottom: 1em;">
  <a href="/setting/index.php">設定画面</a>
  /
  <a href="/users.php">会員一覧画面</a>
</div>
<form method="POST" action="./timeline.php"><!-- enctypeは外して
おきましょう -->
    <textarea name="body" required rows="10" cols="60"></textarea>
    <div style="margin: 1em 0">
      <input type="file" accept="image/*" name="image" id="imageInput" multiple="true">
    </div>
    <input id="imageBase64Input" type="hidden" name="image_base64"><!-- base64を送る用のinput (非表示) -->
    <canvas id="imageCanvas" style="display: none;"></canvas><!-- 画像縮小に使うcanvas (非表示) -->
    <button type="submit">送信</button>
  </form>
<dl id="entryTemplate" style="display: none; margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
  <dt>番号</dt>
  <dd data-role="entryIdArea"></dd>
  <dt>投稿者</dt>
  <dd>
    <a href="" data-role="entryUserAnchor">
         <img data-role="entryUserIconImage"
        style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
      <span data-role="entryUserNameArea"></span>
</a>
  </dd>
  <dt>日時</dt>
  <dd data-role="entryCreatedAtArea"></dd>
  <dt>内容</dt>
  <dd data-role="entryBodyArea">
  </dd>

</dl>
<div id="entriesRenderArea">
</div>
</body>
</html>

<script>

document.addEventListener("DOMContentLoaded", () => {
    const entryTemplate = document.getElementById('entryTemplate');
    const entriesRenderArea = document.getElementById('entriesRenderArea');
    let page = 1;
    function fetchEntries() {
    const request = new XMLHttpRequest();
    request.onload = (event) => {
      const response = event.target.response;
      response.entries.forEach((entry) => {
      
        const entryCopied = entryTemplate.cloneNode(true);

        entryCopied.style.display = 'block';
        
        entryCopied.id = 'entry' + entry.id.toString();
        if (entry.user_icon_file_url !== undefined) {
            entryCopied.querySelector('[data-role="entryUserIconImage"]').src = entry.user_icon_file_url;
          } else {
            entryCopied.querySelector('[data-role="entryUserIconImage"]').display = 'none';
          }
        
        entryCopied.querySelector('[data-role="entryIdArea"]').innerText = entry.id.toString();
   
        entryCopied.querySelector('[data-role="entryUserNameArea"]').innerText = entry.user_name;
     
        entryCopied.querySelector('[data-role="entryUserAnchor"]').href = entry.user_profile_url;
       
        entryCopied.querySelector('[data-role="entryCreatedAtArea"]').innerText = entry.created_at;
       
        entryCopied.querySelector('[data-role="entryBodyArea"]').innerHTML = entry.body;
  
        if (entry.image_file_url !== undefined) {
          const imageElement = new Image();
          imageElement.src = entry.image_file_url; // 画像URLを設定
          imageElement.style.display = 'block'; // ブロック要素にする (img要素はデフォルトではインライン要素のため)
          imageElement.style.marginTop = '1em'; // 画像上部の余白を設定
          imageElement.style.maxHeight = '300px'; // 画像を表示する最大サイズ(縦)を設定
          imageElement.style.maxWidth = '300px'; // 画像を表示する最大サイズ(横)を設定
          entryCopied.querySelector('[data-role="entryBodyArea"]').appendChild(imageElement); // 本文エリアに画像を追加
        }
        entriesRenderArea.appendChild(entryCopied);
      });
    };
    request.open('GET', `/timeline_json.php?page=${page}`, true); 
    request.responseType = 'json';
    request.send();
    page++;
    }
    fetchEntries();
    window.addEventListener("scroll", () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 10) {
            fetchEntries();
        }
    });
    const imageInput = document.getElementById("imageInput");
    imageInput.addEventListener("change", () => {
      if (imageInput.files.length < 1) {
      
	              return;
		            }
            const file = imageInput.files[0];
            if (!file.type.startsWith('image/')){ 
		            return;
	          }
	         
	          const imageBase64Input = document.getElementById("imageBase64Input"); 
		        const canvas = document.getElementById("imageCanvas"); 
		        const reader = new FileReader();
			      const image = new Image();
			      reader.onload = () => { 
			              image.onload = () => { 
				                
				                const originalWidth = image.naturalWidth;
						          const originalHeight = image.naturalHeight;
						          const maxLength = 1000;
							            if (originalWidth <= maxLength && originalHeight <= maxLength) { 
									                  canvas.width = originalWidth;
											                canvas.height = originalHeight;
											            } else if (originalWidth > originalHeight) { // 横長画像の場合
													                  canvas.width = maxLength;
															                canvas.height = maxLength * originalHeight / originalWidth;
															            } else { // 縦長画像の場合
																	                  canvas.width = maxLength * originalWidth / originalHeight;
																			                canvas.height = maxLength;
																			            }
							           
							            const context = canvas.getContext("2d");
							            context.drawImage(image, 0, 0, canvas.width, canvas.height);
								              
								              imageBase64Input.value = canvas.toDataURL();
								            };
				              image.src = reader.result;
				            };
			            reader.readAsDataURL(file);
			          });
      });
  </script>
