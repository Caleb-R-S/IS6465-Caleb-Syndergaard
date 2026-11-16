<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$pageTitle = 'Add Gift Card';
$currentPage = 'add';

$errors = [];
$formData = [
    'card_name' => '',
    'card_type' => '',
    'card_value' => '',
    'points_required' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'card_name' => trim((string) ($_POST['card_name'] ?? $_POST['card-name'] ?? '')),
        'card_type' => trim((string) ($_POST['card_type'] ?? $_POST['card-type'] ?? '')),
        'card_value' => $_POST['card_value'] ?? $_POST['card-value'] ?? '',
        'points_required' => $_POST['points_required'] ?? $_POST['points-required'] ?? '',
    ];

    $result = validateGiftCardData($formData);
    $errors = $result['errors'];

    if ($errors === []) {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required) VALUES (:card_name, :card_type, :card_value, :points_required)');
        $stmt->execute([
            'card_name' => $result['data']['card_name'],
            'card_type' => $result['data']['card_type'],
            'card_value' => number_format((float) $result['data']['card_value'], 2, '.', ''),
            'points_required' => $result['data']['points_required'],
        ]);

        header('Location: card-list.php');
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<main class="page-shell form-shell">
    <p class="eyebrow">Catalog management</p>
    <h1>Add Gift Card</h1>
    <p class="intro">Enter the details for a new reward option.</p>

    <?php if ($errors): ?>
        <div class="error-message" role="alert">
            <strong>Please correct the following:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="editor-form" action="card-add.php" method="post">
        <div class="form-field">
            <label for="card-name">Card Name</label>
            <input id="card-name" name="card_name" type="text" value="<?= e($formData['card_name']); ?>" placeholder="e.g. Amazon Gift Card" required>
        </div>
        <div class="form-field">
            <label for="card-type">Card Type</label>
            <select id="card-type" name="card_type" required>
                <option value="" <?= $formData['card_type'] === '' ? 'selected' : ''; ?>>Select a card type</option>
                <option value="retail" <?= strtolower((string) $formData['card_type']) === 'retail' ? 'selected' : ''; ?>>Retail</option>
                <option value="dining" <?= strtolower((string) $formData['card_type']) === 'dining' ? 'selected' : ''; ?>>Dining</option>
                <option value="travel" <?= strtolower((string) $formData['card_type']) === 'travel' ? 'selected' : ''; ?>>Travel</option>
                <option value="entertainment" <?= strtolower((string) $formData['card_type']) === 'entertainment' ? 'selected' : ''; ?>>Entertainment</option>
            </select>
        </div>
        <div class="form-field">
            <label for="card-value">Card Value (USD)</label>
            <input id="card-value" name="card_value" type="number" min="0.01" step="0.01" value="<?= e((string) $formData['card_value']); ?>" placeholder="25.00" required>
        </div>
        <div class="form-field">
            <label for="points-required">Points Required</label>
            <input id="points-required" name="points_required" type="number" min="1" step="1" value="<?= e((string) $formData['points_required']); ?>" placeholder="10000" required>
        </div>
        <div class="button-row form-actions">
            <button class="button button-primary" type="submit">Add Gift Card</button>
            <a class="button button-secondary" href="card-list.php">Cancel</a>
        </div>
        <p class="form-note">Card ID is generated automatically and is not entered here.</p>
    </form>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
