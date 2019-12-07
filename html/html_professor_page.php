<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('professor_page_functions.php');

session_start();

$pdo = connect_to_psql('project', $verbose=TRUE);

echo "<form method = 'post' action = 'logout.php'> <input type = 'submit' value = 'logout'></form>";

if (isset($_POST['delete'])) {
    $ids = explode(",", $_POST['id']);
    $sql = 'DELETE FROM students_tests WHERE student_id = :student_id AND test_id=:test_id';
	$stmt = $pdo->prepare($sql);
	$data = [ 'student_id' => $ids[0],
	            'test_id' => $ids[1]];
	$stmt->execute($data);
}

if (isset($_POST['edit'])) {
    echo "<form method='post' id='edit_form'>";
    $ids = explode(",", $_POST['id']);
    echo "<input type='hidden' name='id' value=" . $ids[0] . " >";
    echo "<div>";
    echo "<label for='test_date'>Test Date: </label>";
date_default_timezone_set("America/New_York");
        $today = date('Y-m-d');
	    echo "<input type='date' name='test_date' value='$today' min='$today' /></div><br>";
    echo "<div>";
    echo "<label for='start_time'>Start Time: </label>";
    echo "<input type='time' name='start_time' min='08:00' max='16:00' /></div><br>";
   
    echo "<div>";
    echo "<label for='end_time'>End Time: </label>";
    echo "<input type='time' name='end_time' min='08:00' max='16:00' />";
    echo "<small> Work from 8:00-16:00 (12:00-13:00 is lunch hour!)</small></div><br><br>";
    
     echo "<input type='submit' id='update' name='update' value='Update' /></form>";
}

if (isset($_POST['update'])) {
$start = $_POST['start_time'];
   $end = $_POST['end_time'];
      $problem=false;
      if ($_POST['start_time'] >= '12:00' AND $_POST['start_time'] < '13:00') {
      $problem=true;
      $message = "Nah we don't work at $start";
      }
      if ($_POST['end_time'] >= '12:00' AND $_POST['end_time'] < '13:00') {
      $problem=true;
      $message = "Nah we don't work at $end";
      }
      if ($_POST['start_time'] < '12:00' AND $_POST['end_time'] >= '13:00') {
      $problem=true;
      $message = "Choose morning time or afternoon time";
      }
      if ($_POST['start_time'] >= $_POST['end_time']) {
      $problem=true;
      $message = "The end time should be after the start time";
      }


   if ($problem==true) {
         echo "<p>$message</p>";

    }
    else {
		$sql = 'UPDATE students_tests SET test_date = :test_date, 
		test_time = :test_start_time, test_schedule_end = :test_end_time
		WHERE student_id = :student_id';
		$stmt = $pdo->prepare($sql);

		$data = ['student_id' => $_POST['id'],
		   		  'test_date' => $_POST['test_date'],
		   'test_start_time'  => $_POST['start_time'],
		   'test_end_time'    => $_POST['end_time']];
	$stmt->execute($data);
	}
}

if (isset($_POST['add'])) {
   echo "<form method='post' id='add_form'>";
   
    echo "<label for='course'>Course: </label>";
	echo "<select name='course'><option selected>Choose one</option>";
	$sql = "SELECT course_program||' '||course_code||' '||course_section AS course FROM courses";
	$data = $pdo->query($sql);
    foreach ($data as $row) {
		echo "<option value='" . $row['course'] . "'>" . $row['course'] . "</option>";
	}
	echo "</select>";
	
	echo "<label for='student_name'>Student: </label>";
	echo "<select name='student_name'><option selected>Choose one</option>";
	$sql2 = "SELECT student_first_name||' '||student_last_name AS name FROM students";
	$data2 = $pdo->query($sql2);
    foreach ($data2 as $row) {
		echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
	}
	echo "</select><br><br>";

	echo "<div><label for='test_date'>Test Date: </label>";
	date_default_timezone_set("America/New_York");
	$today = date('Y-m-d');
    echo "<input type='date' name='test_date' value='$today' min='$today' /></div><br>";
    
    echo "<label for='start_time'>Start Time: </label>";
    echo "<input type='time' name='start_time' value='08:00' min='08:00' max='16:00' />";

    echo "<label for='end_time'>End Time: </label>";
    echo "<input type='time' name='end_time' value='09:00' min='08:00' max='16:00' />";
    echo "<small> Work from 8:00-16:00 (12:00-13:00 is lunch hour!)</small><br><br>";
	
	echo "<div><label for='location'>Test Location: </label>";
	echo "<select name='location'><option selected>Choose one</option>";
	$sql3 = "SELECT DISTINCT university || ', ' || building AS location FROM testCenters";
	$data3 = $pdo->query($sql3);
    foreach ($data3 as $row) {
		echo "<option value='" . $row['location'] . "'>" . $row['location'] . "</option>";
	}
    echo "</select></div><br>";
	//-------------------------Added Code for isPaper--------------------------------------
	echo "<label for='is_paper'>Paper Test? </label>";
	echo"<select name='is_paper'><option value =true>YES</option><option value =false>NO</option>";
	echo"</select><br><br>";
	//---------------------------------------------------------------------------
	echo "<input type='submit' id='confirm' name='confirm' value='Confirm' /></form>";
}

if (isset($_POST['confirm'])) { //Creates form for adding a test reservation.
   $start = $_POST['start_time'];
   $end = $_POST['end_time'];
   $problem=false;
   if ($_POST['start_time'] >= '12:00' AND $_POST['start_time'] < '13:00') {
      $problem=true;
      $message = "Nah we don't work at $start"; 
   }
   if ($_POST['end_time'] >= '12:00' AND $_POST['end_time'] < '13:00') {
      $problem=true;
      $message = "Nah we don't work at $end";
   }
   if ($_POST['start_time'] < '12:00' AND $_POST['end_time'] >= '13:00') {
      $problem=true;
      $message = "Choose morning time or afternoon time";
   }
   if ($_POST['start_time'] >= $_POST['end_time']) {
      $problem=true;
      $message = "The end time should be after the start time";
   }


   if ($problem==true) {
      echo "<p>$message</p>";

    }
   else {
	$course = explode(" ", $_POST['course']);
    $student = explode(" ", $_POST['student_name']);
    $sql_student = "SELECT student_id FROM students WHERE student_first_name = '$student[0]' AND student_last_name = '$student[1]'";
    $data_student = $pdo->query($sql_student);
    $student_id = $data_student->fetch();
    //var_dump($id);
 
	$location = explode(", ", $_POST['location']);

$sql = 'INSERT INTO tests (professor_id, course_program, course_code, course_section, university, building) VALUES (?, UPPER(?), ?, ?, ?, ?)';
  $stmt = $pdo->prepare($sql);
  
  $user = "thomas.allen@centre.edu";
  $sql_professor = "SELECT professor_id FROM professors WHERE professor_email = '$user'";
    $data_professor = $pdo->query($sql_professor);
    $professor_id = $data_professor->fetch();
   
  /*
   $stmt->bindValue(':professor_id', $num, PDO::PARAM_INT);
   $stmt->bindValue(':course_program', strtolower($course[0]), PDO::PARAM_STR);
   $stmt->bindValue(':course_code', $course[1], PDO::PARAM_STR);
   $stmt->bindValue(':course_section', $course[2], PDO::PARAM_STR);
   $stmt->bindValue(':university', $location[0], PDO::PARAM_STR);
   $stmt->bindValue(':building', $location[1], PDO::PARAM_STR);
   
   $data = ['professor_id' => $num,		                                  'course_program' => strtolower($course[0]),
   'course_code'  => $course[1],
   'course_section' => $course[2],
   'university'    => $location[0],
   'building'      => $location[1]];*/

   try{
   $stmt->execute([$professor_id['professor_id'], $course[0], $course[1], $course[2], $location[0], $location[1]]);
   }
   catch (Exception $e){
   $stmt->debugDumpParams();
   }

    $p_id = $professor_id['professor_id'];
   $sql_test = "SELECT test_id FROM tests WHERE professor_id='$p_id' AND
   course_program='$course[0]' AND course_code='$course[1]' AND
   course_section='$course[2]' AND university='$location[0]' AND
   building='$location[1]' ";
     $data_test = $pdo->query($sql_test);
         $test_id = $data_test->fetch();

    $sql2 = 'INSERT INTO students_tests(student_id, test_id, test_date, test_time, test_schedule_end) VALUES (:student_id, :test_id, :test_date, :test_time, :test_schedule_end)';
    
   $stmt2 = $pdo->prepare($sql2);
   /*try{
      $stmt2->execute([$student_id['student_id'], $test_id['test_id'], $_POST['test_date'], $_POST['start_time'], $_POST['end_time']]);
    }
   catch (Exception $e){
      $stmt2->debugDumpParams();
   }*/
   
   $stmt2->bindValue(':student_id', $student_id['student_id'], PDO::PARAM_INT);
   $stmt2->bindValue(':test_id', $test_id['test_id'], PDO::PARAM_INT);
   $stmt2->bindValue(':test_date', $_POST['test_date'], PDO::PARAM_STR);
   $stmt2->bindValue(':test_time', $_POST['start_time'], PDO::PARAM_STR);
   $stmt2->bindValue(':test_schedule_end', $_POST['end_time'], PDO::PARAM_STR);
   try{
      $stmt2->execute();
      echo "<p> ". $_POST['student_name']. " has had test scheduled for ".$_POST['course']."!</p>";
        }
   catch (Exception $e){
       /* $stmt2->debugDumpParams(); */
       echo "<p>Insertion Failed</p>";
       }
       echo"<a href = 'html_professor_page.php'>Continue</a>"; // added continue
       }
}
				   

/*  Queries the view display_tests and displays the results as a table. */
function display_tests($pdo) {
$sql = 'SELECT *
            FROM professor_test
				 NATURAL JOIN professors
		    WHERE professor_email = :user_id';
$stmt = $pdo->prepare($sql);
$data['user_id'] = $_SESSION['user_id'];
try
{
    $stmt->execute($data);
}
catch (\PDOException $e)
{
    debug_message("Error: ".$e);
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
																       
  echo "<head><Title> Professor Home Page</Title></head>";
  echo "<p>User: " . $data['user_id'] . "</p>";
  echo '<p align="center" id="title">Test Schedule</p>';
  echo "<div id='main'>";
  echo "<div id='ta'>";
  echo '<table>';
  echo '<tr><th>Course</th><th>Student</th><th>Test Date</th><th>Test Time</th><th>Test Location</th><th></th></tr>';
  foreach ($result as $row) {
	echo '<tr>';
	echo '<form method="post">';
    echo '<td>' . $row['course'] . '</td>';
	echo '<td>' . $row['student'] . '</td>';
	echo '<td>' . $row['test_day'] . '</td>';
	echo '<td>' . $row['time'] . '</td>';
	echo '<td>' . $row['test_location'] . '</td>';
	echo "<td>
	<input type='hidden' name='id' value=" . $row['student_id'] . "," . $row['test_id'] ." />
	<input type='submit' name= 'delete' value='Delete' />
	<input type='submit' name= 'edit' value='Edit' />
	</td></form></tr>";
  }
  echo "</table></div><br>";
  echo "<div id='add_button'>";
  echo "<form method='post'>";
  echo "<input type='submit' name='add' value='Add new test'></form></div></div>";  
  
  style();
}
function checkNumSeats($pdo)
	{//COUNT(DISTINCT(student_id,test_id)) AS num_seats
	            $sql = <<<'SQL'
                    SELECT test_date,
			   test_time,
			   test_schedule_end,
			   student_id,
			   test_id,
			   test_isPaper
                    FROM students_tests
                         NATURAL JOIN tests
		    WHERE (university = :university AND building = :building AND test_date = :test_date)
                    
SQL;
	     $stmt = $pdo->prepare($sql);
	     $location = explode(", ",filter_input(INPUT_POST,'location',FILTER_SANITIZE_STRING));
	     //var_dump($location);------------------------------------------------------------------------------------------
	     $data['test_date'] = $_POST['test_date'];
	     //$data['test_time'] = $_POST['start_time']; (delete this)
	     $data['university'] = $location[0];
             $data['building'] = $location[1];
	     try 
             {
	       $stmt->execute($data);
	     }
	     catch(\PDOException $e)
	     {
	       debug_message('Error Occured: '.$e);
	     }

	     $sql2 = <<<'SQL'
		     SELECT available_seats
		     FROM testCenters
		     WHERE (university = :university AND building = :building)
SQL;
	   
/*	    $seats = "available_seats";
	    $ispaper = true;
	    var_dump($_POST['is_paper']);//-------------------------print
	    if ($_POST['is_paper']==='false')
	    {
		$seats = "available_computers";
		$sql2 = <<<'SQL'
			SELECT available_computers
                        FROM testCenters
			WHERE (university = :university AND building = :building)
SQL;
		$ispaper = false;
	    }*/
	    /*var_dump($seats);//-------------------------print*/
            $stmt2 = $pdo->prepare($sql2);
            $data2['university'] = $location[0];
	    $data2['building'] = $location[1];
            try 
            {
	      $stmt2->execute($data2);
            }
	    catch(\PDOException $e)
	    {
	      debug_message('Error Occured: '.$e);
	    }

	    //set inital conditions before checks begin
	    $max_num_array = $stmt2->fetch();
	    //var_dump($max_num_array);//-------------------------print
	    $start_time = $_POST['start_time'];
	    $end_time = $_POST['end_time'];
	    $num_conflicts = 0;
	    $conflict = false;
	    $max_num_seats = $max_num_array['available_seats'];
	/* $max_num_seats = $max_num_array[$seats];*/

	     while($cur_test = $stmt->fetch() AND $num_conflicts < $max_num_seats)
	     {
		 //var_dump($cur_test);
		 //echo"<br />";
                 $conflict = false;
		 $ts = $cur_test['test_time'];
		 $te = $cur_test['test_schedule_end'];

		 /*var_dump($ispaper); //---------------------------------print
		 var_dump($cur_test['test_ispaper']."<br />");//--------print

		 if($ispaper===$cur_test['test_ispaper'])
		 {*/
			 //deteremines if a current test conflicts with desired schedule time. 
			 if ($ts ===$start_time){
			     $conflict = true;		 
			 }
			 elseif($te ===$end_time){
			      $conflict = true;
			 }
			 elseif(($start_time<$ts AND $ts<$end_time)||($start_time<$te AND $te <$end_time)){
			      $conflict = true;	 
			 }
			 elseif($start_time>$ts AND $start_time<$te){
			      $conflict = true;
			 }
			 elseif($end_time<$te  AND $end_time>$ts){
			      $conflict = true;	 
			 }

			 if($conflict){ //increments the counter. 
			    $num_conflicts++;
			 }
		// }
	    }
            //echo"---------------------------------<br />";
            //echo$num_conflicts;	   
	    //var_dump($max_num_seats);//-------------------------print

	    if($num_conflicts >= $max_num_seats)
            {
		    echo"<p><b>ERROR</b>: no available seats at chosen time.</p>";
		    return false;
	    }
	    else
	    {
	      $diff = $max_num_seats-$num_conflicts;
	      echo"<p> ".$diff."seats are available</p>";
	      return true; 
	    }
	    
	}
    display_tests($pdo);

?>
