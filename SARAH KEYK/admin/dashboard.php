<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$totalCakes = (int) $pdo->query('SELECT COUNT(*) FROM cakes')->fetchColumn();
$recentCakes = $pdo->query('SELECT id, cake_name, price, season, date_added FROM cakes ORDER BY date_added DESC, id DESC LIMIT 5')->fetchAll();
$pageTitle = 'Admin dashboard — SarahKeyk';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-wrap">
	<aside class="admin-sidebar">
		<div class="sidebar-label">WORKSPACE</div>
		<a class="active" href="<?= e(app_url('admin/dashboard.php')) ?>">◫ &nbsp; Overview</a>
		<a href="<?= e(app_url('admin/cakes.php')) ?>">♡ &nbsp; Cake management</a>
		<a href="<?= e(app_url('index.php')) ?>">↗ &nbsp; View storefront</a>
		<div class="sidebar-note">A lovely day to make something sweet.</div>
	</aside>
	<section class="admin-content">
		<div class="admin-heading"><div><div class="auth-eyebrow">YOUR LITTLE CORNER</div><h1>Good day, <?= e($_SESSION['user']['full_name']) ?> <span class="wave">✿</span></h1><p class="muted-copy">Here’s what’s happening in your cake shop.</p></div><a class="btn btn-rose" href="<?= e(app_url('admin/cakes.php')) ?>">＋ Manage cakes</a></div>
		<div class="stats-grid"><article class="stat-card"><span class="stat-icon">♡</span><div class="stat-label">TOTAL CAKES</div><div class="stat-value"><?= $totalCakes ?></div><div class="stat-foot">In your storefront collection</div></article><article class="stat-card stat-card-blush"><span class="stat-icon">✧</span><div class="stat-label">YOUR SHOP</div><div class="stat-value stat-word">Looking lovely</div><div class="stat-foot">Every cake made with care</div></article></div>
		<div class="table-card dashboard-recent"><div class="table-card-heading"><div><h2>Recently added</h2><p>Your latest creations</p></div><a href="<?= e(app_url('admin/cakes.php')) ?>">See all cakes →</a></div>
			<div class="table-responsive"><table class="table cake-table align-middle mb-0"><thead><tr><th>Cake name</th><th>Season</th><th>Price</th><th>Date added</th></tr></thead><tbody>
				<?php if (!$recentCakes): ?><tr><td colspan="4" class="empty-state"><span>♡</span><strong>Nothing in the case just yet</strong><small>Start your collection by adding a cake.</small><a class="btn btn-rose btn-sm mt-2" href="<?= e(app_url('admin/cakes.php')) ?>">Add a cake</a></td></tr><?php endif; ?>
				<?php foreach ($recentCakes as $cake): ?><tr><td><strong><?= e($cake['cake_name']) ?></strong></td><td><span class="season-pill"><?= e($cake['season']) ?></span></td><td class="price-cell">₱<?= number_format((float) $cake['price'], 2) ?></td><td class="muted-copy"><?= e(date('M j, Y', strtotime($cake['date_added']))) ?></td></tr><?php endforeach; ?>
			</tbody></table></div>
		</div>
	</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
