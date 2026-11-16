# Lab 6: PHP, PDO, and SQLite Integration

## Database setup

From the `lab6` project root, I ran:

```bash
sqlite3 database/tutoring.db < database/setup-database.sql
sqlite3 database/tutoring.db '.tables'
```

The tables displayed were `students` and `tutoring_requests`. The setup script also inserts one classroom sample request.

## Run the application and submit a request

Start the server from the `lab6` root:

```bash
php -S 0.0.0.0:8000
```

Open the forwarded port 8000 URL shown by Codespaces (the local equivalent is `http://localhost:8000/`). The root redirects to `/php/request-form.php`.

- **Codespaces browser URL opened:** TODO — paste the forwarded port 8000 URL.
- **Request saved and displayed:** TODO — after submitting test-only data, confirm the request detail page displays it.
- **Screenshot:** TODO — insert a screenshot of the submitted request detail page here.

## Browser workflow tests

Complete these checks in the Codespaces browser with test-only information. Replace the pending results with what you observe.

| Test | What I Did | Expected Result | Actual Result | Pass or Fail |
|---|---|---|---|---|
| Submit a valid request | TODO: submit the form using test data | A new request is created with status `Open` | Pending browser check | Not run |
| View all requests | TODO: open the request list | The new request appears in the list | Pending browser check | Not run |
| Filter requests | TODO: filter by the request's course and status | Only matching requests are shown | Pending browser check | Not run |
| Edit an Open request | TODO: change a value and save | The revised values are displayed | Pending browser check | Not run |
| Cancel an Open request | TODO: cancel the request | Its status changes to `Cancelled` | Pending browser check | Not run |
| Try to edit or cancel a cancelled request | TODO: open the cancelled request and try both actions | The application does not allow either change | Pending browser check | Not run |

The integration tests also exercise creation, filtering, editing, cancellation, and rejection of edits/cancellation after cancellation; these automated checks do not replace the required browser checks.

## Request-submission workflow

1. **Form display:** [`php/request-form.php`](./php/request-form.php).
2. **Form submission handler:** [`php/process-request.php`](./php/process-request.php). It accepts a POST from the request form, validates the form marker, calls the service, and redirects to the details page after saving.
3. **PDO connection:** [`config/db.php`](./config/db.php), in `openDatabase()`. It opens `database/tutoring.db`, configures PDO error/fetch modes, and enables SQLite foreign keys.
4. **Validation and transaction coordination:** [`services/requestService.php`](./services/requestService.php). `submitRequest()` validates the input, starts a transaction, reuses or inserts the student, inserts the request, then commits or rolls back. Field-level checks are in [`includes/validation.php`](./includes/validation.php).
5. **SQL statements:** [`repositories/studentPDO.php`](./repositories/studentPDO.php) has student lookup/insert SQL; [`repositories/requestPDO.php`](./repositories/requestPDO.php) has request insert, lookup, list/filter, edit, and cancel SQL. Table definitions, constraints, and sample seed rows are in [`database/setup-database.sql`](./database/setup-database.sql).
6. **Student table:** `students`.
7. **Tutoring request table:** `tutoring_requests`.
8. **Relationship:** `tutoring_requests.student_id` is a foreign key referencing `students.student_id`. Each request therefore links to the student who submitted it.

SQL belongs in repository files rather than the form page because repositories isolate database access from presentation and form handling. That separation lets the form focus on user input and display, while the service coordinates validation and business workflow. Repository functions can also be reused by different pages and tested without duplicating SQL throughout the application.

## Integration tests

Run from the `lab6` root:

```bash
php tests/integration-tests.php
```

Complete output from this workspace:

```text
Xdebug: [Step Debug] Could not connect to debugging client. Tried: localhost:9000 (through xdebug.client_host/xdebug.client_port) :-(
PASS UC01 create and Open
PASS Lab 4 checkbox values preserved
PASS Duplicate email reuses student
PASS UC02 course and status filter
PASS Invalid reason rejected
PASS Invalid submission writes nothing
PASS Edit Open request
PASS Updated reason persisted
PASS Cancel Open request
PASS Cancelled row retained
PASS Edit Cancelled request rejected
PASS Second cancellation rejected
PASS Unrelated request unchanged
PASS FK enforced
PASS UC02 empty filter result
All integration checks passed
```

The Xdebug connection warning is from the local PHP setup; it did not prevent the integration checks from passing. The test uses an in-memory database and clears the sample rows created by the setup script so its row-count assertions start from a clean state.

## AI Skills Pack

### Application Builder

**Prompt**

> Explain how a tutoring request moves from php/request-form.php through PHP, PDO, and SQLite in this project. Identify the responsibility of each file.

**AI response**

> `php/request-form.php` displays the form and posts its fields to `php/process-request.php`. The handler checks that the request is a valid form POST, opens the database through `config/db.php`, and calls `submitRequest()` in `services/requestService.php`. The service validates the fields (using the checks in `includes/validation.php`), starts a transaction, uses `repositories/studentPDO.php` to find or insert the student, and uses `repositories/requestPDO.php` to insert the tutoring request. On success it commits and redirects to `php/request-details.php`, which retrieves and displays the saved request.

**Evaluation:** One accurate statement is that `config/db.php` opens the SQLite database using PDO. I verified this in `openDatabase()`: it creates a `PDO('sqlite:' . $path)` connection to `database/tutoring.db`, sets PDO attributes, and enables foreign-key enforcement. The service and handler also match the flow described above.

### Traceability Auditor

**Prompt**

> Trace the requirement “a student can submit a tutoring request” through the PHP files, service, repositories, SQLite tables, and integration tests in this project.

**AI response**

> A student enters information in `php/request-form.php`; `php/process-request.php` receives the POST and calls `submitRequest()` in `services/requestService.php`. The service validates the fields, then calls student repository functions to create or locate a student and a request repository function to insert the request. The student row is stored in `students`, and the request row is stored in `tutoring_requests` with a foreign-key `student_id`. `tests/integration-tests.php` verifies that submitting a valid request creates a request with status `Open`, preserves selected topics, reuses an existing student with the same email, and rejects invalid input without writing a request.

| Requirement | Application Page | Service or Validation | Repository or SQL | Database Table | Test Evidence |
|---|---|---|---|---|---|
| Student submits a tutoring request | `php/request-form.php` → `php/process-request.php` | `services/requestService.php` (`submitRequest`, `validateSubmission`); `includes/validation.php` | `repositories/studentPDO.php` and `repositories/requestPDO.php`; `database/setup-database.sql` defines the tables | `students` and `tutoring_requests`, joined by `student_id` | `tests/integration-tests.php`: `UC01 create and Open`, duplicate email reuse, invalid reason rejection, invalid submission writes nothing, and `FK enforced` |

**Evaluation:** The trace agrees with the actual form action, function calls, repository SQL, schema foreign key, and named integration checks. The integration tests use an in-memory database initialized from the same SQL setup script, rather than the on-disk database.

### Test Harness Generator

**Prompt**

> Suggest one useful test case for the Peer Tutoring Hub application. The test must relate to request creation, filtering, editing, cancellation, validation, or database integrity.

**AI response**

> After cancelling an Open request, attempt to revise that same request and verify that the edit is rejected and its stored values remain unchanged. This protects the rule that only Open requests can be edited.

**Evaluation:** The original starter integration test checked that a second cancellation was rejected, but did not check editing after cancellation. I added an assertion to `tests/integration-tests.php` that `reviseRequest()` rejects the cancelled request; it now reports `PASS Edit Cancelled request rejected`. Manually, I would also open the cancelled request in the browser, confirm that no Edit action is available, and try the edit URL/submit path to verify the application refuses the change. This test is appropriate because both the UI and repository are intended to restrict edits to Open requests.

### Skills Pack reflection

The AI explanation helped me organize the request flow from the form through the handler, service, repositories, and database. Comparing it with the actual PHP files helped distinguish validation and transaction work in the service from SQL operations in the repositories. The traceability table connected the user requirement to the schema and concrete automated test output. Developers must verify AI-generated explanations and tests against the code and requirements because an answer can sound plausible while describing a file, behavior, or test that does not exist.

## Inspect the database

I ran the required query:

```sql
SELECT
    r.request_id,
    s.student_name,
    s.student_email,
    r.course,
    r.topic,
    r.preferred_date,
    r.preferred_time,
    r.status
FROM tutoring_requests AS r
JOIN students AS s
    ON r.student_id = s.student_id
ORDER BY r.request_id;
```

The `JOIN` combines each request row with its matching student row by comparing `r.student_id` with `s.student_id`. This lets the result show student name and email alongside course/request details, without duplicating those student fields in every tutoring-request row.