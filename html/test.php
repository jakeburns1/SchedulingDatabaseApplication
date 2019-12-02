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
	  session_start();
	  ini_set('display_errors',1);
 error_reporting(E_ALL);
 function debug_message($message, $continued=FALSE)
{
  $html = '<span style="color:orange;">';
    $html .= $message . '</span>';
      if ($continued == FALSE) {
          $html .= '<br />';
	}
	  $html .= "\n";
	  echo $html;
}

function connect_to_psql($db, $verbose=FALSE)
{
  $host = 'localhost';
    $user = 'jake_burns'; // YOU WILL HAVE TO EDIT THESE
      $pass = 'centrecollege';

  $dsn = "pgsql:host=$host;dbname=$db;user=$user;password=$pass";
    $options = [
        PDO::ATTR_ERRMODE	     => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
	];
	try {
	  if ($verbose) {
	     debug_message('Connecting to PostgreSQL DB `classes`...', TRUE);
	  }
	  $pdo = new PDO($dsn, $user, $pass, $options);
	  if ($verbose){
	     debug_message('Success!');
	  }
	  return $pdo;
	  }
	  catch (\PDOException $e){
	  debug_message('Error: Could not connect to database! Aborting!');
	  debug_message($dsn);
	  debug_message($e);
	  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

	  }
$dBase = 'project';
$pdo =connect_to_psql($dBase);
    
    $username = $_POST['username'];
    $password = $_POST['password'];
         $hashed_password = password_hash($password, PASSWORD_DEFAULT);
	       
	       $insertAccountSQL = "UPDATE users SET password = :hashedPass WHERE username = 'testUser';";
	       $stmt = $pdo->prepare($insertAccountSQL);
	       $stmt->bindParam(':hashedPass', $hashed_password, PDO::PARAM_STR, 100);
	       $stmt->execute();
	       
	       if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
	             $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                 $stmt->bindParam('s', $username);   
                 
                 
             if ( password_verify( $_POST['password'], $hashed_password) ) {
    		$_SESSION['user_id'] = $user;
    		echo "password was correct";
    	}
    	else{
    	    
    	    echo "password was incorrect";
    	}
	       }
	       
	       
	       
	      

	     ?>
	</body>
	</html>
