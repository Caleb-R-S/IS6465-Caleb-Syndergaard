-- Run after setup-database.sql. Rebuild students to add the account link while
-- preserving the original student IDs used by tutoring_requests.
PRAGMA foreign_keys = OFF;

CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('Student', 'Tutor', 'Administrator')),
    status TEXT NOT NULL DEFAULT 'Active' CHECK(status IN ('Active', 'Inactive')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Rename both related Lab 6 tables before recreating either one. SQLite updates
-- a child table's FK target when its parent is renamed, so rebuilding only
-- students would leave tutoring_requests pointing at students_lab6.
ALTER TABLE tutoring_requests RENAME TO tutoring_requests_lab6;
ALTER TABLE students RENAME TO students_lab6;
CREATE TABLE students (
    student_id INTEGER PRIMARY KEY,
    student_name TEXT NOT NULL,
    student_email TEXT NOT NULL UNIQUE,
    user_id INTEGER UNIQUE,
    FOREIGN KEY(user_id) REFERENCES users(user_id)
);
CREATE TABLE tutoring_requests (
    request_id INTEGER PRIMARY KEY,
    student_id INTEGER NOT NULL,
    course TEXT NOT NULL,
    topic TEXT NOT NULL,
    preferred_date TEXT NOT NULL,
    preferred_time TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'Open' CHECK(status IN ('Open','Cancelled','Assigned','Completed')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(student_id) REFERENCES students(student_id)
);
INSERT INTO students (student_id, student_name, student_email)
SELECT student_id, student_name, student_email FROM students_lab6;
INSERT INTO tutoring_requests (request_id, student_id, course, topic, preferred_date, preferred_time, status, created_at)
SELECT request_id, student_id, course, topic, preferred_date, preferred_time, status, created_at FROM tutoring_requests_lab6;
DROP TABLE tutoring_requests_lab6;
DROP TABLE students_lab6;
CREATE UNIQUE INDEX IF NOT EXISTS idx_students_user_id ON students(user_id);
PRAGMA foreign_keys = ON;
