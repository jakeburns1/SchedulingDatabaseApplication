/* 
This file contains the views used for application queries to database
*/

--DROP views if exist--
DROP VIEW IF EXISTS professor_test;
DROP VIEW IF EXISTS tests_information;

----------
--Views---
----------
CREATE VIEW tests_information
AS
SElECT DISTINCT students.student_first_name || ' ' ||students.student_last_name AS name,
       students_tests.test_date,
       students_tests.test_time AS test_time, --to_char(students_tests.test_time, 'HH:MI') || '-' || to_char(students_tests.test_schedule_end, 'HH:MI AM')test_time,
       students_tests.test_status,
       tests.test_id,
       students_tests.student_id,
       tests.test_isPaper,
       tests.course_program || ' ' ||tests.course_code || ' ' || tests.course_section AS class,
       professor_first_name || ' ' || professor_last_name As professor_name,
       professors.professor_id,
       proctors.proctor_email
 FROM students
      INNER JOIN students_tests ON(students.student_id = students_tests.student_id)
      NATURAL JOIN tests
      INNER JOIN professors ON (tests.professor_id = professors.professor_id)
      NATURAL JOIN proctors
 WHERE (tests.university = proctors.university)-- AND /*current_data*/ students_tests.test_date = '2019-11-25')
 ORDER BY students_tests.test_time,name;
 
 /* for professor_page */
 CREATE VIEW professor_test
 AS 
 SELECT student_id, test_id, course_program || ' ' || course_code || ' ' || course_section AS course,
        student_first_name || ' ' || student_last_name AS student,
        students_tests.test_date AS test_day,
        to_char(students_tests.test_time, 'HH:MI') || '-' || to_char(students_tests.test_schedule_end, 'HH:MI AM') AS time,
        university || ', ' || building AS test_location
   FROM tests
        NATURAL JOIN students_tests
        NATURAL JOIN students
ORDER BY test_day, time, course, student;

SELECT * FROM tests_information;
SELECT * FROM professor_test;
--SELECT * FROM students_tests;
