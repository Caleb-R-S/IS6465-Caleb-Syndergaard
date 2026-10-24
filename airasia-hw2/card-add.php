<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Gift Card | AirAsia Rewards</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="card-list.php" aria-label="AirAsia Rewards gift cards">
            <span class="brand-mark" aria-hidden="true">airasia</span>
            <span class="brand-label">REWARDS</span>
        </a>
        <a class="header-link" href="card-list.php">← Back to gift cards</a>
    </header>

    <main class="page-shell form-shell">
        <p class="eyebrow">Catalog management</p>
        <h1>Add Gift Card</h1>
        <p class="intro">Enter the details for a new reward option.</p>

        <form class="editor-form" action="card-list.php" method="post">
            <div class="form-field">
                <label for="card-name">Card Name</label>
                <input id="card-name" name="card-name" type="text" placeholder="e.g. Amazon Gift Card" required>
            </div>
            <div class="form-field">
                <label for="card-type">Card Type</label>
                <select id="card-type" name="card-type" required>
                    <option value="" selected disabled>Select a card type</option>
                    <option value="retail">Retail</option>
                    <option value="dining">Dining</option>
                    <option value="travel">Travel</option>
                    <option value="entertainment">Entertainment</option>
                </select>
            </div>
            <div class="form-field">
                <label for="card-value">Card Value (USD)</label>
                <input id="card-value" name="card-value" type="number" min="0.01" step="0.01" placeholder="25.00" required>
            </div>
            <div class="form-field">
                <label for="points-required">Points Required</label>
                <input id="points-required" name="points-required" type="number" min="1" step="1" placeholder="10000" required>
            </div>
            <div class="button-row form-actions">
                <button class="button button-primary" type="submit">Add Gift Card</button>
                <a class="button button-secondary" href="card-list.php">Cancel</a>
            </div>
            <p class="form-note">Card ID is generated automatically and is not entered here.</p>
        </form>
    </main>

    <footer class="site-footer">AirAsia Rewards · Static gift card catalog</footer>
</body>
</html>
