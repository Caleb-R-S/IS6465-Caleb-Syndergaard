<?php
$currentPage = $currentPage ?? '';
?>
<header class="site-header">
    <a class="brand" href="card-list.php" aria-label="AirAsia Rewards gift cards">
        <span class="brand-mark" aria-hidden="true">airasia</span>
        <span class="brand-label">REWARDS</span>
    </a>
    <nav aria-label="Main navigation" class="header-nav">
        <a class="header-link <?php echo $currentPage === 'list' ? 'active' : ''; ?>" href="card-list.php">Gift Card List</a>
        <a class="header-link <?php echo $currentPage === 'add' ? 'active' : ''; ?>" href="card-add.php">Add Gift Card</a>
    </nav>
</header>
