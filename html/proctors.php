<!--
AUTHOR: HENRY GUNNER
NAME: proctors.php
PURPOSE: create and fill proctor home page
DEPENDENCIES: professor_page_functions.php
--> 

<html>
  <head>
    <Title> Proctor Home Page</Title>
    <a href = "professor_page.php">Professor Page</a>
    <?php
        require('professor_page_functions.php');
  //    require('functions.php');
      ini_set('display_errors',1);
      error_reporting(E_ALL);
    ?>
    <style>
        h1 {
           font-size:300%;
        }
        table{
           border-collapse: thick;
        }
    </style>
  </head>
    <body>
       <h1 align='center'> <b>Welcome Back</b> </h1>

       <h2 align ='center'>Upcoming exams</h2>
       <?php

           function displayTests($pdo)
	   {
             $sql = <<<'SQL'
		     SELECT name, test_date, test_time, test_id, 
                            class,
		            CASE WHEN  test_isPaper = TRUE THEN 'yes'
			         ELSE 'no' 
		            END As paper, 
                            professor_name  
                       FROM tests_information

SQL;
	      try
	      {
	       $stmt = $pdo->query($sql);
	      }
	      catch(\PDOException $e)
	      {
               debug_message('Error Occured: '.$e);
	      }
	      echo "<p><table border = 1, align ='center'>";
	      echo"<tr><th>Student Name</th>";
	      echo"<th>Class</th>";
	      echo"<th>Assigned By</th>";
	      echo"<th>Test Date</th>";
	      echo"<th>Test Time</th>";
	      echo"<th>Paper Copy</th>";
	      echo"<th> </th><th>  </th><th> </th><th> </th></tr>";

	      echo"<form method = 'post'>";
	      $index = 0;
	      while($row = $stmt->fetch())     
	      {
		  $ids = $row['test_id'];
//		  var_dump($ids);
		  echo"<tr>\n";
		  echo"<td>". $row['name'] . "</td>\n";
		  echo"<td>". $row['class'] . "</td>\n";
                  echo"<td>". $row['professor_name'] . "</td>\n";
		  echo"<td>". $row['test_date'] . "</td>\n";
		  echo"<td>". $row['test_time'] . "</td>\n";
		  echo"<td>". $row['paper'] . "</td>\n";
         //	  echo"<td>" . submitButton('delete[' . $id . ']', 'Delete') . '</td>' . "\n";
//		  echo"<td>" . submitButton('edit[' . $id . ']', 'Edit') . '</td>' . "\n";
		  echo"<td>". "<input type='submit' name = 'start[".$ids."]' value = 'START'>" . "</td>\n";
		  echo"<td>". "<input type='submit' name = 'end[".$ids."]' value = 'END'>" . "</td>\n";

                  echo"<td>". "<input type='submit' name = 'edit[".$ids."]' value = 'EDIT'>" . "</td>\n";
		  echo"<td>". "<input type='submit' name = 'delete[".$ids."]' value = 'DELETE'>".
			      "<input type = 'hidden' name = 'test_id[".$ids."]' value = $ids>". "</td>\n" ;
		  echo"</tr>";
                  $index++;
	      }
	      echo"</form>";
	      return $pdo;
	   }
       function main()
	   {
             var_dump($_POST);
//	     $pdo = connect_to_psql('acme_proctoring');
            $pdo = connect_to_psql('project');
	//     $edit = process_post($pdo);
//	     if($edit===true)
//	     {
               if(isset($_POST['edit']))
	       {
		       echo"<p>you tried to edit a test</p>";
		  $action = $_POST['edit'];
	          foreach($_POST['test_id'] as $num)
		  {
	           try
		   {
		     echo"<p>you tried to edit".$action[$num];
		   }
		   catch(\PDOException $e){debug_message("ERROR: ".$e);}
		  }
                  echo"<UL><a href = 'proctors.php'>return</a></UL>";
	       }


//	     }
	     else
	     {
	        $pdo = displayTests($pdo);
	     }
	   }

	   main();
       ?> 
    </body>

</html>