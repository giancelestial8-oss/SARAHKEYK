<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- System Settings Page -->
<div class="space-y-6 max-w-4xl">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-base-content">System Settings</h1>
        <p class="text-sm text-base-content/60 mt-1">I-configure ang impormasyon ng tindahan, security, at hitsura ng sistema.</p>
    </div>

    <!-- Shop Information Card -->
    <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
        <h2 class="text-lg font-bold text-base-content mb-1">Bakery Information</h2>
        <p class="text-xs text-base-content/60 mb-5">Mga detalye na lumalabas sa mga resibo at order slips.</p>

        <form class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Shop Name</span></label>
                    <input type="text" value="SarahKeyk Pastry & Cakes" class="input input-bordered rounded-xl w-full" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Contact Number</span></label>
                    <input type="text" value="0917-000-0000" class="input input-bordered rounded-xl w-full" />
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Shop Address</span></label>
                <input type="text" value="Binangonan, Rizal, Philippines" class="input input-bordered rounded-xl w-full" />
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" class="btn btn-primary rounded-xl">Save Shop Details</button>
            </div>
        </form>
    </div>

    <!-- Account & Security Card -->
    <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
        <h2 class="text-lg font-bold text-base-content mb-1">Account Security</h2>
        <p class="text-xs text-base-content/60 mb-5">Palitan ang iyong admin login credentials.</p>

        <form class="space-y-4">
            <div class="form-control">
                <label class="label"><span class="label-text font-medium">Admin Email</span></label>
                <input type="email" value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'admin@sarahkeyk.com'; ?>" class="input input-bordered rounded-xl w-full" readonly />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">New Password</span></label>
                    <input type="password" placeholder="••••••••" class="input input-bordered rounded-xl w-full" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Confirm Password</span></label>
                    <input type="password" placeholder="••••••••" class="input input-bordered rounded-xl w-full" />
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" class="btn btn-neutral rounded-xl">Update Password</button>
            </div>
        </form>
    </div>

    <!-- Theme Appearance Card -->
    <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-base-content">Dashboard Theme</h2>
                <p class="text-xs text-base-content/60 mt-1">Pumili ng kulay na babagay sa SarahKeyk brand.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="document.documentElement.setAttribute('data-theme', 'valentine')" class="btn btn-sm btn-secondary rounded-xl">Pink (Valentine)</button>
                <button type="button" onclick="document.documentElement.setAttribute('data-theme', 'cupcake')" class="btn btn-sm btn-accent rounded-xl">Pastel (Cupcake)</button>
                <button type="button" onclick="document.documentElement.setAttribute('data-theme', 'light')" class="btn btn-sm btn-ghost border-base-300 rounded-xl">Clean Light</button>
            </div>
        </div>
    </div>

</div>
</body>
</html>