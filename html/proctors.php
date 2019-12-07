<!--Proctors page
AUTHOR: 
DESCRIPTION: This page renders the proctors home page. All functions are exectued by main(), 
which is at the bottom of the script. 
FUNCTIONS: 
	function displaySchedule($pdo) 
	function displayTests($pdo)
	function setTimeZone($pdo)
	function getStudName($pdo,$sid,$tid)
	function selectTest($pdo)
	
	function inProgress($pdo,$stud_id,$test_id)
	function completed($pdo,$stud_id,$test_id)
	function startIsValued($pdo,$stud_id,$test_id,$print)
	function endIsValued($pdo,$stud_id,$test_id)
	
	function startButton($pdo)
	function endButton($pdo)
	function editButton($pdo)
	function updateTest($pdo)
--> 
<?php
    session_start();
      require('professor_page_functions.php');
//      require('functions.php');
      ini_set('display_errors',1);
      error_reporting(E_ALL);
    ?>
    
<html>
    <head>
    <Title> Proctor Home Page</Title>
    <style>
        h1 {
           font-size:300%;
        }

        td, th{
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }
        
        body {
            background-color: #CBC0D3;
        }
        
        * {
         font-family: "Comic Sans MS", cursive, sans-serif;
        }
        
        table {
            margin: auto;
            border-collapse: thick;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        
        th {
           background-color: #80C2AF;
        }
        
        input, button {
		   cursor: pointer;
		}
        
    </style>
  </head>
    <body>
       <h1 align='center'> <b>Welcome Back</b> </h1>
    </body>

</html>
    <?php
if ( isset( $_SESSION['user_id'] ) ) {

        echo "User logged in :" .$_SESSION['user_id'];
        echo "<form method = 'post' action = 'logout.php'> <input type = 'submit' value = 'logout'></form>";
        
        function displaySchedule($pdo)
	   {
             $sql = <<<'SQL'
		     SELECT * FROM proctors_schedule 
                     WHERE proctor_email = :user_id 
SQL;
             $stmt = $pdo->prepare($sql);
	     $data['user_id'] = $_SESSION['user_id'];
	     try
	     {
		 $stmt->execute($data);
	//	 $schedule= $stmt->fetchALL();
	     }
	     catch (\PDOException $e)
	     {
		 debug_message("Error: ".$e);
	     }
	  
	     $i = 0;
	     while($row = $stmt->fetch())
	     {
		$days[$i] = $row['day_name'];
		$proctor_ids[$i] = $row['proctor_id'];
		$start_times[$i] = $row['start_shift'];
		$end_times[$i] = $row['end_shift'];	
	//	echo"<tr><th>$day</th></tr>";
		$i++;
	     }

	    /* for($l = 0; $l<$i; $l++)
	     {
               $days[$l] = trim($days[$l]);
	     }*/
	    // $hold[0] = $days;
	     // $hold[] = $proctor_ids;
	     if($i===0){$start_times = null; $end_times = null;}
	     else
	     {
	        $hold[1] = $start_times;
	        $hold[2] = $end_times;
	     }

	     echo"<h2 align = 'center'>This weeks work schedule</h2>";
	     echo "<p><table border = 1, align ='center'></p><tr>";

	     for($j = 0; $j < $i; $j++)
	     {
	       echo "<th>". $days[$j]."</th>";
	     }

	     for($k = 1; $k < 3; $k++)
	     {
		echo"<tr>";
		for($j = 0; $j < $i; $j++)
		{
		   echo "<td>".$hold[$k][$j]."</td>";
		}
		echo"</tr>";
	     }
	     if($j===0){echo"<p align = 'center'>No work this week</p>";}
	     echo"</th></tr></table>";
	   }

         function displayTests($pdo) // Creates the table displaying test information for current day
	   {
		   $stmt_and_data= newQuery($pdo); //this function allows for changing the queries
		   $stmt = $stmt_and_data[0];
		   $data = $stmt_and_data[1];
	      try
	      {
		     $stmt->execute($data);     
	      }
	      catch(\PDOException $e)
	      {
	       echo "<p align = 'center'><b>ERROR</b>: incorrect input type</p>";//debug_message('Error Occured: '.$e);
	      }
	      echo"<h2 align ='center'>Upcoming exams</h2>";

              echo"<form method = 'post'>";
              //---------Creates drop down for View by.---------------
	      echo"<p align = 'center'>View by: <select name='Filter'>".
		        "<option value ='all'</option>all<br />".
			"<option value ='date'</option>date<br />".
			"<option value ='name'</option>name<br />".
                        "<option value ='time'</option>time<br />".
			"<option value ='status'</option>status<br />";
	      // <option value="Incomplete"</option>Incomplete<br /> 
	      echo"</select>";//echo"<button  type = 'submit' name='change_filter' value ='enter'>ENTER</button></p>";
	      //------------------------------------------------------

	      //---Creates table for displaying tests ---------------------------
	      //echo"<form method = 'post'>";
	      //echo"<input type = 'text' name='filter_value' value = 'what you want'></p>";
	      $val_to_show = ""; 
	      if(isset($_POST['Filter'])){$val_to_show = $_POST['Filter'];}

	      echo"<button  type = 'submit' name='change_filter' value ='enter'>ENTER</button>";
	     // echo" Currently Viewing by : ".$val_to_show."</p>";
	     //
	      echo "<p><table border = 1, align ='center'>";
	      echo"<tr><th>Student Name</th>";
	      echo"<th>Class</th>";
	      echo"<th>Assigned By</th>";
	      echo"<th>Test Date</th>";
	      echo"<th>Test Time</th>";
	      echo"<th>Paper Copy</th>";
	      echo"<th>Status</th>";
	      echo"<th> </th><th> </th><th> </th></tr>"; //<th> </th></tr>";
	    //  echo"<form method = 'post'>";
	      $index = 0;
	      while($row = $stmt->fetch())     
	      {
		  $test_id = $row['test_id'];
		  $student_id = $row['student_id'];
		  $ids[0] = $student_id;
		  $ids[1] =  $test_id;

		  echo"<tr>\n";
		  echo"<td>". $row['name'] . "</td>\n";
		  echo"<td>". $row['class'] . "</td>\n";
		  echo"<td>". $row['professor_name'] . "</td>\n";
		  echo"<td>". $row['test_date'] . "</td>\n";
		  echo"<td>". $row['test_time'] . "</td>\n";
		  echo"<td>". $row['paper'] . "</td>\n";
		  echo"<td>". $row['test_status'] . "</td>\n";

		  echo"<td>". "<button type='submit' name = 'start' value = ".$student_id.",".$test_id.">START</button" . "</td>\n";
		  echo"<td>". "<button type='submit' name = 'end' value = ".$student_id.",".$test_id.">END</button>" . "</td>\n";
		  echo"<td>". "<button type='submit' name = 'edit' value = ".$student_id.",".$test_id.">EDIT</button>" . "</td>\n";
		  echo"</tr>";
		  $index++;
	      }
	      //------------------------------------------------------------------------
	      echo"</form>";
	      if($index ===0)
	      {
                echo"<p align = 'center'><b>Error</b>: no matching data in database</p>";
	      }
	      return $pdo;
	   }

       function newFilter($pdo) //This form gets the specific value the user
	   {                        //wants to query. Does this based on the Filter Selected. 
	     if(isset($_POST['Filter']))
	     {
		   $catagory = $_POST['Filter'];
		   $value = NULL;
		   if($_POST['Filter']==="name")      {$value = "first_name last_name";}
		   elseif($_POST['Filter']==="date")  {$value = "YYYY-mm-dd";}
		   elseif($_POST['Filter']==="status"){$value = "Pending, in Progress, Completed, Incomplete";}
		   elseif($_POST['Filter'] ==="time") {$value = "enter a time hh:mm (on 24 hour scale)";} 

		   if($value !== NULL) {
		       echo"<h2>Input the ".$catagory." you would like to search</h2>";
		       $html = <<<_HTML_
			   <form method = "post">
				<input  type = 'text' name='filter_value' value = '' >$value
				<button type = 'submit' name = 'apply_filter' value = $catagory >ENTER</button> 
                           </form>
_HTML_;

                      echo $html;
                      echo"<a href = 'proctors.php'>Cancel</a></form></p>";
		   }
		   else
		   {
		       echo"<h2>Confirm 'View by all' selection</h2>";
		       echo"<p align = 'center'><form  method = 'post'><button type = 'submit' name = 'apply_filter' value = 'all' >ENTER</button>";
                       echo"<a href = 'proctors.php'>Cancel</a></form></p>";
		   }
	     }
	     else{ echo "thinks Filter isn't set";}
	   }

	   function newQuery($pdo) //used to create the proper queries to query the database. 
	   {                       //runs different queries if specified, otherwise runs  the below query. 
                   $sql = <<<'SQL'
			     SELECT name, test_date, test_time, test_id,test_status, student_id,
				    class,
				    CASE WHEN  test_isPaper = TRUE THEN 'yes'
					 ELSE 'no' 
				    END As paper, 
				    professor_name  
			     FROM tests_information
			     WHERE(tests_information.proctor_email= :proctor_email)
SQL;

		   $stmt = $pdo->prepare($sql); 
	           $data['proctor_email'] = $_SESSION['user_id']; //'barry.totter@centre.edu'; //''thomas.allen@centre.edu';
		   $query_type = 'all';
		   $value = "";

		   if(isset($_POST['apply_filter']) AND isset($_POST['filter_value']))
		   {
		      $query_type = $_POST['apply_filter'];
		      $value = $_POST['filter_value'];
                      //var_dump($_POST);

		     if($query_type ==="date")//queries based on the date given. 
		     {
			//echo"sort by date";
                        $sql = <<<'SQL'
			     SELECT name, test_date, test_time, test_id,test_status, student_id,
				    class,
				    CASE WHEN  test_isPaper = TRUE THEN 'yes'
					 ELSE 'no' 
				    END As paper, 
				    professor_name  
			     FROM tests_information
			     WHERE(tests_information.proctor_email= :proctor_email AND test_date =:test_date)
SQL;
			$stmt = $pdo->prepare($sql);	
			$data['test_date'] = $value;	
		     }
		     elseif ($query_type ==="name")
		     { 
                        $sql = <<<'SQL'
			     SELECT name, test_date, test_time, test_id,test_status, student_id,
				    class,
				    CASE WHEN  test_isPaper = TRUE THEN 'yes'
					 ELSE 'no' 
				    END As paper, 
				    professor_name  
			     FROM tests_information
			     WHERE(tests_information.proctor_email= :proctor_email AND name = :name)
SQL;
		       $stmt = $pdo->prepare($sql); 
		       $data['name'] = $value;

		     }
		     elseif ($query_type ==='status')
	             { 
                         $sql = <<<'SQL'
			     SELECT name, test_date, test_time, test_id,test_status, student_id,
				    class,
				    CASE WHEN  test_isPaper = TRUE THEN 'yes'
					 ELSE 'no' 
				    END As paper, 
				    professor_name  
			     FROM tests_information
			     WHERE(tests_information.proctor_email= :proctor_email AND test_status =:test_status)
SQL;
		       $stmt = $pdo->prepare($sql); 
		       $data['test_status'] = $value;
		     }
                     elseif ($query_type ==='time')
	             { 
                         $sql = <<<'SQL'
			     SELECT name, test_date, test_time, test_id,test_status, student_id,
				    class,
				    CASE WHEN  test_isPaper = TRUE THEN 'yes'
					 ELSE 'no' 
				    END As paper, 
				    professor_name  
			     FROM tests_information
			     WHERE(tests_information.proctor_email= :proctor_email AND test_time =:test_time)
SQL;
		         $stmt = $pdo->prepare($sql); 
			 $data['test_time'] = $value;
		     }

		   }

              $stmt = $pdo->prepare($sql);
              return [$stmt,$data];
	   }

	   function setTimeZone($pdo) // sets the time zone
	   {
	     $sql = <<<'SQL'
		   SET TIME ZONE 'America/New_York'
SQL;
	     $stmt = $pdo->prepare($sql);
	     $stmt->execute(); //$pdo->query($stmt);
	   }

	   function getStudName($pdo,$sid,$tid) //returns the student name associate with the student_test reservation
	   {                                    // as a string. 
	    $sql = <<<'SQL'
		   SELECT name FROM tests_information 
		   WHERE student_id = :sid AND test_id = :tid
SQL;
	    $stmt = $pdo->prepare($sql);
	    $data['sid'] = $sid;
	    $data['tid'] = $tid;
	      try
	      {
		      $stmt->execute($data);
		      $name = $stmt->fetch();
		      return $name['name'];
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }

	   }

	   function selectTest($pdo) // selects the desired test.
	   {                         //returns the test as an array of attributes.
	      $ids = explode(",",filter_input(INPUT_POST, 'edit' , FILTER_SANITIZE_STRING));
	      $sql = <<<'SQL'
		   SELECT test_start_time, test_end_time, test_status,
			  student_id, test_id, test_description
		   FROM students_tests 
		   WHERE student_id = :sid AND test_id = :tid
SQL;
	      $stmt = $pdo->prepare($sql);
	      $data['sid'] = $ids[0];
	      $data['tid'] = $ids[1];
	      try
	      {
		      $stmt->execute($data);
		      $test = $stmt->fetch();
		      return $test;
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }
	   }

	   function inProgress($pdo,$stud_id,$test_id)//helper funciton, updates status of started test to "in progress".
	   {
	      $string = "in Progress";
	      $sql = <<<'SQL'
		      UPDATE students_tests
		      SET test_status = :string
			  WHERE student_id = :stud_id AND test_id = :test_id
SQL;
	      $stmt = $pdo->prepare($sql);
	      $data['stud_id'] = $stud_id;
	      $data['test_id'] = $test_id;
	      $data['string'] = $string;
	      try
	      {
		      $stmt->execute($data);
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }
	   }

	   function completed($pdo,$stud_id,$test_id)//helper function, updates Status to "completed" when end_time entered
	   {
	      $string = "Completed";
	      $sql = <<<'SQL'
		      UPDATE students_tests
		      SET test_status = :string
			  WHERE student_id = :stud_id AND test_id = :test_id
SQL;
	      $stmt = $pdo->prepare($sql);
	      $data['stud_id'] = $stud_id;
	      $data['test_id'] = $test_id;
	      $data['string'] = $string;
	      try
	      {
		      $stmt->execute($data);
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }
	   }

	   function startIsValued($pdo,$stud_id,$test_id,$print) //helper function, checks if test_start_time is valued or not for given reservation.                                                    //returns true if it is valued, flase otherwise. 
	   {
		   $sql = <<<'SQL'
			  SELECT test_start_time FROM students_tests
			  WHERE student_id = :stud_id AND test_id = :test_id
SQL;
	      $stmt = $pdo->prepare($sql);
	      $data['stud_id'] = $stud_id;
	      $data['test_id'] = $test_id;

	      try
	      {
		      $stmt->execute($data);
//    		      $result = $pdo->query($stmt);
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }
//	      $result = $pdo->query($stmt);

	      $value = $stmt->fetch();
	      if (is_null($value['test_start_time']))
	      {
		      if($print) {echo"<UL><p align='center'>does not have  a start time</p></UL>";}
		      return false;
	      }
	      else
	      {
		  if($print) {echo"<UL><p align='center'><b>ERROR:</b> already  has a start time of (".$value['test_start_time'].")</p></UL>";}
		  return true;
	      }
	   }

	   function endIsValued($pdo,$stud_id,$test_id) //returns true if test_end_time is not null, else false. 
	   {
	      $sql = <<<'SQL'
			  SELECT test_end_time FROM students_tests
			  WHERE student_id = :stud_id AND test_id = :test_id
SQL;
	      $stmt = $pdo->prepare($sql);
	      $data['stud_id'] = $stud_id;
	      $data['test_id'] = $test_id;

	      try
	      {
		      $stmt->execute($data);
	      }
	      catch (\PDOException $e)
	      {
		  debug_message("Error: ".$e);
	      }

	      $value = $stmt->fetch();
	      if (is_null($value['test_end_time']))
	      {
		      echo"<UL><p align='center'>does not have  an end time</p></UL>";
		      return false;
	      }
	      else
	      {
		  echo"<UL><p align='center'><b>ERROR</b>: already  has an end time of (".$value['test_end_time'].")</p></UL>";
		  return true;
	      }

	   }

	   function startButton($pdo) //Updates the start time for the reservation. 
	   {
	     $ids = explode(",",filter_input(INPUT_POST, 'start' , FILTER_SANITIZE_STRING));
	     echo"<h2 align = 'center'>Setting start time for ".getStudName($pdo,$ids[0],$ids[1])."'s test...</h2><br />";

	     if (startIsValued($pdo,$ids[0],$ids[1],true)===false) 
	     { 	       
	       $curr_time_sql = <<<'SQL'
			       SELECT to_char(LOCALTIME,'HH:MI')
SQL;
	       $current_time = $pdo->query($curr_time_sql);
	       $current_time = $current_time->fetch();
	     //  var_dump($current_time);
	       $current_time = $current_time['to_char'];

	       $sql = <<<'SQL'
		      UPDATE students_tests 
		      SET test_start_time = :test_start_time
		      WHERE students_tests.student_id = :stud_id AND students_tests.test_id = :test_id
SQL;
	       $stmt = $pdo->prepare($sql);
	       $data['stud_id'] = $ids[0];
	       $data['test_id'] = $ids[1];
	       $data['test_start_time'] = $current_time;

	       try 
	       {
		       echo"<UL><p align='center'>Start Time recorded successfully.</p></UL>";
		       $stmt->execute($data);
	       }
	       catch(\PDOException $e)
	       {
		   debug_message('Error Occured: '.$e);
		   echo"<p><a href = 'proctors.php'>return</a></p>";
	       }
	       inProgress($pdo,$ids[0],$ids[1]);
	     }
	     else { echo"<UL><p align='center'> You have already given this test a start time</p></UL>";}
	   }

	   function endButton($pdo) //enters the end time for the reservation
	   {
	     $ids = explode(",",filter_input(INPUT_POST, 'end' , FILTER_SANITIZE_STRING));
	     echo"<h2 align = 'center'>Setting  end  time for ".getStudName($pdo,$ids[0],$ids[1])."'s test...</h2><br />";

	     if (endIsValued($pdo,$ids[0],$ids[1])===false AND startIsValued($pdo,$ids[0],$ids[1],false) === true)
	     { 	       
	       $curr_time_sql = <<<'SQL'
			       SELECT to_char(LOCALTIME,'HH:MI')
SQL;
	       $current_time = $pdo->query($curr_time_sql);
	       $current_time = $current_time->fetch();
	       $current_time = $current_time['to_char'];

	       $sql = <<<'SQL'
		      UPDATE students_tests 
		      SET test_end_time = :test_end_time
		      WHERE students_tests.student_id = :stud_id AND students_tests.test_id = :test_id
SQL;
	       $stmt = $pdo->prepare($sql);
	       $data['stud_id'] = $ids[0];
	       $data['test_id'] = $ids[1];
	       $data['test_end_time'] = $current_time;
	       try 
	       {
		       echo"<UL><p align='center'>End Time recorded successfully.</p></UL>";
		       $stmt->execute($data);
	       }
	       catch(\PDOException $e)
	       {
		   debug_message('Error Occured: '.$e);
		   echo"<UL><a href = 'proctors.php'>return</a></UL>";
	       }
	       completed($pdo,$ids[0],$ids[1]);
	     }
	     elseif (startIsValued($pdo,$ids[0],$ids[1],false) === true)  
	     { 
	      echo"<UL><p align='center'> You have already given this test an End time</p></UL>";
	     }
	     else
	     {
	      echo"<UL><p align='center'><b>Error:</b> You must first give this test a  Start time</p></UL>";
	     }
	   }

	   function editButton($pdo) // Displays what the proctor can edit and 
	   {                        //collects those inputs. Post position of this form is $_POST['update']
	      $test = selectTest($pdo);
	      $start = $test['test_start_time'];
	      $end =  $test['test_end_time'];
	      $status = $test['test_status']; 
	      $student_id = $test['student_id'];
	      $test_id = $test['test_id'];
	      $description = $test['test_description'];
	      $title_string = "Edit Test for student ". getStudName($pdo,$student_id,$test_id);

	     /* if(($start-'13:00:00') > 0)
	      {
		  $start = $start - '13:00:00';
		  $start = "$start"." PM";
	      } */
	      $html = <<<_HTML_
		<h2 align = "center">$title_string</h2>
		<form method="post">
		  <table align="center">
		    <tr><td>Start time</td><td><input type="time" name="start_time" value="$start" min='08:00' max='16:00'/>Current start time: '$start'. (input "hh:mm  AM/PM")</td></tr>
		    <tr><td>End time</td><td><input type="time" name="end_time" value="$end" min='08:00' max='16:00'/>Current end time: '$end'. (input "hh:mm AM/PM")</td></tr>
		    <tr><td>Status</td><td><select name='status'>
			<!-- <option value="Pending"</option>Pending<br />-->
			 <option value="in Progress"</option>in Progress<br />
			 <option value="Completed"</option>Completed<br />
			 <option value="Incomplete"</option>Incomplete<br /> 
		    </select></td></tr>   
		   <tr><td>Description</td><td><input type="text" name="description" value="$description" /> (optional)</td></tr>
		    <tr><td colspan="1" align="center"><button type="submit" name="update" value= "$student_id,$test_id" >Enter</button></td>
		    <td><a  href = 'proctors.php'>Cancel</a></td></tr>
		  </table>
		</form>
_HTML_;
             echo $html; 
             //var_dump($start);
	     //var_dump($end);

	   }

	   function updateTest($pdo) //applies changes to database based on information
	   {                        //from the $_POST data set in edit. 
		$ids = explode(",",filter_input(INPUT_POST, 'update' , FILTER_SANITIZE_STRING));
		$sql = <<<'SQL'
		      UPDATE students_tests 
			      SET test_start_time = :test_start_time,
				  test_end_time = :test_end_time,
				  test_status = :test_status,
				  test_description = :test_description
		      WHERE students_tests.student_id = :stud_id AND students_tests.test_id = :test_id
SQL;
	      // var_dump($_POST['description']);
	       $stmt = $pdo->prepare($sql);
	       $data['stud_id'] = $ids[0];
	       $data['test_id'] = $ids[1];
	       $data['test_start_time'] = $_POST['start_time'];
	       $data['test_end_time'] = $_POST['end_time'];
	       $data['test_status'] = $_POST['status'];
	       $data['test_description'] = $_POST['description'];

	       $did_it = true;
	       try
	       {
		 $stmt->execute($data);
	       }
	       catch(\PDOException $e)
	       {
		       debug_message("ERROR: ".$e);
		       $did_it = false;
	       }
	       if($did_it)
	       {
		       echo"<p align='center'>  edit successful</p>";
	       }
	       else
	       {
                      echo"<p align='center'>edit failed</p>";
	       }

	   }

	   function main() // Main function. 
	   {
	       $pdo = connect_to_psql('project');
	       setTimeZone($pdo);
	       //$pdo = connect_to_psql('project'); //this should not be commented out.
	       // style(); 

	       if(isset($_POST['edit']))  //Determines if user clicked the edit button
	       { 
		  editButton($pdo); 
       //           echo"<UL><a href = 'proctors.php'>cancel</a></UL>";
	       }
	       elseif (isset($_POST['update'])) //applies the changes specified in the editButton() form.
	       {		  
		          updateTest($pdo);
                  echo"<p align='center'><a href = 'proctors.php'>return to home</a></p>";
	       }
	       elseif (isset($_POST['start'])) //IF START is the button pushed.
	       {
		     startButton($pdo);      
	         echo"<UL><p align='center'><a href = 'proctors.php'>return to home</a></p></UL>";
	       }
           elseif (isset($_POST['end'])) //If END is the button pushed.
	       {
		     endButton($pdo);
		     echo"<UL><p align='center'><a href = 'proctors.php'>return to home</a></p></UL>";
	       }
          elseif (isset($_POST['change_filter'])) //If a new filter for displaying tests has been selected. 
	       {
	           newFilter($pdo);
	       }
           elseif(isset($_POST['apply_filter']) AND isset($_POST['filter_value']))//Runs a query based on the filter, and value being searched. 
	       { 
                   displaySchedule($pdo);
		           $pdo = displayTests($pdo);
	       }
	       else
	       {
		       displaySchedule($pdo);
		       $pdo = displayTests($pdo); 

	       }
	   }

	   main();
       
} else {
    // Redirect them to the login page
    echo "Please go back and log in to access page";
    header("http://104.197.235.157/html/login.php");
}
?>
  
    









