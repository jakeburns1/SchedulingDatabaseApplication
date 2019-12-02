<!DOCTYPE html>
<html>
	<head>
	<title>Achme Test Log In</title>
	</head>
	<body>
	  <h1 align = 'center'> Welcome to Acme Proctoring. Please login below!</h1>

	  <form method = 'post' align = 'center'>
	    username: <input type = 'text' name = 'username' value="testing"> <br>
	    password: <input type = 'password' name = 'password'><br>

	    <input type="submit" value = "login"><br>
	    <a href="professor_page.php">professor page</a>
	    <a href="proctors.php">proctor page</a>
	  </form>
	  
	  
	  <?php
	       $username = 'testUser';
	       $password = 'thomasallen';
	       
	       $hashed_password = password_hash($password, PASSWORD_DEFAULT);
	       
	       $insertAccountSQL = 'INSERT INTO users VALUES ($username,$hashed_password)';
	       
	       

	     ?>
	</body>
	</html>
