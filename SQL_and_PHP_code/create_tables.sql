CREATE TABLE students (
    PRIMARY KEY (student_id),
    student_id                  SERIAL,
    student_first_name          VARCHAR(50)     NOT NULL, 
    student_last_name           VARCHAR(50)     NOT NULL,
    student_email               VARCHAR(50)     NOT NULL UNIQUE,
    student_phone               CHAR(12)        NULL UNIQUE -- pattern: 000-000-0000
    CONSTRAINT valid_phone_number  
                                CHECK (student_phone SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}')
);

INSERT INTO students (student_first_name, student_last_name, student_email, student_phone)
VALUES ('Bob','Smith', 'bob.smith@centre.edu', '678-999-8212'),
       ('Jacob', 'Jones', 'jacob.jones@centre.edu', '404-924-2412'),
       ('Maddie', 'Allen', 'maddie.allen@centre.edu', '812-849-2494');

CREATE TABLE courses (
    PRIMARY KEY (course_program, course_code, course_section),
    course_program	            CHAR(3)         NOT NULL,
    course_code                 CHAR(3)         NOT NULL,
    course_section              CHAR(1)         DEFAULT NULL,
    professor_id                INT             NOT NULL      
                                REFERENCES professors (professor_id)
                                ON DELETE RESTRICT

);

INSERT INTO courses (course_program, course_code, course_section, professor_id)
VALUES ('CSC', '221', 'a', 1),
       ('DRA', '110', 'b', 2),
       ('REL', '130', 'a', 2);

CREATE TABLE professors (
    PRIMARY KEY (professor_id),
    professor_id                SERIAL,
    professor_first_name        VARCHAR(50)     NOT NULL, 
    professor_last_name         VARCHAR(50)     NOT NULL,
    professor_email             VARCHAR(50)     NOT NULL UNIQUE,
    professor_phone             CHAR(12)        NULL UNIQUE -- pattern: 000-000-0000
	CONSTRAINT valid_professor_phone_number
			                    CHECK (professor_phone SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}')
);

INSERT INTO professors (professor_first_name, professor_last_name, professor_email, professor_phone)
VALUES ('Thomas', 'Allen', 'thomas.allen@centre.edu','432-213-4132'),
       ('Michael', 'Bradshaw', 'michael.bradshaw@centre.edu', '241-421-6313'),
       ('David', 'Toth', 'david.toth@centre.edu', '123-456-6788');

CREATE TABLE testCenters (
	PRIMARY KEY (university, building),
	university			        VARCHAR(50)     NOT NULL UNIQUE,
	building			        VARCHAR(50)     NOT NULL,
	time_open                   TIME            NOT NULL,
	time_close			        TIME            NOT NULL,
	available_seats		        INT             NOT NULL,
	available_computers		    INT             NOT NULL
);

CREATE TABLE tests (
    PRIMARY KEY (test_id),
    test_id                     SERIAL,
    professor_id                INT             NOT NULL,
                                REFERENCES professors(professor_id),
    university                  VARCHAR(50)     NOT NULL,
    building                    VARCHAR(50)     NOT NULL,
--  test_date_time              DATETIME  (may use this instead of separate date and time).
    test_date                   DATETIME        NOT NULL,
    test_end_time               TIME            NOT NULL,
    test_duration               INT             NOT NULL,
    test_isPaper                BOOLEAN         NOT NULL DEFAULT TRUE
    FOREIGN KEY(university,building)      
                                REFERENCES testCenters (university, building)
);             



CREATE TABLE proctors (
    PRIMARY KEY (proctor_id, university, building),
    proctor_id                 	SERIAL,
    university		  	        VARCHAR(50)     NOT NULL UNIQUE,
    building                    VARCHAR(50)     NOT NULL,
    proctor_first_name		    VARCHAR(50)     NOT NULL, 
    proctor_last_name   	    VARCHAR(50)     NOT NULL,
    proctor_email       		VARCHAR(50)     NOT NULL UNIQUE,
    proctor_phone       		CHAR(12)        NULL UNIQUE -- pattern: 000-000-0000
	CONSTRAINT valid_proctor_phone_number
				                CHECK (proctor_phone SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}'),
    FOREIGN KEY (university, building)
	                            REFERENCES testCenters (university, building)
	                            ON DELETE RESTRICT
);

CREATE TABLE schedule (
	PRIMARY KEY (proctor_id, start_shift, end_shift),
	proctor_id			        INT             NOT NULL
					            REFERENCES proctors (proctor_id)
					            ON DELETE RESTRICT,
	start_shift			        TIME            NOT NULL,
	end_shift			        TIME            NOT NULL
);

CREATE TABLE students_courses (
    PRIMARY KEY(student_id, course_code, course_program, course_section),
    student_id                  INT             NOT NULL                     
                                REFERENCES students (student_id)
                                ON DELETE CASCADE,
    course_code                 CHAR(3)         NOT NULL
			                    REFERENCES courses (course_code)
			                    ON DELETE CASCADE,
    course_program              CHAR(3)         NOT NULL
			                    REFERENCES courses (course_program)
			                    ON DELETE CASCADE,
    course_section              CHAR(1)         DEFAULT NULL
			                    REFERENCES courses (course_section)
			                    ON DELETE CASCADE,
);

CREATE TABLE students_tests (
    PRIMARY KEY(student_id, test_id),
    student_id                  INT             NOT NULL                     
                                REFERENCES students (student_id)
                                ON DELETE CASCADE,
    test_id                     INT             NOT NULL                     
                                REFERENCES tests (tests_id)
                                ON DELETE CASCADE,
   test_isAbsent                BOOLEAN         NOT NULL DEFAULT FALSE 
);