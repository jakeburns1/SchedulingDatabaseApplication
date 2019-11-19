<!DOCTYPE html>
<html>
	<head>
	    <title>Professor Page</title>
	    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 80%;
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
	    <h2 align = 'center'> Your Test Schedule </h2>
	    <table>
            <tr>
                <th>Course</th>
                <th>Test Time</th>
                <th>Test Location</th>
                <th>Operations</th>
            </tr>
            <tr>
                <td>CSC410</td>
                <td>2:20-3:50</td>
                <td>Centre College</td>
                <td><button type="button" name="edit_button">edit</button>
                <button type="button" name="delete_button">delete</button></td>
            </tr>
            <tr>
                <td>CSC160</td>
                <td>12:40-3:40</td>
                <td>Centre College</td>
                <td><button type="button" name="edit_button">edit</button>
                <button type="button" name="delete_button">delete</button></td>
            </tr>
        </table>
	  
	<?php ?>
	</body>
</html>

