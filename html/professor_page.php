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


/*  Queries the view display_ducks and displays the results as a table. */
function display_tests($pdo) {
  $sql = 'SELECT * FROM professor_test';
  $data = $pdo->query($sql);
  
  echo '<h1 align="center">Test Schedule</h1>';
  
  echo '<table>';
  echo '<tr><th>Course</th><th>Student</th><th>Test Date</th><th>Test Time</th><th>Test Location</th><th></th><th></th></tr>';
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
	<input type="submit" name= "delete" value="Delete" />
	<input type="submit" name= "edit" value="Edit" />
	</td></form></tr>";
	echo '</tr>';
  }
  echo '</table><br>';
  
  style();
}
  
  display_tests($pdo);

?>
