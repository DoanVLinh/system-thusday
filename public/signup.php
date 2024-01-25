<?php
$dbh = new PDO('mysql:host=mysql;dbname=linh', 'root', '');
if (!empty($_POST['name']) && !empty($_POST['email'])&& !empty($_POST['password']) && !empty($_POST['date_birth'])) {
 	
	$insert_sth = $dbh->prepare("INSERT INTO users (name, email, password, date_birth) VALUES (:name, :email, :password, :date_birth)");
         $insert_sth->execute([
             ':name' => $_POST['name'],
	     ':email' => $_POST['email'],
	     ':password' => password_hash($_POST['password'] ,PASSWORD_DEFAULT),
	     ':date_birth' => $_POST['date_birth']
                      ]);
                          // When the process is finished, redirect to the completion screen
                          header("HTTP/1.1 302 Found");
                            header("Location: /signup_finish.php");
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
.sign {
  width: 800px;
  padding 8% 0 0;
  margin: auto;
}

form {
 position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 800px;
  margin: 0 auto 100px;
  padding: 30px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
label { 
  font-size: 20px;
  font-weight: bold;
}
form input {
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
.singin { 
 background-color:#ccc;
text-align: center;
}
button:hover {
  background:#3c69f8;
}

</style>
<body>
                               
			      
<div class= "sign">
<h1>Register</h1>
                              <form method="POST">
                                 <!-- The type attribute of the input element all works with text, but setting it to an appropriate value makes it easier for users to use -->
                                  <label>
				       Name
				
                                           <input type="text" name="name">
                                  </label>         
                                              <br>
                                                <label>
                                                    Email address:
                                                         <input type="email" name="email">
                                                           </label>
                                                             <br>
                                                              <label>
                                                                  Password:
                                                                     <input type="password" name="password" min="6" autocomplete="new-password">
                                                                        </label>
									  <br>
								<label> 
						Date of Birth:
<input type="date" id"date_birth" name="date_birth">
</label>
                                                                            <button type="submit">決定</button>
<div class= "singin"> 
Already a registered member <a href =" /login.php "> Log in </a>
</div>

									  </form>
							    
</div>
<?php if(!empty($_GET['duplicate_email'])): ?>
<div style="color: red;">
  The email address you entered is already in use.
</div>

<?php endif; ?>
</body>
</html>
