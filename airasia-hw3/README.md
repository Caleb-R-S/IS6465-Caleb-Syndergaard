# AirAsia Rewards Gift Card Management

This project implements the Gift Card portion of the AirAsia Rewards web application using PHP, SQLite, and PDO. It provides CRUD pages for Gift Card records, shared page templates, server-side validation, and a validation test harness.

## Application pages

- `card-list.php` – displays all Gift Cards
- `card-details.php` – displays one selected Gift Card
- `card-add.php` – creates a new Gift Card
- `card-update.php` – edits an existing Gift Card
- `card-delete.php` – confirms and removes a Gift Card

## Database

The SQLite database is located at `database/rewards.db` and is created from `database/schema.sql` and `database/seed.sql`.

## Use Case Traceability

| Use case | PHP page or pages | Database table | Test evidence |
| --- | --- | --- | --- |
| View a List of Gift Cards | `card-list.php` | `GIFTCARD` | Loaded the page and confirmed card rows appeared with names, values, and points required. |
| View Details of One Gift Card | `card-details.php` | `GIFTCARD` | Requested `card-details.php?card_id=1` and checked that the selected Gift Card details rendered correctly. |
| Add a Gift Card | `card-add.php` | `GIFTCARD` | Submitted valid Gift Card data and confirmed the new record appeared on `card-list.php`. |
| Update a Gift Card | `card-update.php` | `GIFTCARD` | Submitted updated values and confirmed the database record and detail page reflected the revision. |
| Delete a Gift Card | `card-delete.php` | `GIFTCARD` | Confirmed the deletion flow and verified the record was no longer listed on `card-list.php`. |

## Validation

The shared validation functions live in `includes/validation.php` and are exercised by `tests/validation-tests.php`.

## Running locally

From the project directory:

```bash
php -S 127.0.0.1:8000
```

Then open `http://127.0.0.1:8000/card-list.php` in a browser.
