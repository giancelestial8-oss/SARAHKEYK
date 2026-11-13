<?php
require_once __DIR__ . '/includes/auth.php';

if (is_admin()) {
	header('Location: ' . app_url('admin/dashboard.php'));
	exit;
}
unset($_SESSION['user']);

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$username = trim($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';
	$statement = mysqli_prepare($db, 'SELECT id, full_name, username, email, password, role FROM users WHERE username = ? LIMIT 1');
	mysqli_stmt_bind_param($statement, 's', $username);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	$user = mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	mysqli_stmt_close($statement);

	if ($user && password_verify($password, $user['password'])) {
		if ($user['role'] === 'admin') {
			session_regenerate_id(true);
			unset($user['password']);
			$_SESSION['user'] = $user;
			header('Location: ' . app_url('admin/dashboard.php'));
			exit;
		}
		$error = 'This account does not have permission to access the inventory.';
	} else {
		$error = 'The username or password was not recognized.';
	}
}

$flash = get_flash();
$pageTitle = 'Log in — SarahKeyk';
require __DIR__ . '/includes/header.php';
?>
<main class="auth-section">
	<div class="auth-card">
		<div class="auth-eyebrow">Welcome back</div>
		<h1>Log in</h1>
		<p class="muted-copy">Good to see you again.</p>
		<?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
		<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
		<form method="post" class="form-stack">
			<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
			<label>Username<input class="form-control" name="username" value="<?= e($username) ?>" required autocomplete="username" autofocus></label>
			<label>Password<input class="form-control" type="password" name="password" required autocomplete="current-password"></label>
			<button class="btn btn-rose w-100" type="submit">Log in</button>
		</form>
	</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>