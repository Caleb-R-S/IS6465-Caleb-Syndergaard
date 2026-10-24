<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In | AirAsia Rewards</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-page">
    <main class="login-card">
        <a class="brand" href="login.php" aria-label="AirAsia Rewards home">
            <span class="brand-mark" aria-hidden="true">airasia</span>
            <span class="brand-label">REWARDS</span>
        </a>
        <p class="eyebrow">Welcome back</p>
        <h1>AirAsia Rewards</h1>
        <p class="intro">Sign in to manage your gift card rewards.</p>

        <form action="card-list.php" method="post">
            <div class="form-field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="Enter username" autocomplete="username" required>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter password" autocomplete="current-password" required>
            </div>
            <button class="button button-primary button-full" type="submit">Log In</button>
        </form>
        <p class="form-note">For this static demo, logging in opens the gift card list.</p>
    </main>
</body>
</html>
