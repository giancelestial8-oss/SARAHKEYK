<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SarahKeyk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="card bg-base-100 w-full max-w-md shadow-xl border border-base-300 rounded-2xl">
        <div class="card-body p-8">
            <div class="text-center mb-4">
                <h1 class="text-2xl font-extrabold tracking-tight">Create Account</h1>
                <p class="text-xs text-base-content/60 mt-1">Gumawa ng SarahKeyk admin account.</p>
            </div>

            <?php if ($error !== ''): ?>
                <div role="alert" class="alert alert-error rounded-xl py-3 text-sm mb-2">
                    <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <form action="actions/register-process.php" method="post" class="space-y-4">
                <div class="form-control">
                    <label for="email" class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="email" id="email" name="email" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="form-control">
                    <label for="password" class="label"><span class="label-text font-semibold">Password (at least 8 characters)</span></label>
                    <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="form-control">
                    <label for="confirm_password" class="label"><span class="label-text font-semibold">Confirm Password</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" class="input input-bordered rounded-xl w-full" required>
                </div>
                <button type="submit" class="btn btn-primary w-full rounded-xl shadow-sm font-bold">Register</button>
            </form>

            <p class="text-sm text-center mt-4">
                May account na?
                <a href="index.php" class="link link-primary font-semibold">Login</a>
            </p>
        </div>
    </div>
</body>

</html>
