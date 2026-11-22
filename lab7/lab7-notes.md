# Lab 7: Authentication & Authorization

## Part 1: Generate the Lab 7 Application

1. 
```
Generate code for all user management use cases UM-01 through UM-10 in the User Management Design Document.

Use the Application Builder, Test Harness Generator, and Traceability Auditor documents as implementation guidance. Follow the authentication, authorization, session management, password hashing, CSRF protection, and role based access practices in the Authentication Reading Handout.

Use lab6.zip as the existing Peer Tutoring Hub application to extend. Preserve the Lab 6 three tier architecture and update the application as needed to support user registration, login, logout, profile management, administrator access, and protected tutoring request workflows.

Return a complete Lab 7 application package with all updated and new files.
```

2. 
- Peer_Tutoring_Hub_User_Management_With_Diagrams.docx
- IS6465_Authentication_Reading_Handout_v1.2_[16Nov2026].pdf
- lab6.zip
- skills\ pack.zip

3. Lab 7 extends lab 6 by adding authentication and authorization. It changes the data schema we use from just 'students' to also having 'users' to separate responsabilities.

4. I verified that the setup-database.sql file generated the code we expected it to. I Also verified the general file structure and contents.

## Part 2: Set Up the Database

1. `sqlite3 tutoring.db < setup-database.sql` and `sqlite3 tutoring.db < upgrade-to-user-management.sql`

2. All three tables appeared.

3. 
```
@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab7/database (main ✗) $ sqlite3 tutoring.db 
SQLite version 3.34.1 2021-01-20 14:10:07
Enter ".help" for usage hints.
sqlite> .tables
students           tutoring_requests  users   
```

## Part 3: Create an Administrator Account

1. php create-admin.php "admin" admin@example.com "***"

2. 
```
@Caleb-R-S ➜ /workspaces/IS6465-Caleb-Syndergaard/lab7/database (main ✗) $ php create-admin.php "admin" admin@example.com "testtesttest"
Xdebug: [Step Debug] Could not connect to debugging client. Tried: localhost:9000 (through xdebug.client_host/xdebug.client_port) :-(
Administrator account created for admin@example.com.
```

3. admin@example.com

4. Administrator

## Part 4: Run the Application

1. `https://humble-trout-9xw75xvq4ppc97x6-8000.app.github.dev/php/login.php`

2. The application launched successfully.

3. Instead of being taken to a "Home" page, we are prompted to sign in.

## Part 5: Test the Student Workflow

| Test | What I Did | Expected Result | Actual Result | Pass or Fail |
| ---- | ---------- | --------------- | ------------- | ------------ |
| Register student account | Clicked "Register" and filled out info | Student account is created | Student account is created | Pass|
| Log in as student | Entered student info and clicked 'Login' | Student can access protected student pages |Student can access protected student pages | Pass |
| View or update profile | Clicked 'My Profile' | Profile information is displayed or saved | Profile information is displayed or saved | Pass |
| Create tutoring request | Requested a new session. | A new request is created with status Open | A new request is created with status Open | Pass |
| Edit Open request | Added 'Practice Problems' | Updated request information is displayed | Updated request information is displayed | Pass
| View my requests | Clicked 'requests' | Student sees the student’s own requests | Student sees the student’s own requests | Pass |
| Log out | Clicked 'Logout' | Session ends and login page appears | Session ends and login page appears | Pass |

## Part 6: Test the Administrator Workflow

1. The login flow of asking "Are you really a user of this system" and providing the correct pages associated with you user.

2. Verifying that you can only access links that you have access to. For example, a student cannot 'Manage Users'.

3. They have access to different operations. For example, admin can update users, but students can create requests.

4. Managing users and 'All tutoring requests' (this is fine to show requests limited to student user).

5. It redirects to Login

## Part 7: Trace Registration and Login

### Student registration workflow

1. [`lab7/php/register.php`](php/register.php) displays the Create Account form.
2. The submitted form is also received by [`lab7/php/register.php`](php/register.php), in its `POST` branch.
3. [`lab7/includes/auth.php`](includes/auth.php) creates CSRF tokens with `csrfToken()` and verifies submissions with `verifyCsrfToken()`; [`lab7/php/register.php`](php/register.php) calls `verifyCsrfToken($_POST)`.
4. [`lab7/services/userService.php`](services/userService.php) validates registration input through `validateAccountInput()` and handles account creation through `registerUser()`.
5. The password is hashed in `registerUser()` in [`lab7/services/userService.php`](services/userService.php) with `password_hash()`.
6. [`lab7/repositories/userPDO.php`](repositories/userPDO.php) contains `insertUser()`, which inserts the user record. [`lab7/repositories/studentPDO.php`](repositories/studentPDO.php) contains `ensureStudentForUser()`, `insertStudentIfAbsent()`, and the student insert/update operations used to link or create student records; in the current registration implementation, student linking is performed lazily when a student submits a tutoring request rather than by `registerUser()` itself.
7. The registration handler currently creates a record in the `users` table only. It does not insert a `students` record immediately. A `students` record is inserted or linked later by `ensureStudentForUser()` from [`lab7/repositories/studentPDO.php`](repositories/studentPDO.php) when that student first submits a tutoring request.

### User login workflow

1. [`lab7/php/login.php`](php/login.php) displays the login form.
2. [`lab7/php/login.php`](php/login.php) receives the submitted credentials in its `POST` branch.
3. `findUserByEmail()` in [`lab7/repositories/userPDO.php`](repositories/userPDO.php) retrieves a user by the normalized email address.
4. The submitted password is checked with `password_verify()` in the login `POST` handler in [`lab7/php/login.php`](php/login.php).
5. After successful login, `loginSession()` in [`lab7/includes/auth.php`](includes/auth.php) regenerates the session ID and stores `user_id`, `display_name`, `role`, and `last_activity` in `$_SESSION`.
6. The session ID is regenerated after login to prevent session fixation: the new login should not continue with an attacker-known pre-authentication session identifier.
7. The application stores the user role from the `users.role` column in `$_SESSION['role']`. Pages use `requireRole()` in [`lab7/includes/auth.php`](includes/auth.php) to determine and enforce whether the user is allowed as a Student, Tutor, or Administrator.

Password hashing and verification belong in the application layer because this keeps security logic centralized, testable, and independent of HTML presentation. The application can consistently validate passwords, apply the configured hashing algorithm and rehash policy, and verify credentials through `password_hash()` and `password_verify()`. Keeping them out of database setup scripts avoids storing plaintext passwords or relying on database-specific functions, and keeping them out of presentation markup prevents presentation changes from weakening authentication behavior.

## Part 8: Run the Automated Tests

### Validation Tests
- Command:
```
php tests/validation-tests.php
```
- Complete output:
```html
Xdebug: [Step Debug] Could not connect to debugging client. Tried: localhost:9000 (through xdebug.client_host/xdebug.client_port) :-(
<p>Valid time: PASS</p><p>Missing time: PASS</p><p>Altered value: PASS</p><p>Multiple valid topics: PASS</p><p>Missing topics: PASS</p><p>Altered topic: PASS</p><p>Minimum length: PASS</p><p>Maximum length: PASS</p><p>Too short: PASS</p><p>Too long: PASS</p>
```
- Result: Passed. All 10 validation tests passed.

### User Management Tests
- Command:
```
php tests/user-management-tests.php
```
- Complete output:
```
Xdebug: [Step Debug] Could not connect to debugging client. Tried: localhost:9000 (through xdebug.client_host/xdebug.client_port) :-(
PASS: UM-01 registers an active Student
PASS: UM-01 default status is Active
PASS: UM-01 rejects duplicate email
PASS: UM-06 administrator changes a role
PASS: UM-07 administrator deactivates account
Results: 5 passed, 0 failed
```
- Result: Passed. All 5 user management tests passed.

### Integration Tests
- Command:
```
php tests/integration-tests.php
```
- Complete output:
```
Xdebug: [Step Debug] Could not connect to debugging client. Tried: localhost:9000 (through xdebug.client_host/xdebug.client_port) :-(

Fatal error: Uncaught PDOException: SQLSTATE[HY000]: General error: 1 no such column: user_id in /workspaces/IS6465-Caleb-Syndergaard/lab7/repositories/studentPDO.php:3
Stack trace:
#0 /workspaces/IS6465-Caleb-Syndergaard/lab7/repositories/studentPDO.php(3): PDO->prepare('SELECT student_...')
#1 /workspaces/IS6465-Caleb-Syndergaard/lab7/services/requestService.php(36): findStudentByEmail(Object(PDO), 'jordan@example....')
#2 /workspaces/IS6465-Caleb-Syndergaard/lab7/tests/integration-tests.php(7): submitRequest(Object(PDO), Array)
#3 {main}
  thrown in /workspaces/IS6465-Caleb-Syndergaard/lab7/repositories/studentPDO.php on line 3
```
- Result: Failed. The integration test harness threw a fatal `PDOException` before it could report the individual test results.
- Failed test: UC01 create and Open, because the first integration operation calls `submitRequest()` and this operation threw the error.
- The test is checking that a tutoring request can be created with status `Open`, that the student is reused or created correctly, and that the resulting data is inserted.
- Further investigation is needed in [`lab7/repositories/studentPDO.php`](repositories/studentPDO.php): `findStudentByEmail()` selects `user_id`, but the integration test loads only [`lab7/database/setup-database.sql`](database/setup-database.sql), which creates the original `students` table without that column. The test database setup or the upgraded student schema/repository compatibility needs review.

## Part 9: Use the AI Skills Pack Process

### Application Builder

**Prompt**

```text
Explain how a student registration request moves from register.php through authentication checks, PHP services, repositories, PDO, and SQLite in the Peer Tutoring Hub application. Identify the responsibility of each file or layer.
```

**AI response**

The browser sends the registration form with a CSRF token to `php/register.php`. The page starts a secure session, checks whether the user is already logged in, and calls `verifyCsrfToken($_POST)` for a POST submission. The presentation page then calls `registerUser(openDatabase(), $_POST)`. `openDatabase()` in `config/db.php` creates the PDO SQLite connection. `validateAccountInput()` in `services/userService.php` normalizes and validates the name, email, role, password, and confirmation. `registerUser()` checks for a duplicate email, hashes the password with `password_hash()`, and passes the hashed credentials to `insertUser()` in `repositories/userPDO.php`. The repository prepares an SQL `INSERT` and executes it through PDO against the `users` table in `database/tutoring.db`. The repository/SQL layer handles database operations, while the service layer contains validation and application rules.

**Evaluation**

One accurate statement is that `registerUser()` in `services/userService.php` hashes the password with `password_hash()` and then calls `insertUser()`. This is verified in [`lab7/services/userService.php`](services/userService.php): `registerUser()` calls `password_hash()` and `insertUser()`, and [`lab7/repositories/userPDO.php`](repositories/userPDO.php) executes the parameterized `INSERT INTO users`.

### Traceability Auditor

**Prompt**

```text
Trace the requirement “a student can register, log in, create a tutoring request, and log out” through the presentation pages, authentication code, services, repositories, SQLite tables, and automated tests in this project.
```

**Traceability table**

| Requirement | Presentation Page | Authentication or Authorization | Service or Validation | Repository or SQL | Database Table | Test Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| Student registers an account | [`lab7/php/register.php`](php/register.php) renders the form and receives its POST | [`lab7/includes/auth.php`](includes/auth.php) supplies `csrfToken()` and `verifyCsrfToken()` | `validateAccountInput()` and `registerUser()` in [`lab7/services/userService.php`](services/userService.php) validate input, duplicate email, and password; hash the password | `insertUser()` in [`lab7/repositories/userPDO.php`](repositories/userPDO.php) prepares and executes the parameterized insert | `users` | UM-01 active Student and duplicate-email checks in [`lab7/tests/user-management-tests.php`](tests/user-management-tests.php) |
| Student logs in | [`lab7/php/login.php`](php/login.php) renders the form and receives its POST | The POST handler calls `verifyCsrfToken()`; `loginSession()` in [`lab7/includes/auth.php`](includes/auth.php) regenerates the session and stores role/session data | `password_verify()` checks the submitted password; account status must be `Active` | `findUserByEmail()` in [`lab7/repositories/userPDO.php`](repositories/userPDO.php) retrieves the user through PDO | `users.password_hash`, `users.status`, and `users.role` | No dedicated login test is present in the automated files; login was verified manually in the student workflow |
| Student creates a tutoring request | [`lab7/php/request-form.php`](php/request-form.php) displays the form; [`lab7/php/process-request.php`](php/process-request.php) receives the POST | `requireLogin()` and `requireRole($user, ['Student'])` enforce authentication and Student access; CSRF is verified | `submitRequestForUser()` and request validation in [`lab7/services/requestService.php`](services/requestService.php) validate the data and link the user to a student | `ensureStudentForUser()` in [`lab7/repositories/studentPDO.php`](repositories/studentPDO.php) and `insertTutoringRequest()` in [`lab7/repositories/requestPDO.php`](repositories/requestPDO.php) perform PDO inserts | `students` and `tutoring_requests` | `UC01 create and Open` in [`lab7/tests/integration-tests.php`](tests/integration-tests.php), although the current run failed before reporting results because of the `user_id` schema mismatch |
| User logs out | The navigation in [`lab7/includes/nav.php`](includes/nav.php) posts to [`lab7/php/logout.php`](php/logout.php) | `verifyCsrfToken()` is checked, then `logoutSession()` clears session data and destroys the session | Logout has no additional service/validation logic | No repository or SQL operation; no database row is changed | No table is changed | Logout was verified manually in the student workflow; no automated logout test is present |

### Test Harness Generator

**Prompt**

```text
Suggest one useful test case for authentication or authorization in the Peer Tutoring Hub application. The test must relate to registration, login, logout, session protection, role based access, password validation, CSRF protection, or account status.
```

**AI-generated test case**

Test that an account with `Inactive` status cannot log in. Start with an active account, set its status to `Inactive`, submit valid email and password credentials through `login.php`, and confirm that login is rejected and no authenticated session variables are created.

This test is not directly covered by the provided test files. `UM-07` only verifies that an administrator can change a user's status to `Inactive`; it does not verify that login rejects that status. I would test it manually by logging in as an administrator, deactivating a Student, logging in with that student's credentials, and confirming that the login page reports invalid credentials. Alternatively, I would add an automated test that changes a user's status to `Inactive`, calls the login logic or HTTP handler, and verifies that `password_verify()` is not enough to grant access and that `$_SESSION['user_id']` remains absent. The test is appropriate because `login.php` explicitly requires `status === 'Active'`, so it directly checks an implemented account-status requirement.

### Skills Pack Reflection

The AI Skills Pack helped organize the registration trace, connect presentation and authentication files to services and repositories, and identify useful authentication test gaps. It also made it easier to compare requirements with the actual pages and test evidence. However, developers must verify AI explanations, code, and tests against source code because AI can describe intended behavior without seeing all schema or implementation differences. Actual queries, database contents, and test output provide stronger evidence than the AI's claims alone.

## Part 10: Inspect the Database

The following query was run from the `lab7` project root against `database/tutoring.db`:

```sql
SELECT
    u.user_id,
    u.full_name,
    u.email,
    u.role,
    u.status,
    s.student_id,
    s.student_name,
    s.student_email
FROM users AS u
LEFT JOIN students AS s
    ON u.user_id = s.user_id
ORDER BY u.user_id;
```

The query returned:

```text
1|admin|admin@example.com|Administrator|Active|||
2|student1|student1@example.com|Student|Active|2|student1|student1@example.com
```

1. The `LEFT JOIN` starts with every row in `users` and adds matching student columns when `students.user_id` equals `users.user_id`. A user with no matching student row remains in the result with `NULL` student fields.

2. `users.user_id` connects to `students.user_id`. `students.user_id` is unique and is a foreign key referencing `users.user_id`; this creates an optional one-to-one association between an account and a student profile.

3. Account information such as credentials, role, and status belongs in `users`, while tutoring/student profile information such as the student record and its identifier belongs in `students`. Separating them supports role-based authentication and allows the application to preserve the distinct identities and relationships used by tutoring requests. The student record can also be linked to a user later, rather than forcing every account to create one.

4. An administrator account may not have a matching student row because the `Administrator` role does not represent the Student role or require a student profile. The observed `admin` row has empty student fields, while the Student account has a matching row.

