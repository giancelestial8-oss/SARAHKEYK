<?php
require_once __DIR__ . '/includes/auth.php';
$result = mysqli_query($db, 'SELECT id, cake_name, description, price, season, cake_image FROM cakes ORDER BY date_added DESC, id DESC');
$cakes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
$pageTitle = 'SarahKeyk — Cakes made with love';
require __DIR__ . '/includes/header.php';
?>
<main>
	<section class="hero-section">
		<div class="container hero-grid">
			<div class="hero-copy">
				<span class="hero-kicker"><span>✦</span> A little joy, freshly baked</span>
				<h1>Made with love.<br><em>Shared with you.</em></h1>
				<p>Thoughtful little cakes for your big and small celebrations. Baked in small batches, with a whole lot of heart.</p>
				<a class="btn btn-rose btn-lg" href="#cakes">Find your happy cake <span>→</span></a>
				<div class="hero-note"><span class="hero-note-icon">♡</span><span>Handcrafted fresh<br><strong>for life’s sweetest moments</strong></span></div>
			</div>
			<div class="hero-art" aria-label="A selection of delicious cakes">
				<div class="hero-photo"><img src="<?= e(app_url('assets/images/hero-cake.svg')) ?>" alt="Illustration of a strawberry cake with pink frosting"></div>
				<div class="hero-sticker">Baked<br>with <span>♡</span></div>
				<span class="hero-sparkle sparkle-one">✦</span><span class="hero-sparkle sparkle-two">✧</span>
			</div>
		</div>
		<div class="hero-bottom-wave"></div>
	</section>

	<section class="collection-section" id="cakes">
		<div class="container">
			<div class="section-heading"><div class="auth-eyebrow">A SWEET LITTLE COLLECTION</div><h2>Find your <em>happy cake</em></h2><p>Each one is a little celebration, baked just for you.</p></div>
			<?php if (!$cakes): ?>
				<div class="collection-empty"><span>♡</span><h3>Something sweet is on its way</h3><p>Our cake collection is being prepared. Please check back soon.</p></div>
			<?php else: ?>
				<div class="cake-grid">
					<?php foreach ($cakes as $index => $cake): ?>
						<article class="product-card" style="--card-delay: <?= (int) ($index % 6) * 70 ?>ms">
							<div class="product-image-wrap">
								<img src="<?= e(app_url($cake['cake_image'] ?: 'assets/images/cake-placeholder.svg')) ?>" alt="<?= e($cake['cake_name']) ?>" loading="lazy">
								<span class="product-season"><?= e($cake['season']) ?></span>
							</div>
							<div class="product-info"><div class="product-title-row"><h3><?= e($cake['cake_name']) ?></h3><span class="product-price">₱<?= number_format((float) $cake['price'], 2) ?></span></div><p><?= e($cake['description']) ?></p></div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<section class="sweet-note"><div class="container"><span>♡</span><p>Made for sharing, celebrating, and all the in-between moments.</p><span>♡</span></div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
