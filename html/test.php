<!DOCTYPE html>
<html>
	<head>
	    <style>
body {
  background: #f1c40f;
}

.button {
  background: #3498db;
  width: 180px;
  padding: 4px 0;
  
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translateX(-50%) translateY(-50%);
  border-radius: 3px;

  p {
   	font-family: 'Roboto'; 
	  text-align: center;
  	text-transform: uppercase;
    color: #FFF;
    user-select: none;
  }
  
  &:hover {
    cursor: pointer;
  }
  
  &:after {
    content: "";
    display: block;
    position: absolute;
    width: 100%;
    height: 10%;
    border-radius: 50%;
    background-color: darken(#f1c40f, 20%);
    opacity: 0.4;
    bottom: -30px;
  }
}
</style>
	<title>Achme Test Log In</title>

	</head>
	<body>
	  <h1 align = 'center'> Welcome to Acme Proctoring. Please login below!</h1>

	  <form action = 'runLogin.php' method = 'post' align = 'center'>
	    username: <input type = 'text' name = 'username'> <br>
	    password: <input type = 'password' name = 'password'><br>

	    <input type="submit" class = "button" value = "login"><br>
	    <a href="professor_page.php">professor page</a>
	    <a href="proctors.php">proctor page</a>
	  </form>

	  
	</body>
	</html>
