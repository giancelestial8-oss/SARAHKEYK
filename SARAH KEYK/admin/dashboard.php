<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$countResult = mysqli_query($db, 'SELECT COUNT(*) FROM cakes');
$countRow = mysqli_fetch_row($countResult);
$totalCakes = (int) $countRow[0];
mysqli_free_result($countResult);

$recentResult = mysqli_query($db, 'SELECT id, cake_name, price, season, date_added FROM cakes ORDER BY date_added DESC, id DESC LIMIT 5');
$recentCakes = mysqli_fetch_all($recentResult, MYSQLI_ASSOC);
mysqli_free_result($recentResult);
$latestUpdate = $recentCakes ? date('M j, Y', strtotime($recentCakes[0]['date_added'])) : 'No items yet';
$pageTitle = 'Inventory dashboard — SarahKeyk';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-wrap">
	<aside class="admin-sidebar">
		<div class="sidebar-label">INVENTORY</div>
		<a class="active" href="<?= e(app_url('admin/dashboard.php')) ?>">◫ &nbsp; Dashboard</a>
		<a href="<?= e(app_url('admin/cakes.php')) ?>">▦ &nbsp; Inventory</a>
		<div class="sidebar-note">Manage your cake inventory in one place.</div>
	</aside>
	<section class="admin-content">
		<div class="admin-heading">
			<div>
				<div class="auth-eyebrow">INVENTORY OVERVIEW</div>
				<h1>Good day, <?= e($_SESSION['user']['full_name']) ?> <span class="wave">✿</span></h1>
				<p class="muted-copy">Review and manage the items in your inventory.</p>
			</div><a class="btn btn-rose" href="<?= e(app_url('admin/cakes.php')) ?>">＋ Manage inventory</a>
		</div>
		<div class="stats-grid">
			<article class="stat-card"><span class="stat-icon">▦</span>
				<div class="stat-label">TOTAL INVENTORY ITEMS</div>
				<div class="stat-value"><?= $totalCakes ?></div>
				<div class="stat-foot">Cake items currently tracked</div>
			</article>
			<article class="stat-card stat-card-blush"><span class="stat-icon">✧</span>
				<div class="stat-label">LATEST UPDATE</div>
				<div class="stat-value stat-word"><?= e($latestUpdate) ?></div>
				<div class="stat-foot">Date the latest inventory item was added</div>
			</article>
		</div>
		<div class="table-card dashboard-recent">
			<div class="table-card-heading">
				<div>
					<h2>Recently added inventory</h2>
					<p>Your latest items</p>
				</div><a href="<?= e(app_url('admin/cakes.php')) ?>">View inventory →</a>
			</div>
			<div class="table-responsive">
				<table class="table cake-table align-middle mb-0">
					<thead>
						<tr>
							<th>Item name</th>
							<th>Season</th>
							<th>Price</th>
							<th>Date added</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!$recentCakes): ?><tr>
								<td colspan="4" class="empty-state"><span>♡</span><strong>Your inventory is empty</strong><small>Add your first cake item to get started.</small><a class="btn btn-rose btn-sm mt-2" href="<?= e(app_url('admin/cakes.php')) ?>">Add an item</a></td>
							</tr><?php endif; ?>
						<?php foreach ($recentCakes as $cake): ?><tr>
								<td><strong><?= e($cake['cake_name']) ?></strong></td>
								<td><span class="season-pill"><?= e($cake['season']) ?></span></td>
								<td class="price-cell">₱<?= number_format((float) $cake['price'], 2) ?></td>
								<td class="muted-copy"><?= e(date('M j, Y', strtotime($cake['date_added']))) ?></td>
							</tr><?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</main>