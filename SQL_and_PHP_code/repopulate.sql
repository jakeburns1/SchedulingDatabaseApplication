/*
 * This file truncates and repopulates all the tables in the ACME database with the given sample data.
 */

/*** TRUNCATE ***/

TRUNCATE TABLE students_tests
TRUNCATE TABLE students_courses;
TRUNCATE TABLE schedule;
TRUNCATE TABLE proctors;
TRUNCATE TABLE tests;
TRUNCATE TABLE testCenters;
TRUNCATE TABLE professors;
TRUNCATE TABLE courses;
TRUNCATE TABLE students;

/*** POPULATE ***/

INSERT INTO students (student_first_name, student_last_name, student_email, student_phone)
VALUES  ('Bob','Smith', 'bob.smith@centre.edu', '678-999-8212'),
        ('Jacob', 'Jones', 'jacob.jones@centre.edu', '404-924-2412'),
        ('Maddie', 'Allen', 'maddie.allen@centre.edu', '812-849-2494');

INSERT INTO courses (course_program, course_code, course_section, professor_id)
VALUES  ('CSC', '221', 'a', 1),
        ('DRA', '110', 'b', 2),
        ('REL', '130', 'a', 2);

INSERT INTO professors (professor_first_name, professor_last_name, professor_email, professor_phone)
VALUES  ('Thomas', 'Allen', 'thomas.allen@centre.edu','432-213-4132'),
        ('Michael', 'Bradshaw', 'michael.bradshaw@centre.edu', '241-421-6313'),
        ('David', 'Toth', 'david.toth@centre.edu', '123-456-6788');

INSERT INTO testcenters (university, building, time_open, time_close, available_seats, available_computers)
VALUES  ('Centre  College', 'Young Hall', '08:00', '16:00', 10, 8);

/*** EDIT DATA ***/
/* 
UPDATE  students
    SET student_first_name = 'Bobly', student_email = bobly.smith@centre.edu
  WHERE student_id = (  SELECT student_id
                          FROM students
                         WHERE student_email = 'bob.smith@centre.edu'
                    );
  
DELETE FROM students
  WHERE student_id = (  SELECT student_id
                          FROM students
                         WHERE student_email = 'bobly.smith@centre.edu'
                    );
                    
UPDATE  courses
    SET course_section = 'b'
  WHERE course_id = (   SELECT course_id
                          FROM courses
                         WHERE course_program = 'CSC'
                           AND course_code = '221'
                           AND course_section = 'a'
                    );
  
DELETE FROM courses
  WHERE course_id = (   SELECT course_id
                          FROM courses
                         WHERE course_program = 'CSC'
                           AND course_code = '221'
                           AND course_section = 'b'
                    );
                    
UPDATE  professors
    SET professor_first_name = 'Thomly', professor_email = 'thomly.allen@centre.edu'
  WHERE professor_id = (SELECT professor_id
                          FROM professors
                         WHERE professor_email = 'thomas.allen@centre.edu'
                    );
  
DELETE FROM professors
  WHERE professor_id = (SELECT professor_id
                          FROM professors
                         WHERE professor_email = 'thomly.allen@centre.edu'
                    );
                    
UPDATE  testcenters
    SET university = 'university of kentucky'
  WHERE university = 'Centre College';
  
DELETE FROM testcenters
  WHERE university = 'university of kentucky';
*/
