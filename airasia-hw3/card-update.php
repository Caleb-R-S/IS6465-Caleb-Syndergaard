<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validateCardId($_GET['card_id'] ?? null);
if ($cardId === null) {
    header('Location: card-list.php');
    exit;
}

$pdo = getDatabaseConnection();
$stmt = $pdo->prepare('SELECT card_id, card_name, card_type, card_value, points_required FROM GIFTCARD WHERE card_id = :card_id');
$stmt->execute(['card_id' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    header('Location: card-list.php');
    exit;
}

$pageTitle = 'Update Gift Card';
$currentPage = 'update';

$formData = [
    'card_name' => $card['card_name'],
    'card_type' => strtolower($card['card_type']),
    'card_value' => number_format((float) $card['card_value'], 2, '.', ''),
    'points_required' => (string) (int) $card['points_required'],
];
$errors = [];

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
        $update = $pdo->prepare('UPDATE GIFTCARD SET card_name = :card_name, card_type = :card_type, card_value = :card_value, points_required = :points_required WHERE card_id = :card_id');
        $update->execute([
            'card_name' => $result['data']['card_name'],
            'card_type' => $result['data']['card_type'],
            'card_value' => number_format((float) $result['data']['card_value'], 2, '.', ''),
            'points_required' => $result['data']['points_required'],
            'card_id' => $cardId,
        ]);

        header('Location: card-details.php?card_id=' . $cardId);
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<main class="page-shell form-shell">
    <p class="eyebrow">Catalog management</p>
    <h1>Update Gift Card</h1>
    <p class="intro">Edit the existing <?= e($card['card_name']); ?> details.</p>

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

    <form class="editor-form" action="card-update.php?card_id=<?= (int) $cardId; ?>" method="post">
        <div class="form-field">
            <label for="card-name">Card Name</label>
            <input id="card-name" name="card_name" type="text" value="<?= e($formData['card_name']); ?>" required>
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
            <input id="card-value" name="card_value" type="number" min="0.01" step="0.01" value="<?= e((string) $formData['card_value']); ?>" required>
        </div>
        <div class="form-field">
            <label for="points-required">Points Required</label>
            <input id="points-required" name="points_required" type="number" min="1" step="1" value="<?= e((string) $formData['points_required']); ?>" required>
        </div>
        <div class="button-row form-actions">
            <button class="button button-primary" type="submit">Update Gift Card</button>
            <a class="button button-secondary" href="card-details.php?card_id=<?= (int) $cardId; ?>">Cancel</a>
        </div>
    </form>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
