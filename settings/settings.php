<?php
require_once __DIR__ . '/../config/db.php';

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
if (!$stmt) {
    die('Unable to load account settings.');
}
$stmt->bind_param("i", $_SESSION['user_id']);
if (!$stmt->execute()) {
    $stmt->close();
    die('Unable to load account settings.');
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if (!$user) {
    die('Unable to find the current account.');
}

$settings_message = $_SESSION['settings_message'] ?? '';
$settings_success = $_SESSION['settings_success'] ?? false;
unset($_SESSION['settings_message'], $_SESSION['settings_success']);
?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Account Settings</h1>
        <p class="text-sm text-base-content/60 mt-1">I-update ang email at password ng admin account.</p>
    </div>

    <?php if ($settings_message !== ''): ?>
        <div class="alert <?= $settings_success ? 'alert-success' : 'alert-error' ?>" role="alert">
            <span><?= htmlspecialchars($settings_message, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-md p-6 rounded-2xl max-w-2xl">
        <form method="post" action="actions/settings-process.php" class="space-y-4">
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Admin Email</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" class="input input-bordered rounded-xl w-full" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Current Password</span></label>
                <input type="password" name="current_password" class="input input-bordered rounded-xl w-full" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">New Password (optional)</span></label>
                <input type="password" name="new_password" minlength="8" class="input input-bordered rounded-xl w-full" autocomplete="new-password">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Confirm New Password</span></label>
                <input type="password" name="confirm_password" minlength="8" class="input input-bordered rounded-xl w-full" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary rounded-xl">Save Settings</button>
        </form>
    </div>
</div>
