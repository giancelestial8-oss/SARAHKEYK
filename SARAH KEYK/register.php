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
		$lockAcquired = false;
		try {
			// The first account is the initial administrator; later sign-ups are regular accounts.
			$lockResult = mysqli_query($db, "SELECT GET_LOCK('sarahkeyk_first_admin', 5)");
			$lockRow = mysqli_fetch_row($lockResult);
			mysqli_free_result($lockResult);
			$lockAcquired = isset($lockRow[0]) && (int) $lockRow[0] === 1;
			if (!$lockAcquired) {
				throw new RuntimeException('Could not create your account. Please try again.');
			}

			$countResult = mysqli_query($db, 'SELECT COUNT(*) FROM users');
			$countRow = mysqli_fetch_row($countResult);
			mysqli_free_result($countResult);
			$userCount = (int) $countRow[0];
			$role = $userCount === 0 ? 'admin' : 'customer';
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$statement = mysqli_prepare($db, 'INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
			mysqli_stmt_bind_param($statement, 'sssss', $fullName, $username, $email, $hashedPassword, $role);
			mysqli_stmt_execute($statement);
			mysqli_stmt_close($statement);
			mysqli_query($db, "SELECT RELEASE_LOCK('sarahkeyk_first_admin')");
			$lockAcquired = false;
			set_flash('success', $role === 'admin' ? 'Your account is ready. You are the first administrator.' : 'Your account has been created. Please log in.');
			header('Location: ' . app_url('login.php'));
			exit;
		} catch (RuntimeException $exception) {
			$errors[] = $exception->getMessage();
		} catch (mysqli_sql_exception $exception) {
			if ($lockAcquired) {
				mysqli_query($db, "SELECT RELEASE_LOCK('sarahkeyk_first_admin')");
			}
			if ($exception->getCode() === 1062) {
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
