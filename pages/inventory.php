<?php
require_once __DIR__ . '/../config/db.php';

$inventory = [];
$result = $conn->query("SELECT id, item_name, category, stock, price, status FROM inventory ORDER BY id DESC");
if (!$result) {
    die('Unable to load inventory items.');
}
while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}
$result->free();

$total_items = count($inventory);
$low_stock = 0;
$inventory_value = 0;
foreach ($inventory as $item) {
    if ((int)$item['stock'] < 5) {
        $low_stock++;
    }
    $inventory_value += (int)$item['stock'] * (float)$item['price'];
}

$inventory_message = $_SESSION['inventory_message'] ?? '';
$inventory_success = $_SESSION['inventory_success'] ?? false;
unset($_SESSION['inventory_message'], $_SESSION['inventory_success']);
?>
    <!-- Inventory Page Content -->
    <div class="space-y-6">

        <!-- Page Header & Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Inventory Management</h1>
                <p class="text-sm text-base-content/60 mt-1">Subaybayan at pamahalaan ang mga sangkap at stock ng mga keyk.</p>
            </div>
            <div>
                <!-- Button para magdagdag ng bagong item -->
                <button onclick="document.getElementById('add_item_modal').showModal()" class="btn btn-primary rounded-xl gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Add New Item
                </button>
            </div>
        </div>

        <?php if ($inventory_message !== ''): ?>
            <div class="alert <?= $inventory_success ? 'alert-success' : 'alert-error' ?>" role="alert">
                <span><?= htmlspecialchars($inventory_message, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        <?php endif; ?>

        <!-- Inventory Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card bg-base-100 shadow-md p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Total Items</p>
                <h3 class="text-xl font-bold mt-1 text-base-content"><?= $total_items ?> Products</h3>
            </div>
            <div class="card bg-base-100 shadow-md p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Low Stock Alerts</p>
                <h3 class="text-xl font-bold mt-1 text-error"><?= $low_stock ?> Items</h3>
            </div>
            <div class="card bg-base-100 shadow-md p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Total Inventory Value</p>
                <h3 class="text-xl font-bold mt-1 text-base-content">₱<?= number_format($inventory_value, 2) ?></h3>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card bg-base-100 shadow-md p-6 rounded-2xl">

            <!-- Table Search & Filter Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                <div class="w-full sm:w-72">
                    <input type="text" placeholder="Maghanap ng item..." class="input input-bordered w-full rounded-xl text-sm" />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select class="select select-bordered rounded-xl text-sm w-full sm:w-auto">
                        <option disabled selected>Filter Category</option>
                        <option>Ingredients</option>
                        <option>Finished Cakes</option>
                        <option>Packaging</option>
                    </select>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Stock Qty</th>
                            <th>Unit Price</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inventory)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-base-content/60">Wala pang inventory items.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inventory as $item): ?>
                                <?php
                                $stock = (int)$item['stock'];
                                $status = $stock > 0 ? ($stock < 5 ? 'Low Stock' : 'In Stock') : 'Out of Stock';
                                $badge = $stock > 0 ? ($stock < 5 ? 'badge-warning' : 'badge-success') : 'badge-error';
                                ?>
                                <tr>
                                    <td class="font-semibold"><?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= $stock ?></td>
                                    <td>₱<?= number_format((float)$item['price'], 2) ?></td>
                                    <td><span class="badge <?= $badge ?> badge-sm"><?= $status ?></span></td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                type="button"
                                                class="btn btn-ghost btn-xs text-info edit-item"
                                                data-id="<?= (int)$item['id'] ?>"
                                                data-name="<?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-category="<?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-stock="<?= $stock ?>"
                                                data-price="<?= htmlspecialchars((string)$item['price'], ENT_QUOTES, 'UTF-8') ?>">
                                                Edit
                                            </button>
                                            <form method="post" action="actions/inventory-process.php" onsubmit="return confirm('Delete this inventory item?');">
                                                <input type="hidden" name="action" value="delete_item">
                                                <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
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

    <!-- Modal para sa pagdaragdag ng Item (DaisyUI Modal) -->
    <dialog id="add_item_modal" class="modal">
        <div class="modal-box rounded-2xl">
            <h3 class="font-bold text-lg text-base-content">Add New Inventory Item</h3>
            <p class="text-xs text-base-content/60 mt-1">Ilagay ang mga detalye ng bagong sangkap o produkto.</p>

            <form method="post" action="actions/inventory-process.php">
                <input type="hidden" name="action" value="add_item">
                <div class="py-4 space-y-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Item Name</span></label>
                        <input type="text" name="item_name" placeholder="Hal. Whipping Cream" class="input input-bordered rounded-xl w-full" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Category</span></label>
                        <select name="category" class="select select-bordered rounded-xl w-full" required>
                            <option>Ingredients</option>
                            <option>Finished Cakes</option>
                            <option>Packaging</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-medium">Stock Qty</span></label>
                            <input type="number" name="stock" min="0" step="1" placeholder="0" class="input input-bordered rounded-xl w-full" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-medium">Unit Price (₱)</span></label>
                            <input type="number" name="price" min="0" step="0.01" placeholder="0.00" class="input input-bordered rounded-xl w-full" required />
                        </div>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost rounded-xl" onclick="document.getElementById('add_item_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-xl">Save Item</button>
                </div>
            </form>
        </div>
</dialog>

<dialog id="edit_item_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg text-base-content">Edit Inventory Item</h3>
        <form method="post" action="actions/inventory-process.php">
            <input type="hidden" name="action" value="update_item">
            <input type="hidden" name="item_id" id="edit_item_id">
            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Item Name</span></label>
                    <input type="text" name="item_name" id="edit_item_name" class="input input-bordered rounded-xl w-full" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Category</span></label>
                    <select name="category" id="edit_item_category" class="select select-bordered rounded-xl w-full" required>
                        <option>Ingredients</option>
                        <option>Finished Cakes</option>
                        <option>Packaging</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Stock Qty</span></label>
                        <input type="number" name="stock" id="edit_item_stock" min="0" step="1" class="input input-bordered rounded-xl w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Unit Price (₱)</span></label>
                        <input type="number" name="price" id="edit_item_price" min="0" step="0.01" class="input input-bordered rounded-xl w-full" required>
                    </div>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost rounded-xl" onclick="document.getElementById('edit_item_modal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-xl">Save Changes</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    document.querySelectorAll('.edit-item').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_item_id').value = button.dataset.id;
            document.getElementById('edit_item_name').value = button.dataset.name;
            document.getElementById('edit_item_category').value = button.dataset.category;
            document.getElementById('edit_item_stock').value = button.dataset.stock;
            document.getElementById('edit_item_price').value = button.dataset.price;
            document.getElementById('edit_item_modal').showModal();
        });
    });
</script>