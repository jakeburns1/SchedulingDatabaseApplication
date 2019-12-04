<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('professor_page_functions.php');

session_start();

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
    echo "<input type='text' name='test_date' />";
	
    echo "<p>Test Start Time:</p>";
echo "<input type='text' name='start_time' />";
    echo "<p>Test University:</p>";
    echo "<select name='university'><option selected>Choose one</option>";
	$sql = 'SELECT DISTINCT university FROM tests';
	$data = $pdo->query($sql);
	foreach ($data as $row) {
	echo "<option value='" . $row['university'] . "'>" . $row['university'] . "</option>";
	}
	echo "</select><br><br>";
	
	echo "<input type='submit' name='update' value='Update' /></form>";
}

if (isset($_POST['update'])) {
	$sql = 'UPDATE students_tests SET test_date = :test_date, 
	test_start_time = :test_start_time, 
	WHERE student_id = :student_id';
	$stmt = $pdo->prepare($sql);

    $data = ['student_id' => $_POST['id'],
    'test_date' => $_POST['test_date'],
	           'test_start_time'  => $_POST['start_time']];
	$stmt->execute($data);
}

if (isset($_POST['add'])) {
    echo "<form method='post' id='add_form'>";
    echo "<p>Course Program:</p>";
	echo "<select name='student_first_name'><option selected>Choose one</option>";
	$sql = 'SELECT course_program FROM courses';
	$data = $pdo->query($sql);
    foreach ($data as $row) {
		echo "<option value='" . $row['course_program'] . "'>" . $row['course_program'] . "</option>";
	}
	echo "</select>";
	
	echo "<p>Student First Name:</p>";
	echo "<select name='student_first_name'><option selected>Choose one</option>";
	$sql = 'SELECT student_first_name FROM students';
	$data = $pdo->query($sql);
    foreach ($data as $row) {
		echo "<option value='" . $row['student_id'] . "'>" . $row['student_first_name'] . "</option>";
	}
	echo "</select><br><br>";
	echo "<input type='submit' name='confirm' value='Confirm' /></form>";
}


				     


/*  Queries the view display_tests and displays the results as a table. */
function display_tests($pdo) {
    $sql = 'SELECT *
            FROM professor_test
            NATURAL JOIN students_courses
            NATURAL JOIN courses
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
  
  echo '<h1 align="center">Test Schedule</h1>';
  
  echo '<table>';
  echo '<tr><th>Course</th><th>Student</th><th>Test Date</th><th>Test Time</th><th>Test Location</th><th></th></tr>';
  while($roww = $stmt->fetch())
  {
      echo '<tr>';
  foreach ($roww as $row) {
      echo '<td>'.$row.'</td>';
	/*echo '<tr>';
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
	</td></form></tr>";*/
  }
  echo '</tr>';
  }
  echo '</table><br>';
  
  style();
}
  
  display_tests($pdo);

?>
