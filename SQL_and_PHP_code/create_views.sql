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
       tests.test_date,
       tests.test_time,
       tests.test_id,
       tests.test_isPaper,
       tests.professor_id
 FROM students
      INNER JOIN students_tests ON(students.student_id = students_tests.student_id)
      NATURAL JOIN tests
      NATURAL JOIN proctors
 WHERE (tests.university = proctors.university)
 ORDER BY tests.test_time,name;   

SELECT * FROM tests_information;