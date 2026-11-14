<?php
session_start();
// Kunin ang error mula sa session kung meron man, tapos linisin agad
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <!-- Sarili mong CSS -->
    <title>Login - SarahKeyk</title>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <section class="container d-flex justify-content-center">
        <div class="card shadow-sm p-4 w-100" style="max-width: 400px;">
            <!-- Form action -->
            <form action="actions/login-process.php" method="post" class="form">
                <div class="head text-center mb-3">
                    <h2>Login</h2>
                </div>

                <!-- PHP Conditional para sa Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2" role="alert">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>

                <div class="inputs mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="inputs mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login_btn" class="btn btn-warning w-100">Login</button>
            </form>
        </div>
    </section>

    <!-- Bootstrap JS Bundle (Optional kung gagamitin mo ang mga interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>