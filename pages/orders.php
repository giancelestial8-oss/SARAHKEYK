<?php
require_once __DIR__ . '/../config/db.php';

$orders = [];
$result = $conn->query("
    SELECT o.id, o.cake_details, o.pickup_date, o.total, o.status,
           c.name AS customer_name, c.phone AS customer_phone
    FROM orders o
    LEFT JOIN customers c ON c.id = o.customer_id
    ORDER BY o.id DESC
");
if (!$result) {
    die('Unable to load orders.');
}
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$result->free();

$customers = [];
$customer_result = $conn->query("SELECT id, name FROM customers ORDER BY name");
if (!$customer_result) {
    die('Unable to load customers for orders.');
}
while ($row = $customer_result->fetch_assoc()) {
    $customers[] = $row;
}
$customer_result->free();

$order_message = $_SESSION['order_message'] ?? '';
$order_success = $_SESSION['order_success'] ?? false;
unset($_SESSION['order_message'], $_SESSION['order_success']);

$status_counts = ['Pending' => 0, 'Processing' => 0, 'Completed' => 0, 'Cancelled' => 0];
foreach ($orders as $order) {
    if (isset($status_counts[$order['status']])) {
        $status_counts[$order['status']]++;
    }
}
$status_classes = [
    'Pending' => 'badge-info',
    'Processing' => 'badge-warning',
    'Completed' => 'badge-success',
    'Cancelled' => 'badge-error'
];
?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Orders Management</h1>
            <p class="text-sm text-base-content/60 mt-1">Pamahalaan ang mga papasok na order at delivery status ng mga keyk.</p>
        </div>
        <button onclick="document.getElementById('new_order_modal').showModal()" class="btn btn-primary rounded-xl gap-2 shadow-sm">
            Create Order
        </button>
    </div>

    <?php if ($order_message !== ''): ?>
        <div class="alert <?= $order_success ? 'alert-success' : 'alert-error' ?>" role="alert">
            <span><?= htmlspecialchars($order_message, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <?php foreach ($status_counts as $status => $count): ?>
            <div class="card bg-base-100 shadow-md p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></p>
                <h3 class="text-xl font-bold mt-1"><?= $count ?> Orders</h3>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card bg-base-100 border shadow-md p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <input type="text" placeholder="Search Order ID o Customer..." class="input input-bordered w-full sm:w-72 rounded-xl text-sm" />
            <select class="select select-bordered rounded-xl text-sm w-full sm:w-48">
                <option selected>All Status</option>
                <option>Pending</option>
                <option>Processing</option>
                <option>Completed</option>
                <option>Cancelled</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Cake Details</th>
                        <th>Pickup / Delivery Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="7" class="text-center text-base-content/60">Wala pang orders.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $status = $order['status'];
                            $badge = $status_classes[$status] ?? 'badge-ghost';
                            ?>
                            <tr>
                                <td class="font-bold">#SK-<?= str_pad((string)$order['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div class="font-semibold"><?= htmlspecialchars($order['customer_name'] ?? 'Customer removed', ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="text-xs text-base-content/60"><?= htmlspecialchars($order['customer_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td><?= htmlspecialchars($order['cake_details'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= date('M d, Y - g:i A', strtotime($order['pickup_date'])) ?></td>
                                <td class="font-semibold">₱<?= number_format((float)$order['total'], 2) ?></td>
                                <td><span class="badge <?= $badge ?> badge-sm"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="post" action="actions/order-process.php" class="flex items-center gap-1">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                                            <select name="status" class="select select-bordered select-xs" aria-label="Order status">
                                                <?php foreach (array_keys($status_counts) as $option): ?>
                                                    <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= $status === $option ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-ghost btn-xs text-success">Save</button>
                                        </form>
                                        <form method="post" action="actions/order-process.php" onsubmit="return confirm('Delete this order?');">
                                            <input type="hidden" name="action" value="delete_order">
                                            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                                            <button type="submit" class="btn btn-ghost btn-xs text-error">Delete</button>
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

<dialog id="new_order_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg">Create New Order</h3>
        <p class="text-xs text-base-content/60 mt-1">Mag-encode ng bagong order para sa customer.</p>
        <form method="post" action="actions/order-process.php">
            <input type="hidden" name="action" value="add_order">
            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Customer</span></label>
                    <select name="customer_id" class="select select-bordered rounded-xl w-full" required>
                        <option value="" disabled selected>Pumili ng customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= (int)$customer['id'] ?>"><?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($customers)): ?>
                        <span class="text-xs text-error mt-1">Magdagdag muna ng customer bago gumawa ng order.</span>
                    <?php endif; ?>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Cake Item / Flavor</span></label>
                    <input type="text" name="cake_details" placeholder="Hal. Mocha Chiffon Cake (8 inch)" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Pickup Date</span></label>
                        <input type="datetime-local" name="pickup_date" class="input input-bordered rounded-xl w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Total Amount (₱)</span></label>
                        <input type="number" name="total" min="0" step="0.01" placeholder="0.00" class="input input-bordered rounded-xl w-full" required>
                    </div>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost rounded-xl" onclick="document.getElementById('new_order_modal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-xl" <?= empty($customers) ? 'disabled' : '' ?>>Save Order</button>
            </div>
        </form>
    </div>
</dialog>
