--Inserts sample data into tables.


----------------
--Insert Data
---------------
INSERT INTO students (student_first_name, student_last_name, student_email, student_phone)
VALUES ('Bob','Smith', 'bob.smith@centre.edu', '678-999-8212'),
       ('Jacob', 'Jones', 'jacob.jones@centre.edu', '404-924-2412'),
       ('Maddie', 'Allen', 'maddie.allen@centre.edu', '812-849-2494'),
       ('John', 'Brown', 'john.brown@centre.edu', '812-859-2496'),
       ('brad', 'smith', 'brad.smithn@centre.edu', '716-859-2800'),
       ('Lyra', 'Henly', 'lyra.henly@centre.edu', '101-860-2100'),
       ('Jenny', 'oldstone', 'jennt.oldsone@centre.edu', '800-810-2200'),
       ('Jake', 'schmidt', 'jake.schmidt@centre.edu', '667-860-2900'),
       ('Percy', 'Jackson', 'percy.jackson@centre.edu', '222-665-3596'),
       ('Lizzy', 'Jenkins', 'lizzy.jenkins@centre.edu', '678-300-4896');
		
INSERT INTO professors (professor_first_name, professor_last_name, professor_email, professor_phone)
VALUES ('Thomas', 'Allen', 'thomas.allen@centre.edu','432-213-4132'),
       ('Michael', 'Bradshaw', 'michael.bradshaw@centre.edu', '241-421-6313'),
       ('David', 'Toth', 'david.toth@centre.edu', '123-456-6788');

INSERT INTO courses (course_program, course_code, course_section, professor_id)
VALUES ('CSC', '221', 'a', 1),
       ('DRA', '110', 'b', 2),
       ('REL', '130', 'a', 2),
       ('MAT', '240', 'a', 3),
       ('CLA', '350', 'b', 3),
       ('PSY', '110', 'a', 1);

INSERT INTO testcenters (university, building, time_open, time_close, available_seats, available_computers)
VALUES ('Centre College', 'Young Hall', '08:00', '16:00', 10, 8);

INSERT INTO tests (professor_id, course_program, course_code, course_section, university, building, test_isPaper)
VALUES (1, 'CSC', '221', 'a', 'Centre College', 'Young Hall', TRUE),
       (1, 'PSY', '110', 'a', 'Centre College', 'Young Hall', TRUE),
       (2, 'DRA', '110', 'b', 'Centre College', 'Young Hall', TRUE),
       (2, 'REL', '130', 'a', 'Centre College', 'Young Hall', TRUE),
       (3, 'MAT', '240', 'a', 'Centre College', 'Young Hall', TRUE),
       (3, 'CLA', '350', 'b', 'Centre College', 'Young Hall', TRUE);

INSERT INTO proctors(proctor_id, university, building, proctor_first_name, proctor_last_name,   
	                     proctor_email, proctor_phone)
VALUES(1,'Centre College','Young Hall','Thomas','Allen','thomas.allen@centre.edu','432-213-4132');
VALUES(2,'Centre College','Young Hall','Michael','Bradshaw','michael.bradshaw@centre.edu','241-421-631');

INSERT INTO students_tests(student_id,test_id, test_date, test_time,  test_duration)
VALUES (1,1, '2019-11-25', '08:00', 2),
       (1,4, '2019-11-26', '08:00', 1),
       (2,1, '2019-11-25', '09:00', 1),
       (3,2, '2019-11-27', '09:00', 1),
       (4,4, '2019-11-26', '13:00', 1),
       (5,5, '2019-11-25', '13:00', 1),
       (6,6, '2019-11-25', '13:00', 1),
       (7,5, '2019-11-27', '14:00', 1),
       (8,4, '2019-11-25', '13:00', 2),
       (9,3, '2019-11-28', '13:00', 1),
       (10,2, '2019-11-29','13:00',1);

/* verification that code inserted properly*/
SELECT * FROM students;
SELECT * FROM professors;
SELECT * FROM courses;
SELECT * FROM testCenters;
SELECT * FROM tests;