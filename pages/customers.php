<?php
require_once __DIR__ . '/../config/db.php';

$customers = [];
$result = $conn->query("
    SELECT c.id, c.name, c.phone, c.email, c.address,
           COUNT(o.id) AS total_orders, COALESCE(SUM(o.total), 0) AS total_spent
    FROM customers c
    LEFT JOIN orders o ON o.customer_id = c.id
    GROUP BY c.id, c.name, c.phone, c.email, c.address
    ORDER BY c.id DESC
");
if (!$result) {
    die('Unable to load customers.');
}
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}
$result->free();

$customer_message = $_SESSION['customer_message'] ?? '';
$customer_success = $_SESSION['customer_success'] ?? false;
unset($_SESSION['customer_message'], $_SESSION['customer_success']);
?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Customers Directory</h1>
            <p class="text-sm text-base-content/60 mt-1">Listahan ng mga kliyente at kanilang order history sa SarahKeyk.</p>
        </div>
        <button onclick="document.getElementById('add_customer_modal').showModal()" class="btn btn-primary rounded-xl gap-2 shadow-sm">
            Add Customer
        </button>
    </div>

    <?php if ($customer_message !== ''): ?>
        <div class="alert <?= $customer_success ? 'alert-success' : 'alert-error' ?>" role="alert">
            <span><?= htmlspecialchars($customer_message, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-md p-6 rounded-2xl">
        <div class="mb-6">
            <input type="text" placeholder="Maghanap ng pangalan o numero..." class="input input-bordered w-full sm:w-80 rounded-xl text-sm" />
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr><td colspan="6" class="text-center text-base-content/60">Wala pang customers.</td></tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                            <?php $initials = strtoupper(substr($customer['name'], 0, 1)); ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary/15 text-primary w-9 rounded-full font-bold text-xs">
                                                <span><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold"><?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="text-xs text-base-content/60"><?= htmlspecialchars($customer['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($customer['address'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int)$customer['total_orders'] ?> Orders</td>
                                <td class="font-semibold">₱<?= number_format((float)$customer['total_spent'], 2) ?></td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-xs text-info edit-customer"
                                            data-id="<?= (int)$customer['id'] ?>"
                                            data-name="<?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-phone="<?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-email="<?= htmlspecialchars($customer['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            data-address="<?= htmlspecialchars($customer['address'], ENT_QUOTES, 'UTF-8') ?>">
                                            Edit
                                        </button>
                                        <form method="post" action="actions/customer-process.php" onsubmit="return confirm('Remove this customer?');">
                                            <input type="hidden" name="action" value="delete_customer">
                                            <input type="hidden" name="customer_id" value="<?= (int)$customer['id'] ?>">
                                            <button type="submit" class="btn btn-ghost btn-xs text-error">Remove</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="add_customer_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg">Add New Customer</h3>
        <form method="post" action="actions/customer-process.php">
            <input type="hidden" name="action" value="add_customer">
            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Full Name</span></label>
                    <input type="text" name="name" placeholder="Juan Dela Cruz" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Contact Number</span></label>
                        <input type="text" name="phone" placeholder="09XX-XXX-XXXX" class="input input-bordered rounded-xl w-full">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Email (Optional)</span></label>
                        <input type="email" name="email" placeholder="email@example.com" class="input input-bordered rounded-xl w-full">
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Delivery Address</span></label>
                    <textarea name="address" class="textarea textarea-bordered rounded-xl w-full" placeholder="House No., Street, Barangay, Municipality"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost rounded-xl" onclick="document.getElementById('add_customer_modal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-xl">Save Customer</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="edit_customer_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg">Edit Customer</h3>
        <form method="post" action="actions/customer-process.php">
            <input type="hidden" name="action" value="update_customer">
            <input type="hidden" name="customer_id" id="edit_customer_id">
            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Full Name</span></label>
                    <input type="text" name="name" id="edit_customer_name" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Contact Number</span></label>
                        <input type="text" name="phone" id="edit_customer_phone" class="input input-bordered rounded-xl w-full">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Email (Optional)</span></label>
                        <input type="email" name="email" id="edit_customer_email" class="input input-bordered rounded-xl w-full">
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Delivery Address</span></label>
                    <textarea name="address" id="edit_customer_address" class="textarea textarea-bordered rounded-xl w-full"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost rounded-xl" onclick="document.getElementById('edit_customer_modal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-xl">Save Changes</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    document.querySelectorAll('.edit-customer').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_customer_id').value = button.dataset.id;
            document.getElementById('edit_customer_name').value = button.dataset.name;
            document.getElementById('edit_customer_phone').value = button.dataset.phone;
            document.getElementById('edit_customer_email').value = button.dataset.email;
            document.getElementById('edit_customer_address').value = button.dataset.address;
            document.getElementById('edit_customer_modal').showModal();
        });
    });
</script>
