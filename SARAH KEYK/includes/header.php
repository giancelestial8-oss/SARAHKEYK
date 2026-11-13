<?php
$pageTitle = $pageTitle ?? 'SarahKeyk Inventory';
$currentUser = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= e($pageTitle) ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= e(app_url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg site-nav">
	<div class="container">
		<a class="navbar-brand brand-mark" href="<?= e(app_url(is_admin() ? 'admin/dashboard.php' : 'login.php')) ?>"><span class="brand-icon">S</span> SarahKeyk Inventory</a>
		<?php if ($currentUser && is_admin()): ?>
			<div class="d-flex align-items-center gap-3">
				<span class="nav-welcome">Hello, <?= e($currentUser['full_name']) ?></span>
				<form method="post" action="<?= e(app_url('logout.php')) ?>" class="m-0">
					<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
					<button class="btn btn-outline-rose btn-sm" type="submit">Log out</button>
				</form>
			</div>
		<?php endif; ?>
	</div>
</nav>
