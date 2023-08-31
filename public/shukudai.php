<?php
$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');
if (isset($_POST['title'])) {
  
  $image_filename = null;
  if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    
    if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1) {
      
      header("HTTP/1.1 302 Found");
      header("Location: ./shukudai.php");
    }
    
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }
  
  $insert_sth = $dbh->prepare("INSERT INTO book (title, image_filename) VALUES (:title, :image_filename)");
  $insert_sth->execute([
    ':title' => $_POST['title'],
    ':image_filename' => $image_filename,
  ]);
  
  header("HTTP/1.1 302 Found");
  header("Location: ./shukudai.php");
  return;
}

$select_sth = $dbh->prepare('SELECT * FROM book ORDER BY created_at DESC');
$select_sth->execute();
?>
<h1> BOOK </h1>
<form method="POST" action="./shukudai.php" enctype="multipart/form-data">
  <textarea name="title" placeholder="Write something... " rows="5" cols="30"></textarea>
  <div style="margin: 1em 0;">
    <input type="file" accept="image/*" name="image" id="imageInput">
  </div>
  <button type="submit">送信</button>
</form>
<table>
<tr>
      <th>ID</th>
      <th>CREATED_AT</th>
      <th>TITLE</th>
      <th>IMAGES</th>
      </tr>
<?php foreach($select_sth as $entry): ?>
    <tr>
     <td><?= $entry['id'] ?></td>
     <td><?= $entry['created_at'] ?></td>
     <td> <?= nl2br(htmlspecialchars($entry['title']))?>	
</td>
<td>
	<?php if(!empty($entry['image_filename'])): ?>
      <div>
        <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
      </div>
<?php endif; ?>
</td>      
</tr>
<?php endforeach ?>
</table>
<style> 
table, th, td {
  border:1px solid #ccc;
}

table {
 width:100%;
}
th {
 background-color : black;
 color :#fff
}
tr:nth-child(even) {
background-color : gray;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
 const imageInput = document.getElementById("imageInput");
 const previewImage = document.getElementById("previewImage");

 imageInput.addEventListener("change", (event) => {
   const selectedFile = event.target.files[0];
   if (selectedFile) {
     if (selectedFile.size > 5 * 1024 * 1024) {
       alert("Please select an image under 5MB.");
       imageInput.value = "";
       return;
     }

     const reader = new FileReader();
     reader.onload = (event) => {
       const img = new Image();
       img.src = event.target.result;

       img.onload = () => {
         const maxWidth = 800; // Maximum width for resized image
         const scaleFactor = Math.min(maxWidth / img.width, 1);

         const canvas = document.createElement("canvas");
         const ctx = canvas.getContext("2d");
         canvas.width = img.width * scaleFactor;
         canvas.height = img.height * scaleFactor;

         ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

         const resizedDataUrl = canvas.toDataURL(selectedFile.type);
         previewImage.src = resizedDataUrl;
       };
     };

     reader.readAsDataURL(selectedFile);
   }
 });
});
</script>
