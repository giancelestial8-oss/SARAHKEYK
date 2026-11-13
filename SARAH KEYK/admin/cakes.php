<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$seasons = ['Birthday', 'Summer', 'Christmas', "Valentine's", 'Wedding', 'All season', 'Other'];
$uploadDirectory = __DIR__ . '/../assets/uploads/cakes/';
$publicUploadPath = 'assets/uploads/cakes/';

function save_cake_image(array $file, string $uploadDirectory, string $publicUploadPath): ?string
{
	if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
	if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('The image could not be uploaded. Try again.');
	if (($file['size'] ?? 0) > 5 * 1024 * 1024) throw new RuntimeException('Cake images must be 5 MB or smaller.');

	$imageInfo = @getimagesize($file['tmp_name']);
	$mime = $imageInfo['mime'] ?? '';
	$extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
	if (!$imageInfo || !isset($extensions[$mime])) throw new RuntimeException('Choose a valid JPG, PNG, GIF, or WEBP image.');

	if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
		throw new RuntimeException('The image upload folder could not be created.');
	}
	$filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
	if (!move_uploaded_file($file['tmp_name'], $uploadDirectory . $filename)) throw new RuntimeException('The image could not be saved. Check folder permissions.');
	return $publicUploadPath . $filename;
}

function remove_cake_image(?string $imagePath): void
{
	if ($imagePath && strpos($imagePath, 'assets/uploads/cakes/') === 0) {
		$fullPath = dirname(__DIR__) . '/' . $imagePath;
		if (is_file($fullPath)) @unlink($fullPath);
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'delete') {
			$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
			if (!$id) throw new RuntimeException('Invalid cake selected.');
			$statement = mysqli_prepare($db, 'SELECT cake_image FROM cakes WHERE id = ?');
			mysqli_stmt_bind_param($statement, 'i', $id);
			mysqli_stmt_execute($statement);
			$result = mysqli_stmt_get_result($statement);
			$cake = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
			mysqli_stmt_close($statement);
			if (!$cake) throw new RuntimeException('That cake could not be found.');
			$statement = mysqli_prepare($db, 'DELETE FROM cakes WHERE id = ?');
			mysqli_stmt_bind_param($statement, 'i', $id);
			mysqli_stmt_execute($statement);
			mysqli_stmt_close($statement);
			remove_cake_image($cake['cake_image']);
			set_flash('success', 'Cake deleted successfully.');
		} elseif ($action === 'save') {
			$id = trim($_POST['id'] ?? '');
			$name = trim($_POST['cake_name'] ?? '');
			$description = trim($_POST['description'] ?? '');
			$price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
			$season = trim($_POST['season'] ?? '');
			if ($name === '' || mb_strlen($name) > 120) throw new RuntimeException('Cake name is required and must be 120 characters or fewer.');
			if ($description === '' || mb_strlen($description) > 2000) throw new RuntimeException('Description is required and must be 2,000 characters or fewer.');
			if ($price === false || $price < 0 || $price > 9999999) throw new RuntimeException('Enter a valid price between 0 and 9,999,999.');
			if (!in_array($season, $seasons, true)) throw new RuntimeException('Choose a valid cake season.');

			$newImage = save_cake_image($_FILES['cake_image'] ?? [], $uploadDirectory, $publicUploadPath);
			if ($id !== '') {
				$cakeId = filter_var($id, FILTER_VALIDATE_INT);
				if (!$cakeId) throw new RuntimeException('Invalid cake selected.');
				$statement = mysqli_prepare($db, 'SELECT cake_image FROM cakes WHERE id = ?');
				mysqli_stmt_bind_param($statement, 'i', $cakeId);
				mysqli_stmt_execute($statement);
				$result = mysqli_stmt_get_result($statement);
				$existing = mysqli_fetch_assoc($result);
				mysqli_free_result($result);
				mysqli_stmt_close($statement);
				if (!$existing) throw new RuntimeException('That cake could not be found.');
				if ($newImage) {
					$statement = mysqli_prepare($db, 'UPDATE cakes SET cake_name = ?, description = ?, price = ?, season = ?, cake_image = ? WHERE id = ?');
					mysqli_stmt_bind_param($statement, 'ssdssi', $name, $description, $price, $season, $newImage, $cakeId);
					mysqli_stmt_execute($statement);
					mysqli_stmt_close($statement);
					remove_cake_image($existing['cake_image']);
				} else {
					$statement = mysqli_prepare($db, 'UPDATE cakes SET cake_name = ?, description = ?, price = ?, season = ? WHERE id = ?');
					mysqli_stmt_bind_param($statement, 'ssdsi', $name, $description, $price, $season, $cakeId);
					mysqli_stmt_execute($statement);
					mysqli_stmt_close($statement);
				}
				set_flash('success', 'Cake updated successfully.');
			} else {
				$statement = mysqli_prepare($db, 'INSERT INTO cakes (cake_name, description, price, season, cake_image) VALUES (?, ?, ?, ?, ?)');
				mysqli_stmt_bind_param($statement, 'ssdss', $name, $description, $price, $season, $newImage);
				mysqli_stmt_execute($statement);
				mysqli_stmt_close($statement);
				set_flash('success', 'New cake added successfully.');
			}
		} else {
			throw new RuntimeException('Invalid form action.');
		}
	} catch (RuntimeException $exception) {
		if (isset($newImage) && $newImage) remove_cake_image($newImage);
		set_flash('danger', $exception->getMessage());
	} catch (mysqli_sql_exception $exception) {
		if (isset($newImage) && $newImage) remove_cake_image($newImage);
		set_flash('danger', 'The cake could not be saved. Please try again.');
	}
	header('Location: ' . app_url('admin/cakes.php'));
	exit;
}

$result = mysqli_query($db, 'SELECT id, cake_name, description, price, season, cake_image, date_added FROM cakes ORDER BY date_added DESC, id DESC');
$cakeCount = mysqli_num_rows($result);
$flash = get_flash();
$pageTitle = 'Inventory — SarahKeyk';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-wrap">
	<aside class="admin-sidebar">
		<div class="sidebar-label">INVENTORY</div>
		<a href="<?= e(app_url('admin/dashboard.php')) ?>">◫ &nbsp; Dashboard</a>
		<a class="active" href="<?= e(app_url('admin/cakes.php')) ?>">▦ &nbsp; Inventory</a>
		<div class="sidebar-note">Manage your cake inventory in one place.</div>
	</aside>
	<section class="admin-content">
		<div class="admin-heading">
			<div>
				<div class="auth-eyebrow">INVENTORY</div>
				<h1>Cake inventory</h1>
				<p class="muted-copy">Add, update, and organize your cake items.</p>
			</div>
			<button type="button" class="btn btn-rose" data-bs-toggle="modal" data-bs-target="#cakeModal" data-form-mode="create">＋ Add an item</button>
		</div>
		<?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert"><?= e($flash['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div><?php endif; ?>
		<div class="table-card">
			<div class="table-card-heading">
				<div>
					<h2>Inventory items</h2>
					<p><?= $cakeCount ?> <?= $cakeCount === 1 ? 'item' : 'items' ?> listed</p>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table cake-table align-middle mb-0">
					<thead>
						<tr>
							<th>Cake</th>
							<th>Season</th>
							<th>Price</th>
							<th>Added</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($cakeCount > 0): ?>
							<?php while ($cake = mysqli_fetch_assoc($result)): ?>
								<tr>
									<td>
										<div class="cake-cell"><img src="<?= e(app_url($cake['cake_image'] ?: 'assets/images/cake-placeholder.svg')) ?>" alt="<?= e($cake['cake_name']) ?>">
											<div><strong><?= e($cake['cake_name']) ?></strong><small><?= e(mb_strimwidth($cake['description'], 0, 68, '…')) ?></small></div>
										</div>
									</td>
									<td><span class="season-pill"><?= e($cake['season']) ?></span></td>
									<td class="price-cell">₱<?= number_format((float) $cake['price'], 2) ?></td>
									<td class="muted-copy"><?= e(date('M j, Y', strtotime($cake['date_added']))) ?></td>
									<td>
										<div class="table-actions">
											<button type="button" class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#cakeModal" data-form-mode="edit" data-id="<?= (int) $cake['id'] ?>" data-name="<?= e($cake['cake_name']) ?>" data-description="<?= e($cake['description']) ?>" data-price="<?= e($cake['price']) ?>" data-season="<?= e($cake['season']) ?>">Edit</button>
											<form method="post" onsubmit="return confirm('Delete this cake? This cannot be undone.');">
												<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $cake['id'] ?>">
												<button class="btn btn-delete btn-sm" type="submit">Delete</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endwhile; ?>
						<?php else: ?>
							<tr>
								<td colspan="5" class="empty-state"><span>♡</span><strong>Your inventory is empty</strong><small>Add your first cake item to get started.</small></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</main>
<?php mysqli_free_result($result); ?>

<div class="modal fade" id="cakeModal" tabindex="-1" aria-labelledby="cakeModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content cake-modal">
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" id="cakeId">
				<div class="modal-header">
					<div>
						<div class="auth-eyebrow">THE CAKE CASE</div>
						<h2 class="modal-title" id="cakeModalTitle">Add a cake</h2>
					</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body form-stack">
					<label>Cake name<input class="form-control" id="cakeName" name="cake_name" maxlength="120" required></label>
					<label>Description<textarea class="form-control" id="cakeDescription" name="description" rows="3" maxlength="2000" required></textarea></label>
					<div class="form-row"><label>Price (₱)<input class="form-control" id="cakePrice" type="number" name="price" min="0" max="9999999" step="0.01" required></label>
						<label>Season<select class="form-select" id="cakeSeason" name="season" required><?php foreach ($seasons as $season): ?><option value="<?= e($season) ?>"><?= e($season) ?></option><?php endforeach; ?></select></label>
					</div>
					<label>Cake image <span class="field-hint" id="imageHint">JPG, PNG, GIF or WEBP · max 5 MB</span><input class="form-control" id="cakeImage" type="file" name="cake_image" accept="image/jpeg,image/png,image/gif,image/webp"></label>
				</div>
				<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-rose" id="cakeSubmit">Add cake</button></div>
			</form>
		</div>
	</div>
</div>