DROP TABLE IF EXISTS GIFTCARD;
DROP TABLE IF EXISTS ACCOUNT;

CREATE TABLE ACCOUNT (
    account_id INTEGER PRIMARY KEY AUTOINCREMENT,
    account_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    member_points INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE GIFTCARD (
    card_id INTEGER PRIMARY KEY AUTOINCREMENT,
    card_name TEXT NOT NULL,
    card_type TEXT NOT NULL CHECK (card_type IN ('Retail', 'Dining', 'Travel', 'Entertainment', 'retail', 'dining', 'travel', 'entertainment')),
    card_value DECIMAL(10,2) NOT NULL CHECK (card_value > 0),
    points_required INTEGER NOT NULL CHECK (points_required > 0)
);
