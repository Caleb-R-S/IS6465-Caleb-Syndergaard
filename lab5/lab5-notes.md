# Lab 5 Notes

## Part 1: Create the database tables

1. 
```
@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ s
qlite3 tutoring.db .tables
students           tutoring_requests
@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db .schema
CREATE TABLE students (
  student_id INTEGER PRIMARY KEY,
  student_name TEXT NOT NULL,
  student_email TEXT NOT NULL UNIQUE
);
CREATE TABLE tutoring_requests (
  request_id INTEGER PRIMARY KEY,
  student_id INTEGER NOT NULL,
  course TEXT NOT NULL,
  topic TEXT NOT NULL,
  preferred_date TEXT NOT NULL,
  preferred_time TEXT NOT NULL,
  status TEXT NOT NULL DEFAULT 'Open',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(student_id)
);
```

2. "student_id" is the PK of the "students" table, "request_id" is the PK of the "tutoring_requests" table.

3. "student_id" in the "tutoring_requests" table is a FK. This represents a linkage between the request and the student who created it.

4. "student_email" is marked UNIQUE because we don't want to allow one student to be created multiple times. 

## Part 2: Insert students and tutoring requests

1. 
```
INSERT OR IGNORE INTO students (student_name, student_email)
VALUES
  ('Jordan Lee', 'jordan.lee@example.edu'),
  ('Avery Patel', 'avery.patel@example.edu');

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
SELECT student_id, 'IS 6465', 'Forms', '2026-11-10', '10:00'
FROM students
WHERE student_email = 'jordan.lee@example.edu';

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
SELECT student_id, 'IS 6465', 'SQL SELECT', '2026-11-12', '11:00'
FROM students
WHERE student_email = 'jordan.lee@example.edu';

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
SELECT student_id, 'IS 6465', 'SQLite CRUD', '2026-11-11', '13:00'
FROM students
WHERE student_email = 'avery.patel@example.edu';
```

2. 
```
@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db  "select * from students"
1|Maria Lopez|maria.lopez@example.com
2|Jordan Lee|jordan.lee@example.edu
3|Avery Patel|avery.patel@example.edu


@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db  "select * from tutoring_requests"
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18
13|2|IS 6465|SQL SELECT|2026-11-12|11:00|Open|2026-11-07 13:37:18
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Open|2026-11-07 13:37:18
```

3. Jordan has one row because he is stored in the students table, the request table doesn't need to store his information twice, it can use the FK to link Jordans data to the two requests.

## Part 3: Read the stored data

1. 
```
select * from students

@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db  "select * from students"
1|Maria Lopez|maria.lopez@example.com
2|Jordan Lee|jordan.lee@example.edu
3|Avery Patel|avery.patel@example.edu
```

2. 
```
select * from tutoring_requests

@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db  "select * from tutoring_requests"
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18
13|2|IS 6465|SQL SELECT|2026-11-12|11:00|Open|2026-11-07 13:37:18
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Open|2026-11-07 13:37:18
```

3. 
```
select * from tutoring_requests where status = 'Open'

@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db  "select * from tutoring_requests where status = 'Open'"
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18
13|2|IS 6465|SQL SELECT|2026-11-12|11:00|Open|2026-11-07 13:37:18
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Open|2026-11-07 13:37:18
```

4. 
```
select * from tutoring_requests where request_id = 1

@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db "select * from tutoring_requests where request_id = 1"
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31
```

5. 
```
select 
    tr.request_id, 
    s.student_name, 
    s.student_email, 
    tr.course,
    tr.topic, 
    tr.preferred_time, 
    tr.status 
from tutoring_requests tr
join students s on s.student_id = tr.student_id
order by tr.preferred_date

@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab5 (main ✗) $ sqlite3 tutoring.db "select 
    tr.request_id, 
    s.student_name, 
    s.student_email, 
    tr.course,
    tr.topic, 
    tr.preferred_time, 
    tr.status 
from tutoring_requests tr
join students s on s.student_id = tr.student_id
order by tr.preferred_date"
1|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms - updated|15:00|Cancelled
3|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
4|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
5|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
6|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
7|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
8|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
9|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
10|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
11|Maria Lopez|maria.lopez@example.com|IS 6465|PHP arrays and forms|14:00|Open
12|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|10:00|Open
14|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|13:00|Open
13|Jordan Lee|jordan.lee@example.edu|IS 6465|SQL SELECT|11:00|Open
```
### JOIN Query
1. JOIN connects the student table to the tutoring request table by using student_id.
2. The purpose of WHERE is to filter data, the purpose of JOIN is to get access to additional data from a single query (though JOIN can also end up filtering data depending on the nature of the JOIN).
3. There could be multiple requests with the same topic.

## Part 4: Update one request
1. 
```
UPDATE tutoring_requests SET topic = 'NEW TOPIC' WHERE request_id = 13;
UPDATE tutoring_requests SET status = 'Cancelled' WHERE request_id = 14;
```

2. (See 3.)
3. 
Before:
```
sqlite> SELECT * FROM tutoring_requests tr JOIN students s on s.student_id = tr.student_id
   ...> ;
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31|1|Maria Lopez|maria.lopez@example.com
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34|1|Maria Lopez|maria.lopez@example.com
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25|1|Maria Lopez|maria.lopez@example.com
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33|1|Maria Lopez|maria.lopez@example.com
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55|1|Maria Lopez|maria.lopez@example.com
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57|1|Maria Lopez|maria.lopez@example.com
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59|1|Maria Lopez|maria.lopez@example.com
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29|1|Maria Lopez|maria.lopez@example.com
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15|1|Maria Lopez|maria.lopez@example.com
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18|1|Maria Lopez|maria.lopez@example.com
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18|2|Jordan Lee|jordan.lee@example.edu
13|2|IS 6465|SQL SELECT|2026-11-12|11:00|Open|2026-11-07 13:37:18|2|Jordan Lee|jordan.lee@example.edu
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Open|2026-11-07 13:37:18|3|Avery Patel|avery.patel@example.edu
sqlite> UPDATE tutoring_requests SET topic = 'NEW TOPIC' WHERE request_id = 13
```

After
```
sqlite> SELECT * FROM tutoring_requests tr JOIN students s on s.student_id = tr.student_id
   ...> ;
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31|1|Maria Lopez|maria.lopez@example.com
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34|1|Maria Lopez|maria.lopez@example.com
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25|1|Maria Lopez|maria.lopez@example.com
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33|1|Maria Lopez|maria.lopez@example.com
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55|1|Maria Lopez|maria.lopez@example.com
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57|1|Maria Lopez|maria.lopez@example.com
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59|1|Maria Lopez|maria.lopez@example.com
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29|1|Maria Lopez|maria.lopez@example.com
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15|1|Maria Lopez|maria.lopez@example.com
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18|1|Maria Lopez|maria.lopez@example.com
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18|2|Jordan Lee|jordan.lee@example.edu
13|2|IS 6465|NEW TOPIC|2026-11-12|11:00|Open|2026-11-07 13:37:18|2|Jordan Lee|jordan.lee@example.edu
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Cancelled|2026-11-07 13:37:18|3|Avery Patel|avery.patel@example.edu
```
4. The WHERE clause is essential in an update statement because we don't want to update more requests than we intend. For example, if we intend to cancel a specific request for Avery, but the WHERE clause is incorrect, we could end up cancelling ALL of Avery's appointments.

5. Cancelled appointments still provide good information about how difficult a subject may be or how much a specific student has been looking for help.

## Part 5: Delete one test request
1. 
```
INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
SELECT student_id, 'TEST_TEST_TEST', 'TEST_TEST', '2026-11-12', '11:00'
FROM students
WHERE student_email = 'jordan.lee@example.edu';
```

2. 
```
DELETE FROM tutoring_requests WHERE request_id = 15;
```

3. 
Before:
```
sqlite> SELECT * FROM tutoring_requests 
   ...> ;
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18
13|2|IS 6465|NEW TOPIC|2026-11-12|11:00|Open|2026-11-07 13:37:18
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Cancelled|2026-11-07 13:37:18
15|2|TEST_TEST_TEST|TEST_TEST|2026-11-12|11:00|Open|2026-11-08 04:49:34
```
After:
```
sqlite> SELECT * FROM tutoring_requests;
1|1|IS 6465|PHP arrays and forms - updated|2026-10-12|15:00|Cancelled|2026-11-07 13:22:31
3|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:23:34
4|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:25
5|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:33
6|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:25:55
7|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:57
8|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:26:59
9|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:27:29
10|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:28:15
11|1|IS 6465|PHP arrays and forms|2026-11-03|14:00|Open|2026-11-07 13:37:18
12|2|IS 6465|Forms|2026-11-10|10:00|Open|2026-11-07 13:37:18
13|2|IS 6465|NEW TOPIC|2026-11-12|11:00|Open|2026-11-07 13:37:18
14|3|IS 6465|SQLite CRUD|2026-11-11|13:00|Cancelled|2026-11-07 13:37:18
sqlite>
```

4. Much like the UPDATE, we are performing changes here. We could accidentally erase ALL of the records.


## Part 6:
|---|---|---|
| Create | INSERT OR IGNORE INTO students (student_name, student_email)
VALUES
  ('Jordan Lee', 'jordan.lee@example.edu'), | Creating a new student |
| Read | SELECT * FROM tutoring_requests WHERE request_id = 2 | Get a specific tutoring request |
Update | UPDATE tutoring_requests
SET status = 'Cancelled'
WHERE request_id = 1; | Cancel a student appointment |
| Delete | DELETE FROM tutoring_requests WHERE request_id = 15; | Remove requests related to a student who has moved |

1. The database maintains a connection between the student's and their requests by using student_id as a foreign key.

2. UNIQUE

3. We could accidentally update or remove unrelated records than what you were intending.

4. The 'Paramaterized" query checks for unsafe operations. A malicious actor may try to exit your query and start their own;