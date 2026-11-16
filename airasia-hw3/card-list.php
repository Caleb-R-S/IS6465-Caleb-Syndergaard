<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$pageTitle = 'Gift Cards';
$currentPage = 'list';

$pdo = getDatabaseConnection();
$stmt = $pdo->query('SELECT card_id, card_name, card_type, card_value, points_required FROM GIFTCARD ORDER BY card_name ASC');
$cards = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<main class="page-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">Rewards catalog</p>
            <h1>Gift Cards</h1>
            <p class="intro">Choose a gift card and redeem it with your points.</p>
        </div>
        <a class="button button-primary" href="card-add.php">＋ Add Gift Card</a>
    </section>

    <section class="card-grid" aria-label="Available gift cards">
        <?php if ($cards): ?>
            <?php foreach ($cards as $card): ?>
                <?php $cardId = (int) $card['card_id']; ?>
                <article class="gift-card">
                    <a class="gift-card-image" href="card-details.php?card_id=<?= $cardId; ?>" aria-label="View <?= e($card['card_name']); ?> details">
                        <img src="images/gift-card.svg" alt="<?= e($card['card_name']); ?> gift card illustration">
                    </a>
                    <div class="gift-card-content">
                        <div>
                            <h2><a href="card-details.php?card_id=<?= $cardId; ?>"><?= e($card['card_name']); ?></a></h2>
                            <p class="card-meta"><?= e(normalizeCardType($card['card_type'])); ?> · $<?= number_format((float) $card['card_value'], 2); ?> value</p>
                        </div>
                        <p class="points"><strong><?= number_format((int) $card['points_required']); ?></strong><span>points required</span></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-state">No gift cards are available yet.</p>
        <?php endif; ?>
    </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
