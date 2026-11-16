<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validateCardId($_GET['card_id'] ?? null);
$pageTitle = 'Delete Gift Card';
$currentPage = 'delete';

require_once __DIR__ . '/includes/header.php';

if ($cardId === null) {
    echo '<main class="page-shell"><section class="editor-form"><p class="eyebrow">Gift card deletion</p><h1>Invalid Gift Card</h1><p class="intro">The selected Gift Card identifier is invalid or missing.</p><a class="button button-primary" href="card-list.php">Return to Gift Card List</a></section></main>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pdo = getDatabaseConnection();
$stmt = $pdo->prepare('SELECT card_id, card_name, card_type, card_value, points_required FROM GIFTCARD WHERE card_id = :card_id');
$stmt->execute(['card_id' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    echo '<main class="page-shell"><section class="editor-form"><p class="eyebrow">Gift card deletion</p><h1>Gift Card Not Found</h1><p class="intro">The requested Gift Card could not be found.</p><a class="button button-primary" href="card-list.php">Return to Gift Card List</a></section></main>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteId = validateCardId($_POST['card_id'] ?? null);
    if ($deleteId === null) {
        echo '<main class="page-shell"><section class="editor-form"><p class="eyebrow">Gift card deletion</p><h1>Deletion failed</h1><p class="intro">The Gift Card identifier could not be validated.</p><a class="button button-primary" href="card-list.php">Return to Gift Card List</a></section></main>';
        require_once __DIR__ . '/includes/footer.php';
        exit;
    }

    $deleteStmt = $pdo->prepare('DELETE FROM GIFTCARD WHERE card_id = :card_id');
    $deleteStmt->execute(['card_id' => $deleteId]);

    header('Location: card-list.php');
    exit;
}
?>
<main class="page-shell form-shell">
    <p class="eyebrow">Gift card deletion</p>
    <h1>Delete Gift Card</h1>
    <p class="intro">Are you sure you want to remove the following Gift Card?</p>

    <section class="editor-form">
        <dl class="detail-list">
            <div><dt>Card Name</dt><dd><?= e($card['card_name']); ?></dd></div>
            <div><dt>Card Type</dt><dd><?= e(normalizeCardType($card['card_type'])); ?></dd></div>
            <div><dt>Card Value</dt><dd>$<?= number_format((float) $card['card_value'], 2); ?></dd></div>
            <div><dt>Points Required</dt><dd><?= number_format((int) $card['points_required']); ?> points</dd></div>
        </dl>

        <form action="card-delete.php?card_id=<?= (int) $cardId; ?>" method="post">
            <input type="hidden" name="card_id" value="<?= (int) $cardId; ?>">
            <div class="button-row form-actions">
                <button class="button button-primary" type="submit">Confirm Delete</button>
                <a class="button button-secondary" href="card-details.php?card_id=<?= (int) $cardId; ?>">Cancel</a>
            </div>
        </form>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
