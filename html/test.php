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

	       $username = 'testUser';
	       $password = 'thomasallen';
	       
	      /* $hashed_password = password_hash($password, PASSWORD_DEFAULT);
	       
	       $insertAccountSQL = "INSERT INTO users VALUES ($username,$hashed_password);";
	       $stmt = $pdo->prepare($insertAccountSQL);
	       $stmt->execute();
	       */

	     ?>
	</body>
	</html>
