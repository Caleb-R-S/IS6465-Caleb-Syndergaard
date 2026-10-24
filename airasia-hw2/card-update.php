<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Gift Card | AirAsia Rewards</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="card-list.php" aria-label="AirAsia Rewards gift cards">
            <span class="brand-mark" aria-hidden="true">airasia</span>
            <span class="brand-label">REWARDS</span>
        </a>
        <a class="header-link" href="card-details.php">← Back to details</a>
    </header>

    <main class="page-shell form-shell">
        <p class="eyebrow">Catalog management</p>
        <h1>Update Gift Card</h1>
        <p class="intro">Edit the existing Amazon Gift Card details.</p>

        <form class="editor-form" action="card-list.php" method="post">
            <div class="form-field">
                <label for="card-name">Card Name</label>
                <input id="card-name" name="card-name" type="text" value="Amazon Gift Card" required>
            </div>
            <div class="form-field">
                <label for="card-type">Card Type</label>
                <select id="card-type" name="card-type" required>
                    <option value="retail" selected>Retail</option>
                    <option value="dining">Dining</option>
                    <option value="travel">Travel</option>
                    <option value="entertainment">Entertainment</option>
                </select>
            </div>
            <div class="form-field">
                <label for="card-value">Card Value (USD)</label>
                <input id="card-value" name="card-value" type="number" min="0.01" step="0.01" value="25.00" required>
            </div>
            <div class="form-field">
                <label for="points-required">Points Required</label>
                <input id="points-required" name="points-required" type="number" min="1" step="1" value="10000" required>
            </div>
            <div class="button-row form-actions">
                <button class="button button-primary" type="submit">Update Gift Card</button>
                <a class="button button-secondary" href="card-details.php">Cancel</a>
            </div>
        </form>
    </main>

    <footer class="site-footer">AirAsia Rewards · Static gift card catalog</footer>
</body>
</html>
