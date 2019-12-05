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
    
    $getAccountPassword = "SELECT password FROM users WHERE username = :username;";
     $stmt = $pdo->prepare($getAccountPassword);
	       $stmt->bindParam(':username', $username, PDO::PARAM_STR, 100);
	       $stmt->execute();
     $hashed_password = $stmt->fetchColumn(); 
    /*  $hashed_password = password_hash("barrytotter", PASSWORD_DEFAULT); 
	   
	      $insertAccountSQL = "UPDATE users SET password = :hashedPass WHERE username = 'barry.totter@centre.edu';";
	       $stmt = $pdo->prepare($insertAccountSQL);
	       $stmt->bindParam(':hashedPass', $hashed_password, PDO::PARAM_STR, 100);
	       $stmt->execute();
	 */    
	       if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
                
                //hash password and verify login
                if ( password_verify($password, $hashed_password) ) {
            		$_SESSION['user_id'] = $username;
            		echo "password was correct";
            		
            		//determine whether professor or proctor
            		$result = $pdo->prepare("SELECT * FROM professors WHERE professor_email = :username");
            		$result->bindParam(':username', $username, PDO::PARAM_STR, 20);  
            		$result->execute();
                    if ($result->fetch())
                    {
                        header('Location: http://104.197.235.157/html/html_professor_page.php');
                    }
                    else
                    {
                        
                        header('Location: http://104.197.235.157/html/proctors.php');
                    }
                }
    	        else{
    	            echo "<h1 align = 'center'>Password or username was incorrect please go back and retry </h1> ";
    	        }
	       }
	       
	       
	       
	      

	     ?>