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

INSERT INTO testCenters (university, building, time_open, time_close, available_seats, available_computers)
VALUES  ('Centre  College', 'Young Hall', '08:00', '16:00', 10, 8);
