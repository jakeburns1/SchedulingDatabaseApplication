<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('professor_page_functions.php');

/*  Queries the view display_ducks and displays the results as a table. */
function display_tests($pdo) {
  $sql = 'SELECT * FROM professor_test';
  $data = $pdo->query($sql);
  
  echo '<h1>Test Schedule</h1>';
  echo '<form method="post">';
  
  echo '<table>';
  echo '<tr><th>Course</th><th>Students</th><th>Test Time</th><th>Test Location</th><th></th><th></th></tr>';
  foreach ($data as $row) {
	echo '<tr>';
    echo '<td>' . $row['course'] . '</td>';
	echo '<td>' . $row['students'] . '</td>';
	echo '<td>' . $row['time'] . '</td>';
	echo '<td>' . $row['test_location'] . '</td>';
	echo '<td><input type="submit" name= "delete" value="Delete" /></td>';
	echo '<td><input type="submit" name= "edit" value="Edit" /></td>';
	echo '</tr>';
  }
  echo '</table><br>';
  echo '</form>';
  
  if(isset($_POST['delete'])) { 
	echo "This is delete that is selected"; 
  } 
  if(isset($_POST['edit'])) { 
    echo "This is edit that is selected"; 
  } 
  
  style();
}


function main() {
  
  $pdo = connect_to_psql('project', $verbose=TRUE);
  
  display_tests($pdo);

}

main();

?>
