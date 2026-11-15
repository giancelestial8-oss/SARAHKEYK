
<?php

// Sinisimulan ang session para ma-access ang session variables.
session_start();

// Kinukuha ang database connection mula sa db.php.
require '../config/db.php';


// Tinitingnan kung naka-login ang user.
// Kapag walang user_id, ibig sabihin hindi authenticated ang user.
if (!isset($_SESSION['user_id'])) {

    // Ire-redirect ang user sa login page.
    header("Location: ../index.php");

    // Ihihinto ang execution ng PHP.
    exit;
}


// Tinitingnan kung POST request ang ipinadala.
// POST request ang ginagamit kapag nagsu-submit ng form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Kapag hindi POST request, ibabalik sa Inventory page.
    header("Location: ../dashboard.php?page=inventory");

    // Ihihinto ang execution.
    exit;
}


// Kinukuha ang action mula sa submitted form.
// Halimbawa: add_item, update_item, delete_item, add_stock.
$action = $_POST['action'] ?? '';


// Default message kapag walang valid na inventory action.
$message = 'Invalid inventory action.';

// Default success status ay false dahil wala pang successful operation.
$success = false;


// Tinitingnan kung ang action ay add_item o update_item.
if ($action === 'add_item' || $action === 'update_item') {

    // Kinukuha ang pangalan ng item mula sa form.
    // trim() removes extra spaces sa simula at dulo.
    $item_name = trim($_POST['item_name'] ?? '');

    // Kinukuha ang category ng inventory item.
    $category = trim($_POST['category'] ?? '');

    // Kinukuha ang stock quantity at chine-check kung integer ito.
    // FILTER_VALIDATE_INT ensures na whole number ang stock.
    $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);

    // Kinukuha ang price mula sa form.
    // Empty string ang default kapag walang value.
    $price = $_POST['price'] ?? '';

    // Kinukuha ang item ID at chine-check kung valid integer.
    $id = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT);


    // VALIDATION
    // Tinitingnan kung valid ang lahat ng required information.
    if (
        $item_name !== '' &&          // Hindi dapat empty ang item name.
        $category !== '' &&           // Hindi dapat empty ang category.
        $stock !== false &&           // Dapat valid integer ang stock.
        $stock >= 0 &&                 // Hindi dapat negative ang stock.
        is_numeric($price) &&          // Dapat numeric ang price.
        (float)$price >= 0             // Hindi dapat negative ang price.
    ) {

        // Kino-convert ang price into float para decimal values ay suportado.
        $price = (float)$price;

        // Automatic na sine-set ang item status base sa stock quantity.
        // Kapag mas mataas sa zero, In Stock.
        // Kapag zero, Out of Stock.
        $status = $stock > 0 ? 'In Stock' : 'Out of Stock';


        // ADD ITEM
        // Tinitingnan kung ang action ay pagdaragdag ng bagong item.
        if ($action === 'add_item') {

            // SQL INSERT query para magdagdag ng bagong inventory record.
            // Ang ? ay placeholders para sa values na ipapasa natin.
            $stmt = $conn->prepare("INSERT INTO inventory (item_name, category, stock, price, status) VALUES (?, ?, ?, ?, ?)");

            // Tinitingnan kung successful ang paghahanda ng SQL query.
            if ($stmt) {

                // Ikinokonekta ang PHP variables sa SQL placeholders.
                // s = string
                // i = integer
                // d = double/decimal
                // s = string
                // ssids = string, string, integer, double, string
                $stmt->bind_param("ssids", $item_name, $category, $stock, $price, $status);

                // Ine-execute ang INSERT query.
                // True kapag successful ang database operation.
                $success = $stmt->execute();

                // Isinasara ang prepared statement.
                $stmt->close();
            }

            // Naglalagay ng message depende kung successful ang pag-add.
            $message = $success ? 'Inventory item added.' : 'Unable to add inventory item.';


        // UPDATE ITEM
        // Kapag hindi add_item, chine-check kung valid ang item ID.
        } elseif ($id !== false && $id > 0) {

            // SQL UPDATE query para baguhin ang existing inventory record.
            // WHERE id = ? para specific item lang ang maa-update.
            $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, category = ?, stock = ?, price = ?, status = ? WHERE id = ?");

            // Tinitingnan kung successful ang paghahanda ng SQL query.
            if ($stmt) {

                // Ikinokonekta ang variables sa SQL placeholders.
                // ssidsi = string, string, integer, double, string, integer.
                $stmt->bind_param("ssidsi", $item_name, $category, $stock, $price, $status, $id);

                // Ine-execute ang UPDATE query.
                // affected_rows >= 0 means walang SQL execution error,
                // kahit walang nabagong value sa database.
                $success = $stmt->execute() && $stmt->affected_rows >= 0;

                // Isinasara ang prepared statement.
                $stmt->close();
            }

            // Naglalagay ng message depende sa result ng update.
            $message = $success ? 'Inventory item updated.' : 'Unable to update inventory item.';

        } else {

            // Lalabas kapag invalid ang item ID.
            $message = 'Invalid inventory item.';
        }

    } else {

        // Lalabas kapag may invalid o kulang na inventory information.
        $message = 'Please enter a valid name, category, stock quantity, and price.';
    }


// DELETE ITEM
// Tinitingnan kung ang action ay delete_item.
} elseif ($action === 'delete_item') {

    // Kinukuha ang item ID at chine-check kung valid integer.
    $id = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT);

    // Tinitingnan kung valid at mas mataas sa zero ang item ID.
    if ($id !== false && $id > 0) {

        // SQL DELETE query para tanggalin ang inventory item.
        // WHERE id = ? para isang specific item lang ang matatanggal.
        $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");

        // Tinitingnan kung successful ang paghahanda ng SQL query.
        if ($stmt) {

            // Ikinokonekta ang item ID sa SQL placeholder.
            // i means integer.
            $stmt->bind_param("i", $id);

            // Ine-execute ang DELETE query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang deletion.
        $message = $success ? 'Inventory item deleted.' : 'Unable to delete inventory item.';

    } else {

        // Lalabas kapag invalid ang item ID.
        $message = 'Invalid inventory item.';
    }


// ADD STOCK
// Tinitingnan kung ang action ay add_stock.
// Ginagamit ito para dagdagan ang existing stock quantity.
} elseif ($action === 'add_stock') {

    // Kinukuha ang item ID at chine-check kung valid integer.
    $id = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT);

    // Kinukuha ang quantity na idaragdag sa existing stock.
    $add_qty = filter_var($_POST['add_qty'] ?? null, FILTER_VALIDATE_INT);


    // VALIDATION
    // Tinitingnan kung valid ang item ID at additional stock quantity.
    // Dapat parehong positive integers ang kanilang values.
    if ($id !== false && $id > 0 && $add_qty !== false && $add_qty > 0) {

        // SQL UPDATE query para dagdagan ang existing stock.
        // stock = stock + ? means idaragdag ang bagong quantity sa kasalukuyang stock.
        // Status ay automatic na magiging In Stock.
        $stmt = $conn->prepare("UPDATE inventory SET stock = stock + ?, status = 'In Stock' WHERE id = ?");

        // Tinitingnan kung successful ang paghahanda ng SQL query.
        if ($stmt) {

            // Ikinokonekta ang additional quantity at item ID.
            // ii means integer at integer.
            $stmt->bind_param("ii", $add_qty, $id);

            // Ine-execute ang stock update query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang stock update.
        $message = $success ? 'Stock updated.' : 'Unable to update stock.';

    } else {

        // Lalabas kapag invalid ang item ID o stock quantity.
        $message = 'Please enter a valid item and stock quantity.';
    }
}


// Sine-save ang result message sa session.
// Magagamit ito sa Inventory page para ipakita ang notification.
$_SESSION['inventory_message'] = $message;


// Sine-save kung successful o failed ang inventory operation.
// True = successful, False = failed.
$_SESSION['inventory_success'] = $success;


// Ibinabalik ang user sa Inventory page pagkatapos ng operation.
header("Location: ../dashboard.php?page=inventory");

// Ihihinto ang PHP execution pagkatapos ng redirect.
exit;

?>
