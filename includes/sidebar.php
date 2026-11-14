<?php
// Kunin kung anong page ang kasalukuyang nakabukas para malagyan ng 'active' class
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<aside class="w-72 h-screen bg-base-200 text-base-content flex flex-col border-r border-base-300 shrink-0 sticky top-0 select-none font-sans">

    <!-- Brand / Logo Header -->
    <div class="h-20 flex items-center gap-3 px-6 border-b border-base-300 bg-base-100/50">
        <div class="w-10 h-10 rounded-xl bg-primary text-primary-content flex items-center justify-center shadow-md">
            <!-- Cake Icon (SVG) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        <div>
            <a href="dashboard.php" class="text-xl font-extrabold tracking-tight text-base-content block leading-none">
                SarahKeyk
            </a>
            <span class="text-xs text-base-content/60 font-medium mt-1 block">Admin Management</span>
        </div>
    </div>

    <!-- Navigation Links (Scrollable kung marami na) -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">

        <!-- Main Menu Group -->
        <div>
            <div class="text-[11px] font-bold uppercase tracking-wider text-base-content/40 px-3 mb-2">
                Main Menu
            </div>
            <ul class="menu menu-md w-full gap-1.5 p-0">
                <!-- Dashboard -->
                <li>
                    <a href="dashboard.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-medium transition-all <?= ($current_page == 'home') ? 'bg-primary text-primary-content shadow-sm font-semibold' : 'hover:bg-base-300/60 text-base-content/80 hover:text-base-content' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1" />
                            <rect width="7" height="5" x="14" y="3" rx="1" />
                            <rect width="7" height="9" x="14" y="12" rx="1" />
                            <rect width="7" height="5" x="3" y="16" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Inventory -->
                <li>
                    <a href="dashboard.php?page=inventory" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-medium transition-all <?= ($current_page == 'inventory') ? 'bg-primary text-primary-content shadow-sm font-semibold' : 'hover:bg-base-300/60 text-base-content/80 hover:text-base-content' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                            <path d="m3.3 7 8.7 5 8.7-5" />
                            <path d="M12 22V12" />
                        </svg>
                        <span>Inventory</span>
                    </a>
                </li>

                <!-- Orders -->
                <li>
                    <a href="dashboard.php?page=orders" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-medium transition-all <?= ($current_page == 'orders') ? 'bg-primary text-primary-content shadow-sm font-semibold' : 'hover:bg-base-300/60 text-base-content/80 hover:text-base-content' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Customers -->
                <li>
                    <a href="dashboard.php?page=customers" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-medium transition-all <?= ($current_page == 'customers') ? 'bg-primary text-primary-content shadow-sm font-semibold' : 'hover:bg-base-300/60 text-base-content/80 hover:text-base-content' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span>Customers</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- System Group -->
        <div>
            <div class="text-[11px] font-bold uppercase tracking-wider text-base-content/40 px-3 mb-2">
                System
            </div>
            <ul class="menu menu-md w-full gap-1.5 p-0">
                <!-- Settings -->
                <li>
                    <a href="dashboard.php?page=settings" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-medium transition-all <?= ($current_page == 'settings') ? 'bg-primary text-primary-content shadow-sm font-semibold' : 'hover:bg-base-300/60 text-base-content/80 hover:text-base-content' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- User Profile & Logout Footer -->
    <div class="p-4 border-t border-base-300 bg-base-100/30">
        <div class="dropdown dropdown-top w-full">
            <div tabindex="0" role="button" class="flex items-center justify-between w-full p-2.5 rounded-xl hover:bg-base-300/60 transition-all cursor-pointer">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content w-10 rounded-full font-bold">
                            <span class="text-xs">
                                <?= isset($_SESSION['email']) ? strtoupper(substr($_SESSION['email'], 0, 2)) : 'AD'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="truncate text-left">
                        <p class="text-sm font-bold truncate text-base-content leading-tight">
                            <?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Admin'; ?>
                        </p>
                        <p class="text-[11px] text-base-content/50 font-medium">Administrator</p>
                    </div>
                </div>
                <!-- Chevron Up Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-base-content/40 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m18 15-6-6-6 6" />
                </svg>
            </div>

            <!-- Dropdown Menu Content -->
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-2xl z-50 w-60 p-2 shadow-xl border border-base-300 mb-2">
                <li>
                    <a href="dashboard.php?page=profile" class="flex items-center gap-2.5 py-2.5 px-3 rounded-lg font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Profile Settings
                    </a>
                </li>
                <div class="divider my-1 opacity-50"></div>
                <li>
                    <a href="actions/logout.php" class="text-error flex items-center gap-2.5 py-2.5 px-3 rounded-lg font-medium hover:bg-error/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                        Log out
                    </a>
                </li>
            </ul>
        </div>
    </div>

</aside>