<?php
session_start();

// Kung naka-login na, diretso na sa dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SarahKeyk</title>

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- DaisyUI v4 + Tailwind CSS Play CDN -->
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

            <!-- Brand Header -->
            <div class="flex flex-col items-center text-center mb-4">
                <div class="w-12 h-12 rounded-2xl bg-primary text-primary-content flex items-center justify-center shadow-md mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8" />
                        <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1" />
                        <path d="M2 21h20" />
                        <path d="M7 8v3" />
                        <path d="M12 8v3" />
                        <path d="M17 8v3" />
                        <path d="M7 4h.01" />
                        <path d="M12 4h.01" />
                        <path d="M17 4h.01" />
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight text-base-content">SarahKeyk</h2>
                <p class="text-xs text-base-content/60 mt-1">Mag-login sa iyong Admin Portal</p>
            </div>

            <!-- Error Alert -->
            <?php if (!empty($error)): ?>
                <div role="alert" class="alert alert-error rounded-xl py-3 text-sm mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div role="alert" class="alert alert-success rounded-xl py-3 text-sm mb-2">
                    <span><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <form action="actions/login-process.php" method="post" class="space-y-4">
                <div class="form-control">
                    <label for="email" class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="email" id="email" name="email" placeholder="admin@sarahkeyk.com" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="form-control">
                    <label for="password" class="label"><span class="label-text font-semibold">Password</span></label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="pt-2">
                    <button type="submit" name="login_btn" class="btn btn-primary w-full rounded-xl shadow-sm font-bold">Login</button>
                </div>
            </form>
            <p class="text-sm text-center mt-4">
                Wala pang account?
                <a href="register.php" class="link link-primary font-semibold">Register</a>
            </p>

        </div>
    </div>

</body>

</html>