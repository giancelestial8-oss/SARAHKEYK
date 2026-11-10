<?php
require_once __DIR__ . '/includes/auth.php';

if (isset($_SESSION['user'])) {
	header('Location: ' . app_url(is_admin() ? 'admin/dashboard.php' : 'index.php'));
	exit;
}

$errors = [];
$fullName = $username = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$fullName = trim($_POST['full_name'] ?? '');
	$username = trim($_POST['username'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirmPassword = $_POST['confirm_password'] ?? '';

	if ($fullName === '' || mb_strlen($fullName) > 120) $errors[] = 'Enter your name (up to 120 characters).';
	if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) $errors[] = 'Username must be 3–30 characters using letters, numbers, or underscores.';
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) $errors[] = 'Enter a valid email address.';
	if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
	if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';

	if (!$errors) {
		try {
			// The first account is the initial administrator; later sign-ups are regular accounts.
			$pdo->query("SELECT GET_LOCK('sarahkeyk_first_admin', 5)");
			$userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
			$role = $userCount === 0 ? 'admin' : 'customer';
			$statement = $pdo->prepare('INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
			$statement->execute([$fullName, $username, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
			$pdo->query("SELECT RELEASE_LOCK('sarahkeyk_first_admin')");
			set_flash('success', $role === 'admin' ? 'Your account is ready. You are the first administrator.' : 'Your account has been created. Please log in.');
			header('Location: ' . app_url('login.php'));
			exit;
		} catch (PDOException $exception) {
			try { $pdo->query("SELECT RELEASE_LOCK('sarahkeyk_first_admin')"); } catch (PDOException $ignored) {}
			if ($exception->getCode() === '23000') {
				$errors[] = 'That username or email is already registered.';
			} else {
				$errors[] = 'We could not create your account. Please try again.';
			}
		}
	}
}

$pageTitle = 'Create an account — SarahKeyk';
require __DIR__ . '/includes/header.php';
?>
<main class="auth-section">
	<div class="auth-card">
		<div class="auth-eyebrow">A little sweetness starts here</div>
		<h1>Create your account</h1>
		<p class="muted-copy">Join the SarahKeyk kitchen community.</p>
		<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
		<form method="post" class="form-stack">
			<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
			<label>Full name<input class="form-control" name="full_name" maxlength="120" value="<?= e($fullName) ?>" required autocomplete="name"></label>
			<label>Username<input class="form-control" name="username" maxlength="30" value="<?= e($username) ?>" required autocomplete="username"></label>
			<label>Email<input class="form-control" type="email" name="email" maxlength="190" value="<?= e($email) ?>" required autocomplete="email"></label>
			<label>Password<input class="form-control" type="password" name="password" minlength="8" required autocomplete="new-password"></label>
			<label>Confirm password<input class="form-control" type="password" name="confirm_password" minlength="8" required autocomplete="new-password"></label>
			<button class="btn btn-rose w-100" type="submit">Create account</button>
		</form>
		<p class="auth-switch">Already have an account? <a href="<?= e(app_url('login.php')) ?>">Log in</a></p>
	</div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
