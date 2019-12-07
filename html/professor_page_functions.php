<?php

$quiet = FALSE;

/* This is for "scaffolding" to inspect variables in loops, etc.
   You might also look up highlight_string and print_r */
function debug_message($message, $continued=FALSE)
{
  global $quiet;
  
  if ($quiet) { return; }
  $html = '<span style="color:orange;">';
  $html .= $message . '</span>';
  if ($continued == FALSE) {
    $html .= '<br />';
  }
  $html .= "\n";
  echo $html;
} 


function dm($m, $c) // Shorthand
{
  debug_message($m, $c=FALSE);
}


/* Connect to a PostgreSQL DB. Returns PDO object if created. */
function connect_to_psql($db, $verbose=FALSE)
{
  $host = 'localhost'; 
  $user = 'jake_burns'; // YOU WILL HAVE TO EDIT THESE
  $pass = 'centrecollege';
  
  $dsn = "pgsql:host=$host;dbname=$db;user=$user;password=$pass";
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
  try {
    if ($verbose) {
      //debug_message('Connecting to PostgreSQL DB `classes`...', TRUE);
    }
    $pdo = new PDO($dsn, $user, $pass, $options);
    if ($verbose) {
      //debug_message('Success!');
    }
    return $pdo;
  } catch (\PDOException $e) {
    debug_message('Error: Could not connect to database! Aborting!');
    debug_message($dsn);
    debug_message($e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
}


/* Some other functions */
function style() {
	echo '<style>
        table {
            /* font-family: arial, sans-serif; */
            border-collapse: collapse;
            width: 85%;
            margin: auto;
        }

        td, th {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

	th {
	   background-color: #80C2AF;
	}
        
        div #ta{
           
            height: 500px;
            overflow: scroll;
            margin: auto;
        }

    #add_form, #edit_form {
	    position: absolute;
         top: 750px;
	     left: 35%;
        }
        
	body {background-color: #CBC0D3;}
	
	* {
         font-size: 100%;
	 font-family: "Comic Sans MS", cursive, sans-serif;
	  }
	 #title {
	 font-size: 150%;
	 font-weight: bold;
	 }
	 input {
			     cursor: pointer;
			     }
	div #add_button {
	    text-align: center;
	}
        </style>';
}

?>
