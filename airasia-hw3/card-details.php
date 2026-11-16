<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validateCardId($_GET['card_id'] ?? null);
$pageTitle = 'Gift Card Details';
$currentPage = 'details';

require_once __DIR__ . '/includes/header.php';

if ($cardId === null) {
    echo '<main class="page-shell"><section class="editor-form"><p class="eyebrow">Gift card details</p><h1>Invalid Gift Card</h1><p class="intro">The selected Gift Card identifier is invalid or missing.</p><a class="button button-primary" href="card-list.php">Return to Gift Card List</a></section></main>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pdo = getDatabaseConnection();
$stmt = $pdo->prepare('SELECT card_id, card_name, card_type, card_value, points_required FROM GIFTCARD WHERE card_id = :card_id');
$stmt->execute(['card_id' => $cardId]);
$card = $stmt->fetch();

if (!$card) {
    echo '<main class="page-shell"><section class="editor-form"><p class="eyebrow">Gift card details</p><h1>Gift Card Not Found</h1><p class="intro">The requested Gift Card could not be found.</p><a class="button button-primary" href="card-list.php">Return to Gift Card List</a></section></main>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>
<main class="page-shell">
    <p class="eyebrow">Gift card details</p>
    <h1><?= e($card['card_name']); ?></h1>
    <section class="details-card" aria-label="<?= e($card['card_name']); ?> information">
        <div class="details-image">
            <img src="images/gift-card.svg" alt="<?= e($card['card_name']); ?> gift card illustration">
        </div>
        <div class="details-content">
            <p class="details-description">Redeem your AirAsia Rewards points for this Gift Card.</p>
            <dl class="detail-list">
                <div><dt>Card Name</dt><dd><?= e($card['card_name']); ?></dd></div>
                <div><dt>Card Type</dt><dd><?= e(normalizeCardType($card['card_type'])); ?></dd></div>
                <div><dt>Card Value</dt><dd>$<?= number_format((float) $card['card_value'], 2); ?></dd></div>
                <div><dt>Points Required</dt><dd><?= number_format((int) $card['points_required']); ?> points</dd></div>
            </dl>
            <div class="button-row">
                <a class="button button-primary" href="card-update.php?card_id=<?= (int) $card['card_id']; ?>">Update Gift Card</a>
                <a class="button button-secondary" href="card-delete.php?card_id=<?= (int) $card['card_id']; ?>">Delete Gift Card</a>
                <a class="button button-secondary" href="card-list.php">Return to Gift Card List</a>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
