<!--
AUTHOR: HENRY GUNNER
NAME: proctors.php
PURPOSE: create and fill proctor home page
DEPENDENCIES: none
--> 

<html>
  <head>
    <Title> Proctor Home Page</Title>
    <?php
      require('functions.php');
      ini_set('display_errors',1);
      error_reporting(E_ALL);
    ?>
    <style>
        h1 {
           font-size:300%;
        }
        table{
           border-collapse: collapse;
        }
    </style>
  </head>
    <body>
       <h1 align='center'> <b>Welcome Back</b> </h1>

       <h2 align ='center'>Upcomming exams</h2>
        <?php
           $pdo = connect_to_psql('acme_proctoring');
           $sql = <<<'SQL'
		SELECT name, test_date, test_time, 
		       CASE WHEN  test_isPaper = TRUE THEN 'yes'
			    ELSE 'no' 
		       END As paper, 
                       professor_name  
                  FROM tests_information

SQL;
	   $stmt = $pdo->query($sql);
	   echo "<p><table border = 1, align ='center'>";
	   echo"<tr><th>student name</th><th>test date</th><th>test time</th><th>paper copy</th><th>assigned by</th></tr>";
	   while($row = $stmt->fetch())     
	  {
		  echo"<tr>\n";
		  echo"<td>". $row['name'] . "</td>\n";
		  echo"<td>". $row['test_date'] . "</td>\n";
		  echo"<td>". $row['test_time'] . "</td>\n";
		  echo"<td>". $row['paper'] . "</td>\n";
		  echo"<td>". $row['professor_name'] . "</td>\n";


          }
       ?> 
    </body>

</html>