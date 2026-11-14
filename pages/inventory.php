<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
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

        <!-- Inventory Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Total Items</p>
                <h3 class="text-xl font-bold mt-1 text-base-content">24 Products</h3>
            </div>
            <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Low Stock Alerts</p>
                <h3 class="text-xl font-bold mt-1 text-error">2 Items</h3>
            </div>
            <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Total Inventory Value</p>
                <h3 class="text-xl font-bold mt-1 text-base-content">₱48,200</h3>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">

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
                        <tr>
                            <td class="font-semibold">All-Purpose Flour (25kg)</td>
                            <td>Ingredients</td>
                            <td>12 bags</td>
                            <td>₱850.00</td>
                            <td><span class="badge badge-success badge-sm text-white">In Stock</span></td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn btn-ghost btn-xs text-info">Edit</button>
                                    <button class="btn btn-ghost btn-xs text-error">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Unsalted Butter (1kg)</td>
                            <td>Ingredients</td>
                            <td>3 boxes</td>
                            <td>₱380.00</td>
                            <td><span class="badge badge-warning badge-sm">Low Stock</span></td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn btn-ghost btn-xs text-info">Edit</button>
                                    <button class="btn btn-ghost btn-xs text-error">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Chocolate Fudge Cake (8")</td>
                            <td>Finished Cakes</td>
                            <td>5 pcs</td>
                            <td>₱1,200.00</td>
                            <td><span class="badge badge-success badge-sm text-white">In Stock</span></td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn btn-ghost btn-xs text-info">Edit</button>
                                    <button class="btn btn-ghost btn-xs text-error">Delete</button>
                                </div>
                            </td>
                        </tr>
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

            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Item Name</span></label>
                    <input type="text" placeholder="Hal. Whipping Cream" class="input input-bordered rounded-xl w-full" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Category</span></label>
                    <select class="select select-bordered rounded-xl w-full">
                        <option>Ingredients</option>
                        <option>Finished Cakes</option>
                        <option>Packaging</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Stock Qty</span></label>
                        <input type="number" placeholder="0" class="input input-bordered rounded-xl w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Unit Price (₱)</span></label>
                        <input type="text" placeholder="0.00" class="input input-bordered rounded-xl w-full" />
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn btn-ghost rounded-xl">Cancel</button>
                    <button class="btn btn-primary rounded-xl">Save Item</button>
                </form>
            </div>
        </div>
    </dialog>
</body>

</html>