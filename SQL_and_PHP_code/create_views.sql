/* 
This file contains the views used for application queries to database
*/

--DROP views if exist--
DROP VIEW IF EXISTS tests_information;

----------
--Views---
----------
CREATE VIEW tests_information
AS
SElECT students.student_first_name || ' ' ||students.student_last_name AS name,
       tests.test_date AS test_date,
       tests.test_time AS test_time,
       tests.test_id AS test_id,
       tests.test_isPaper AS test_isPaper,
       professor_first_name || ' ' || professor_last_name As professor_name,
       professors.professor_id AS professor_id
    /*   CASE WHEN tests.professor_id IN (SELECT professor_id, FROM professors) 
	                            THEN professor_name
            ELSE ''
       END AS prof_name*/
 FROM students
      INNER JOIN students_tests ON(students.student_id = students_tests.student_id)
      NATURAL JOIN tests
      INNER JOIN professors ON (tests.professor_id = professors.professor_id)
      NATURAL JOIN proctors
 WHERE (tests.university = proctors.university AND /*current_data*/ '2019-11-25' = tests.test_date)
 ORDER BY tests.test_time,name;   

SELECT * FROM tests_information;