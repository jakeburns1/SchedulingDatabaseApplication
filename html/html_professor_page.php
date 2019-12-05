<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('professor_page_functions.php');

$pdo = connect_to_psql('project', $verbose=TRUE);

if (isset($_POST['delete'])) {
    $sql = 'DELETE FROM students_tests WHERE student_id = :student_id';
	$stmt = $pdo->prepare($sql);
	$data = [ 'student_id' => $_POST['id'] ];
	$stmt->execute($data);
}

if (isset($_POST['edit'])) {
    echo "<form method='post' id='edit_form'>";
    echo "<input type='hidden' name='id' value='" . $_POST['id'] . "'/>";
    echo "<p>Test Date:</p>";
    echo "<input type='date' name='test_date' value='2019-12-03' min='2019-12-03' />";
	
    echo "<p>Test Start Time:</p>";
    echo "<input type='time' name='start_time' value='08:00' min='08:00' max='15:30' />";

    echo "<p>Test End Time:</p>";
    echo "<input type='time' name='end_time' value='09:00' min='08:30' max='16:00'/>";
    echo "<small> Working hours are 8-12am and 1-4pm</small><br><br>";
    
     echo "<input type='submit' name='update' value='Update' /></form>";
}

if (isset($_POST['update'])) {
	if ($_POST['start_time'] < "08:00") {
		echo "<small>Select another time</small>";
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
    echo "<p>Course:</p>";
	echo "<select name='course'><option selected>Choose one</option>";
	$sql = 'SELECT course_program||' '||course_code||' '||course_section AS course FROM courses';
	$data = $pdo->query($sql);
    foreach ($data as $row) {
		echo "<option value='" . $row['course'] . "'>" . $row['course'] . "</option>";
	}
	echo "</select>";
						    
	echo "<p>Student Name:</p>";
	echo "<select name='student_name'><option selected>Choose one</option>";
	$sql2 = "SELECT student_first_name||' '||student_last_name AS name FROM students";
	$data2 = $pdo->query($sql2);
    foreach ($data2 as $row) {
		echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
	}
	echo "</select>";
	
	echo "<p>Test Date:</p>";
    echo "<input type='date' name='test_date' value='2019-12-03' min='2019-12-03' />";
	
    echo "<p>Test Start Time:</p>";
    echo "<input type='time' name='start_time' value='08:00' min='08:00' max='15:30' />";

    echo "<p>Test End Time:</p>";
    echo "<input type='time' name='end_time' value='09:00' min='08:30' max='16:00'/>";
    echo "<small> Working hours are 8-12am and 1-4pm</small>";
	
	echo "<p>Test Location:</p>";
	echo "<select name='location'><option selected>Choose one</option>";
	$sql3 = "SELECT university||' '||building AS location FROM testCenters";
	$data3 = $pdo->query($sql3);
    foreach ($data3 as $row) {
		echo "<option value='" . $row['location'] . "'>" . $row['location'] . "</option>";
	}
	echo "</select><br><br>";
	
	echo "<input type='submit' name='confirm' value='Confirm' /></form>";
}

if (isset($_POST['confirm'])) {
	$course = explode(" ", $_POST['course']);
    $sql_course = "SELECT course_id FROM courses WHERE course_program = '$pieces[0]' AND course_code = '$pieces[1]' AND course_section = '$pieces[2]'";
    $data_course = $pdo->query($sql_course);
    $course_id = $data_course->fetch();
    echo $course_id['course_id'];
   
    $student = explode(" ", $_POST['student_name']);
    $sql_student = "SELECT student_id FROM students WHERE student_first_name = '$pieces[0]' AND student_last_name = '$pieces[1]'";
    $data_student = $pdo->query($sql_student);
    $student_id = $data_student->fetch();
    //var_dump($id);
    echo $student_id['student_id'];
	
	$location = explode(" ", $_POST['location']);
    echo $location[0];
	echo $location[1];
   
}									
				     

/*  Queries the view display_tests and displays the results as a table. */
function display_tests($pdo) {
  $sql = 'SELECT * FROM professor_test';
  $data = $pdo->query($sql);
  
  echo '<h1 align="center">Test Schedule</h1>';
  
  echo '<table>';
  echo '<tr><th>Course</th><th>Student</th><th>Test Date</th><th>Test Time</th><th>Test Location</th><th></th></tr>';
  foreach ($data as $row) {
	echo '<tr>';
	echo '<form method="post">';
    echo '<td>' . $row['course'] . '</td>';
	echo '<td>' . $row['student'] . '</td>';
	echo '<td>' . $row['test_day'] . '</td>';
	echo '<td>' . $row['time'] . '</td>';
	echo '<td>' . $row['test_location'] . '</td>';
	echo "<td>
	<input type='hidden' name='id' value='" . $row["student_id"] . "'/>
	<input type='submit' name= 'delete' value='Delete' />
	<input type='submit' name= 'edit' value='Edit' />
	</td></form></tr>";
  }
  echo '</table><br>';
  echo "<form method='post'>";
  echo '<input type="submit" name="add" value="Add new test" /></form>';  
  
  style();
}
  
  display_tests($pdo);

?>
