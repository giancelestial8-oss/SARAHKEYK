<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- Customers Management Page -->
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Customers Directory</h1>
                <p class="text-sm text-base-content/60 mt-1">Listahan ng mga kliyente at kanilang order history sa SarahKeyk.</p>
            </div>
            <button onclick="document.getElementById('add_customer_modal').showModal()" class="btn btn-primary rounded-xl gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="19" x2="19" y1="8" y2="14" />
                    <line x1="22" x2="16" y1="11" y2="11" />
                </svg>
                Add Customer
            </button>
        </div>

        <!-- Customers Table Card -->
        <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">

            <div class="flex items-center justify-between mb-6">
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
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary/15 text-primary w-9 rounded-full font-bold text-xs">MS</div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Maria Santos</div>
                                        <div class="text-xs text-base-content/60">maria.santos@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>0917-555-0192</td>
                            <td>Brgy. Libid, Binangonan, Rizal</td>
                            <td>8 Orders</td>
                            <td class="font-semibold">₱9,450.00</td>
                            <td class="text-center">
                                <button class="btn btn-ghost btn-xs text-info">Edit</button>
                                <button class="btn btn-ghost btn-xs text-error">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-secondary/15 text-secondary w-9 rounded-full font-bold text-xs">JD</div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Juan Dela Cruz</div>
                                        <div class="text-xs text-base-content/60">juandelacruz@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>0920-888-3411</td>
                            <td>Brgy. Calumpang, Binangonan, Rizal</td>
                            <td>3 Orders</td>
                            <td class="font-semibold">₱4,100.00</td>
                            <td class="text-center">
                                <button class="btn btn-ghost btn-xs text-info">Edit</button>
                                <button class="btn btn-ghost btn-xs text-error">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <dialog id="add_customer_modal" class="modal">
        <div class="modal-box rounded-2xl">
            <h3 class="font-bold text-lg">Add New Customer</h3>
            <div class="py-4 space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Full Name</span></label>
                    <input type="text" placeholder="Juan Dela Cruz" class="input input-bordered rounded-xl w-full" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Contact Number</span></label>
                        <input type="text" placeholder="09XX-XXX-XXXX" class="input input-bordered rounded-xl w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Email (Optional)</span></label>
                        <input type="email" placeholder="email@example.com" class="input input-bordered rounded-xl w-full" />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Delivery Address</span></label>
                    <textarea class="textarea textarea-bordered rounded-xl w-full" placeholder="House No., Street, Barangay, Municipality"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn btn-ghost rounded-xl">Cancel</button>
                    <button class="btn btn-primary rounded-xl">Save Customer</button>
                </form>
            </div>
        </div>
    </dialog>
</body>

</html>