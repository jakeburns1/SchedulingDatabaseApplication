<!DOCTYPE html>
<html>
	<head>
	    <style>
body {
  background: #349eeb;
}

body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}


</style>
	<title>Achme Test Log In</title>

	</head>
	<body>
	  <h1 align = 'center'> Welcome to Acme Proctoring. Please login below!</h1>

	  <form action = 'runLogin.php' method = 'post' align = 'center'>
	    username: <input type = 'text' name = 'username'> <br>
	    password: <input type = 'password' name = 'password'><br>

	    <input type="submit" class = "button" value = "Login"><br>
	    <a href="professor_page.php">professor page</a>
	    <a href="proctors.php">proctor page</a>
	  </form>

	  
	</body>
	</html>
