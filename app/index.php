<?php
require 'config.php';
require 'includes/functions.php';

$pageTitle = 'Home';
$activePage = 'home';

/*
|--------------------------------------------------------------------------
| Ticker listings
|--------------------------------------------------------------------------
*/
$tickerStmt = $pdo->query("
    SELECT
        l.id,
        l.quantity,
        l.price,
        l.created_at,
        u.name AS seller_name,
        u.phone,
        c.name AS crop_name,
        un.name AS unit_name,
        r.name AS region_name,
        lt.name AS listing_type
    FROM listings l
    INNER JOIN users u
        ON u.id = l.user_id
    INNER JOIN crops c
        ON c.id = l.crop_id
    INNER JOIN units un
        ON un.id = l.unit_id
    INNER JOIN regions r
        ON r.id = l.region_id
    INNER JOIN listing_types lt
        ON lt.id = l.listing_type_id
    INNER JOIN listing_statuses ls
        ON ls.id = l.status_id
    WHERE ls.name = 'active'
    ORDER BY RAND()
    LIMIT 4
");

$ticker = $tickerStmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Marketplace statistics
|--------------------------------------------------------------------------
*/
$statsStmt = $pdo->query("
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN lt.name = 'sell' THEN 1 ELSE 0 END) AS selling,
        SUM(CASE WHEN lt.name = 'buy' THEN 1 ELSE 0 END) AS buying,
        COUNT(DISTINCT l.region_id) AS regions,
        COUNT(DISTINCT l.crop_id) AS crops
    FROM listings l
    INNER JOIN listing_types lt
        ON lt.id = l.listing_type_id
    INNER JOIN listing_statuses ls
        ON ls.id = l.status_id
    WHERE ls.name = 'active'
");

$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

if (!$stats) {
    $stats = [
        'total' => 0,
        'selling' => 0,
        'buying' => 0,
        'regions' => 0,
        'crops' => 0
    ];
}


/*
|--------------------------------------------------------------------------
| Recent listings
|--------------------------------------------------------------------------
*/
$recentStmt = $pdo->query("
    SELECT
        l.id,
        l.quantity,
        l.price,
        l.note,
        l.created_at,
        u.name AS seller_name,
        u.phone,
        c.name AS crop_name,
        un.name AS unit_name,
        r.name AS region_name,
        lt.name AS listing_type
    FROM listings l
    INNER JOIN users u
        ON u.id = l.user_id
    INNER JOIN crops c
        ON c.id = l.crop_id
    INNER JOIN units un
        ON un.id = l.unit_id
    INNER JOIN regions r
        ON r.id = l.region_id
    INNER JOIN listing_types lt
        ON lt.id = l.listing_type_id
    INNER JOIN listing_statuses ls
        ON ls.id = l.status_id
    WHERE ls.name = 'active'
    ORDER BY l.created_at DESC
    LIMIT 6
");

$recent = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Sokoni</h1>

        <p>
            Tanzania's agricultural marketplace connecting
            farmers, traders and buyers.
        </p>

        <div class="hero-actions">
            <a href="marketplace.php" class="btn btn-primary">
                Browse Marketplace
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="post.php" class="btn btn-secondary">
                    Post a Listing
                </a>
            <?php else: ?>
                <a href="register.php" class="btn btn-secondary">
                    Join Sokoni
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="stats">
    <div class="stat-card">
        <strong>
            <?= number_format((int)$stats['total']) ?>
        </strong>
        <span>Active Listings</span>
    </div>

    <div class="stat-card">
        <strong>
            <?= number_format((int)$stats['selling']) ?>
        </strong>
        <span>Products for Sale</span>
    </div>

    <div class="stat-card">
        <strong>
            <?= number_format((int)$stats['buying']) ?>
        </strong>
        <span>Buying Requests</span>
    </div>

    <div class="stat-card">
        <strong>
            <?= number_format((int)$stats['regions']) ?>
        </strong>
        <span>Regions</span>
    </div>

    <div class="stat-card">
        <strong>
            <?= number_format((int)$stats['crops']) ?>
        </strong>
        <span>Crops</span>
    </div>
</section>


<?php if (!empty($ticker)): ?>

<section class="ticker">
    <div class="ticker-title">
        Latest activity
    </div>

    <div class="ticker-items">
        <?php foreach ($ticker as $item): ?>

            <div class="ticker-item">

                <strong>
                    <?= htmlspecialchars($item['crop_name']) ?>
                </strong>

                <span>
                    <?= htmlspecialchars($item['quantity']) ?>
                    <?= htmlspecialchars($item['unit_name']) ?>
                </span>

                <span>
                    TZS <?= number_format((float)$item['price']) ?>
                </span>

                <span>
                    <?= htmlspecialchars($item['region_name']) ?>
                </span>

            </div>

        <?php endforeach; ?>
    </div>
</section>

<?php endif; ?>


<section class="section">

    <div class="section-header">
        <div>
            <h2>Recent Listings</h2>
            <p>Latest opportunities on Sokoni.</p>
        </div>

        <a href="marketplace.php" class="btn btn-outline">
            View All
        </a>
    </div>


    <?php if (empty($recent)): ?>

        <div class="empty-state">
            <h3>No listings yet</h3>

            <p>
                Be the first person to post a crop on Sokoni.
            </p>

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="post.php" class="btn btn-primary">
                    Post Your First Listing
                </a>

            <?php else: ?>

                <a href="register.php" class="btn btn-primary">
                    Create an Account
                </a>

            <?php endif; ?>

        </div>

    <?php else: ?>

        <div class="listing-grid">

            <?php foreach ($recent as $l): ?>

                <article class="listing-card">

                    <div class="listing-card-top">

                        <span class="listing-type">
                            <?= htmlspecialchars($l['listing_type']) ?>
                        </span>

                        <span class="listing-region">
                            <?= htmlspecialchars($l['region_name']) ?>
                        </span>

                    </div>


                    <h3>
                        <?= htmlspecialchars($l['crop_name']) ?>
                    </h3>


                    <div class="listing-quantity">

                        <?= htmlspecialchars($l['quantity']) ?>

                        <?= htmlspecialchars($l['unit_name']) ?>

                    </div>


                    <div class="listing-price">

                        TZS
                        <?= number_format((float)$l['price']) ?>

                    </div>


                    <?php if (!empty($l['note'])): ?>

                        <p class="listing-note">
                            <?= htmlspecialchars($l['note']) ?>
                        </p>

                    <?php endif; ?>


                    <div class="listing-seller">

                        <strong>
                            <?= htmlspecialchars($l['seller_name']) ?>
                        </strong>

                        <a href="tel:<?= htmlspecialchars($l['phone']) ?>">
                            <?= htmlspecialchars($l['phone']) ?>
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>


<section class="section">

    <div class="section-header">
        <div>
            <h2>How Sokoni Works</h2>
        </div>
    </div>


    <div class="features">

        <div class="feature">
            <h3>1. Create an Account</h3>
            <p>
                Register with your name, phone number and region.
            </p>
        </div>


        <div class="feature">
            <h3>2. Post a Listing</h3>
            <p>
                Tell buyers what crop you are selling or what
                you want to buy.
            </p>
        </div>


        <div class="feature">
            <h3>3. Connect Directly</h3>
            <p>
                Buyers and sellers can contact each other directly
                using the phone number provided.
            </p>
        </div>

    </div>

</section>


<?php require 'includes/footer.php'; ?>