<!DOCTYPE html>
<html>
	<head>
	    <title>Professor Page</title>
	    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        </style>
	</head>
	
	<body>
	    <h1 align = 'center'> Professor Home Page </h1> <br>
	    /*
        Description: page a user sees if they login as a professor, can see all students they have scheduled and allows them to edit / schedule additional students <br>
        Dependency: login_check.php <br>
        Team member who created the page: Iris <br>
        Team member who tested the page: Jake <br>
        */
	    <h2 align = 'center'> Your Test Schedule </h2>
	    <table>
            <tr>
                <th>Course</th>
                <th>Students</th>
                <th>Test Time</th>
                <th>Test Location</th>
                <th>Operations</th>
            </tr>
            <tr>
                <td>CSC410</td>
                <td>Bob Smith, Jacob Jones, Maddie Allen</td>
                <td>2:20-3:50</td>
                <td>Centre College</td>
                <td><button type="button" name="edit_button">edit</button>
                <button type="button" name="delete_button">delete</button></td>
            </tr>
            <tr>
                <td>CSC160</td>
                <td>Bob Smith, Jacob Jones, Maddie Allen</td>
                <td>12:40-3:40</td>
                <td>Centre College</td>
                <td><button type="button" name="edit_button">edit</button>
                <button type="button" name="delete_button">delete</button></td>
            </tr>
        </table>
	  
	<?php ?>
	</body>
</html>

