# Test Report

## T01 – Validation test harness

- Test steps and input: Open `tests/validation-tests.php` in the browser and check the PASS/FAIL output for valid data, blank name, invalid type, zero value, and invalid points.
- Expected result: Each test case reports `PASS` based on the validation rules.
- Actual result: All five validation checks reported `PASS`.
- Status: Pass

## T02 – View Gift Card List

- Test steps and input: Load `card-list.php` in the browser.
- Expected result: The page shows a list of Gift Card entries with names, values, and points required.
- Actual result: The list rendered successfully and displayed the seeded records.
- Status: Pass

## T03 – View Gift Card Details

- Test steps and input: Request `card-details.php?card_id=1`.
- Expected result: The selected Gift Card details are shown in a read-only format.
- Actual result: The Gift Card name, type, value, and points required displayed correctly.
- Status: Pass

## T04 – Add Gift Card

- Test steps and input: Submit `card_name=Test Card Demo`, `card_type=retail`, `card_value=33.50`, and `points_required=15000` to `card-add.php`.
- Expected result: The form inserts the new record and redirects to the Gift Card list.
- Actual result: The new record appeared on `card-list.php` with a new `card_id` and was persisted in the SQLite database.
- Status: Pass

## T05 – Update Gift Card

- Test steps and input: Submit an updated form to `card-update.php?card_id=<inserted-id>` with a new card name and values.
- Expected result: The selected record updates and the details page shows the revised information.
- Actual result: The record was updated and the returned detail page displayed the new values.
- Status: Pass

## T06 – Delete Gift Card

- Test steps and input: Submit a POST delete confirmation on `card-delete.php?card_id=<inserted-id>`.
- Expected result: The Gift Card is removed and the user returns to `card-list.php`.
- Actual result: The record was deleted and no longer appeared in the list.
- Status: Pass

## T07 – Invalid or altered input handling

- Test steps and input: Submit invalid form values such as blank card name, invalid card type, zero card value, and invalid points requirement.
- Expected result: The application displays validation errors and prevents record creation or update.
- Actual result: Server-side validation returned field-specific errors and blocked invalid submissions.
- Status: Pass

## T08 – Invalid Gift Card identifier handling

- Test steps and input: Request `card-details.php?card_id=999999` and `card-delete.php?card_id=999999`.
- Expected result: The application handles the invalid identifier safely and shows a not-found or invalid message.
- Actual result: The pages returned a friendly invalid/not-found response and returned the user to the list flow.
- Status: Pass

## Notes

- No defect was identified during final verification that required a code fix.
- The project’s database was rebuilt from `database/schema.sql` and `database/seed.sql` to confirm the seeded data and CRUD behavior.
