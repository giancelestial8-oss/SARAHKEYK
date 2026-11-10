<?php
require_once __DIR__ . '/includes/auth.php';

if (isset($_SESSION['user'])) {
	header('Location: ' . app_url(is_admin() ? 'admin/dashboard.php' : 'index.php'));
	exit;
}

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$username = trim($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';
	$statement = $pdo->prepare('SELECT id, full_name, username, email, password, role FROM users WHERE username = ? LIMIT 1');
	$statement->execute([$username]);
	$user = $statement->fetch();

	if ($user && password_verify($password, $user['password'])) {
		session_regenerate_id(true);
		unset($user['password']);
		$_SESSION['user'] = $user;
		header('Location: ' . app_url($user['role'] === 'admin' ? 'admin/dashboard.php' : 'index.php'));
		exit;
	}
	$error = 'The username or password was not recognized.';
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
		<p class="auth-switch">New to SarahKeyk? <a href="<?= e(app_url('register.php')) ?>">Create an account</a></p>
	</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
