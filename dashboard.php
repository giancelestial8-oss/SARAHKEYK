<?php
// 1. Simulan ang session at i-check kung naka-login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SarahKeyk</title>

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- DaisyUI v4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-base-100 text-base-content antialiased">

    <!-- Main Wrapper (Ginawang Tailwind 'flex' sa halip na Bootstrap 'd-flex') -->
    <div class="flex min-h-screen">

        <!-- 2. Sidebar Component -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- 3. Main Content Area sa Kanan -->
        <main class="flex-1 bg-base-100 p-8 overflow-y-auto">
            <?php
            // Kunin ang 'page' mula sa URL. Kung walang pinindot, default sa 'home'
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';

            // Piliin kung aling file ang ia-include sa gitna
            switch ($page) {
                case 'inventory':
                    include 'pages/inventory.php';
                    break;
                case 'orders':
                    include 'pages/orders.php';
                    break;
                case 'customers':
                    include 'pages/customers.php';
                    break;
                case 'settings':
                    include 'settings/settings.php';
                    break;
                case 'home':
                default:
            ?>
                    <!-- Dashboard Home UI -->
                    <div class="space-y-6">

                        <!-- Welcome Header -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-extrabold tracking-tight text-base-content">Welcome back, Boss!</h1>
                                <p class="text-sm text-base-content/60 mt-1">Narito ang buod ng takbo ng SarahKeyk system ngayong araw.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="dashboard.php?page=orders" class="btn btn-primary btn-sm rounded-xl gap-2 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                    New Order
                                </a>
                            </div>
                        </div>

                        <!-- Quick Stats Cards (DaisyUI Stats) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Total Sales -->
                            <div class="card bg-base-100 border border-base-300 shadow-sm p-5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Total Sales</p>
                                        <h3 class="text-2xl font-bold mt-1 text-base-content">₱24,500</h3>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" x2="12" y1="2" y2="22" />
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-1 text-xs text-success font-medium">
                                    <span>+12%</span>
                                    <span class="text-base-content/50">vs nakaraang linggo</span>
                                </div>
                            </div>

                            <!-- Total Orders -->
                            <div class="card bg-base-100 border border-base-300 shadow-sm p-5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Orders</p>
                                        <h3 class="text-2xl font-bold mt-1 text-base-content">48</h3>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="8" cy="21" r="1" />
                                            <circle cx="19" cy="21" r="1" />
                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-1 text-xs text-base-content/60 font-medium">
                                    <span>5 ang naghihintay</span>
                                </div>
                            </div>

                            <!-- Inventory / Stocks -->
                            <div class="card bg-base-100 border border-base-300 shadow-sm p-5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Active Cakes</p>
                                        <h3 class="text-2xl font-bold mt-1 text-base-content">15</h3>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-1 text-xs text-warning font-medium">
                                    <span>Mababang stock: 2 items</span>
                                </div>
                            </div>

                            <!-- Customers -->
                            <div class="card bg-base-100 border border-base-300 shadow-sm p-5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/50">Customers</p>
                                        <h3 class="text-2xl font-bold mt-1 text-base-content">128</h3>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-info/10 text-info flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-1 text-xs text-info font-medium">
                                    <span>+4 ngayong linggo</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders Section -->
                        <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-base-content">Recent Orders</h3>
                                <a href="dashboard.php?page=orders" class="text-sm font-semibold text-primary hover:underline">Tingnan lahat</a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-zebra w-full">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Cake Item</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-medium">#SK-1048</td>
                                            <td>Maria Santos</td>
                                            <td>Chocolate Fudge Cake (8")</td>
                                            <td><span class="badge badge-success badge-sm text-white">Completed</span></td>
                                            <td>₱1,200</td>
                                        </tr>
                                        <tr>
                                            <td class="font-medium">#SK-1049</td>
                                            <td>Juan Dela Cruz</td>
                                            <td>Custom Birthday Theme Cake</td>
                                            <td><span class="badge badge-warning badge-sm">Processing</span></td>
                                            <td>₱2,500</td>
                                        </tr>
                                        <tr>
                                            <td class="font-medium">#SK-1050</td>
                                            <td>Ana Reyes</td>
                                            <td>Red Velvet Cupcakes (Box of 6)</td>
                                            <td><span class="badge badge-info badge-sm text-white">Pending</span></td>
                                            <td>₱450</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
            <?php
                    break;
            }
            ?>
        </main>

    </div>

</body>

</html>