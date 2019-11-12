DROP TABLE IF EXISTS students_tests
DROP TABLE IF EXISTS students_courses;
DROP TABLE IF EXISTS schedule;
DROP TABLE IF EXISTS proctors;
DROP TABLE IF EXISTS tests;
DROP TABLE IF EXISTS testCenters;
DROP TABLE IF EXISTS professors;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;

--creates all tables in the database. 

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

CREATE TABLE courses (
    PRIMARY KEY (course_program, course_code, course_section),
    course_program	            CHAR(3)         NOT NULL,
    course_code                 CHAR(3)         NOT NULL,
    course_section              CHAR(1)         DEFAULT NULL,
    professor_id                INT             NOT NULL      
                                REFERENCES professors (professor_id)
                                ON DELETE RESTRICT

);

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