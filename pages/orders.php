<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<!-- Orders Management Page -->
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Orders Management</h1>
            <p class="text-sm text-base-content/60 mt-1">Pamahalaan ang mga papasok na order at delivery status ng mga keyk.</p>
        </div>
        <button onclick="document.getElementById('new_order_modal').showModal()" class="btn btn-primary rounded-xl gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Create Order
        </button>
    </div>

    <!-- Order Status Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
            <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Pending</p>
            <h3 class="text-xl font-bold mt-1 text-info">5 Orders</h3>
        </div>
        <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
            <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Baking / Processing</p>
            <h3 class="text-xl font-bold mt-1 text-warning">3 Orders</h3>
        </div>
        <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
            <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Completed Today</p>
            <h3 class="text-xl font-bold mt-1 text-success">12 Orders</h3>
        </div>
        <div class="card bg-base-100 border border-base-300 shadow-sm p-4 rounded-2xl">
            <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Cancelled</p>
            <h3 class="text-xl font-bold mt-1 text-error">1 Order</h3>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
        
        <!-- Search & Filter -->
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
                    <tr>
                        <td class="font-bold">#SK-1051</td>
                        <td>
                            <div class="font-semibold">Camille Mendoza</div>
                            <div class="text-xs text-base-content/60">0917-123-4567</div>
                        </td>
                        <td>Strawberry Shortcake (6")</td>
                        <td>Nov 16, 2026 - 2:00 PM</td>
                        <td class="font-semibold">₱950.00</td>
                        <td><span class="badge badge-info badge-sm text-white">Pending</span></td>
                        <td class="text-center">
                            <button class="btn btn-ghost btn-xs text-primary">View</button>
                            <button class="btn btn-ghost btn-xs text-success">Done</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold">#SK-1050</td>
                        <td>
                            <div class="font-semibold">Mark Anthony Cruz</div>
                            <div class="text-xs text-base-content/60">0998-765-4321</div>
                        </td>
                        <td>Custom Minimalist Bento Cake</td>
                        <td>Nov 15, 2026 - 10:00 AM</td>
                        <td class="font-semibold">₱450.00</td>
                        <td><span class="badge badge-warning badge-sm">Processing</span></td>
                        <td class="text-center">
                            <button class="btn btn-ghost btn-xs text-primary">View</button>
                            <button class="btn btn-ghost btn-xs text-success">Done</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<dialog id="new_order_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg">Create New Order</h3>
        <p class="text-xs text-base-content/60 mt-1">Mag-encode ng bagong order para sa customer.</p>
        
        <div class="py-4 space-y-4">
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Customer Name</span></label>
                <input type="text" placeholder="Buong pangalan ng customer" class="input input-bordered rounded-xl w-full" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Cake Item / Flavor</span></label>
                <input type="text" placeholder="Hal. Mocha Chiffon Cake (8 inch)" class="input input-bordered rounded-xl w-full" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Pickup Date</span></label>
                    <input type="date" class="input input-bordered rounded-xl w-full" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Total Amount (₱)</span></label>
                    <input type="number" placeholder="0.00" class="input input-bordered rounded-xl w-full" />
                </div>
            </div>
        </div>

        <div class="modal-action">
            <form method="dialog" class="flex gap-2">
                <button class="btn btn-ghost rounded-xl">Cancel</button>
                <button class="btn btn-primary rounded-xl">Save Order</button>
            </form>
        </div>
    </div>
</dialog>
</body>

</html>