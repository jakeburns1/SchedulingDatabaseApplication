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
    echo "<div id='edit_date'>";
    echo "<p>Test Date:</p>";
    echo "<input type='date' name='test_date' value='2019-12-03' min='2019-12-03' /></div>";

    echo "<div id='edit_start'>";
    echo "<p>Test Start Time:</p>";
    echo "<input type='text' name='start_time' value='08:00' pattern='(08|09|10|11|13|14|15):[0-5]{1}[0-9]{1}' />";
    echo "<small> Time of 24-hour</small></div>";

    echo "<div id='edit_end'>";
    echo "<p>Test End Time:</p>";
    echo "<input type='text' name='end_time' value='09:00' pattern='(08|09|10|11|13|14|15):[0-5]{1}[0-9]{1}' />";
    echo "<small> Working hours are 8-12am and 1-4pm</small></div><br><br>";
    
     echo "<input type='submit' id='update' name='update' value='Update' /></form>";
}

if (isset($_POST['update'])) {
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

if (isset($_POST['add'])) {
    echo "<form method='post' id='add_form'>";
    echo "<div id='add_course'>";
    echo "<p>Course:</p>";
	echo "<select name='course'><option selected>Choose one</option>";
	$sql = "SELECT course_program||' '||course_code||' '||course_section AS course FROM courses";
	$data = $pdo->query($sql);
    foreach ($data as $row) {
		echo "<option value='" . $row['course'] . "'>" . $row['course'] . "</option>";
	}
	echo "</select></div>";

	echo "<div id='add_student'>";
	echo "<p>Student Name:</p>";
	echo "<select name='student_name'><option selected>Choose one</option>";
	$sql2 = "SELECT student_first_name||' '||student_last_name AS name FROM students";
	$data2 = $pdo->query($sql2);
    foreach ($data2 as $row) {
		echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
	}
	echo "</select></div>";

	echo "<div id='add_date'>";
	echo "<p>Test Date:</p>";
	date_default_timezone_set("America/New_York");
	$today = date('Y-m-d');
    echo "<input type='date' name='test_date' value='$today' min='$today' /></div>";

    echo "<div id='add_start'>";
    echo "<p>Test Start Time:</p>";
    echo "<input type='text' name='start_time' value='08:00' pattern='(08|09|10|11|13|14|15):[0-5]{1}[0-9]{1}' />";
    echo "<small> Time of 24-hour</small></div>";

    echo "<p>Test End Time:</p>";
    echo "<input type='text' id='add_end' name='end_time' value='09:00' pattern='(08|09|10|11|13|14|15):[0-5]{1}[0-9]{1}' />";
    echo "<small> Working hours are 8-12am and 1-4pm</small>";
	
	echo "<p>Test Location:</p>";
	echo "<select id='add_location' name='location'><option selected>Choose one</option>";
	$sql3 = "SELECT DISTINCT university || ', ' || building AS location FROM testCenters";
	$data3 = $pdo->query($sql3);
    foreach ($data3 as $row) {
		echo "<option value='" . $row['location'] . "'>" . $row['location'] . "</option>";
	}
	echo "</select><br><br>";
	
	echo "<input type='submit' id='confirm' name='confirm' value='Confirm' /></form>";
}

if (isset($_POST['confirm'])) {
	$course = explode(" ", $_POST['course']);
   
    $student = explode(" ", $_POST['student_name']);
    $sql_student = "SELECT student_id FROM students WHERE student_first_name = '$student[0]' AND student_last_name = '$student[1]'";
    $data_student = $pdo->query($sql_student);
    $student_id = $data_student->fetch();
    //var_dump($id);
 
	$location = explode(", ", $_POST['location']);

$sql = 'INSERT INTO tests (professor_id, course_program, course_code, course_section, university, building) VALUES (?, UPPER(?), ?, ?, ?, ?)';
  $stmt = $pdo->prepare($sql);
  
  $user = $_SESSION['user_id'];
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
        }
   catch (Exception $e){
       /* $stmt2->debugDumpParams(); */
       echo "<p> ". $_POST['student_name']. " has been enrolled in ".$_POST['course']."!</p>";
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
																       
  
  echo '<br>';
  echo '<h1 align="center">Test Schedule</h1>';
  echo "<div id='main'>";
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
  echo "</table><br><div id='add_button'>";
  echo "<form method='post'>";
  echo "<input type='submit' name='add' value='Add new test'></form></div>";  
  
  style();
}
  
  display_tests($pdo);

?>
