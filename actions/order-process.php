
<?php

// Sinisimulan ang session para ma-access ang session variables.
session_start();

// Kinukuha ang database connection mula sa db.php file.
require '../config/db.php';


// Tinitingnan kung may naka-login na user.
// Kapag walang user_id, hindi siya makakagamit ng Orders functionality.
if (!isset($_SESSION['user_id'])) {

    // Ire-redirect ang user sa login page.
    header("Location: ../index.php");

    // Ihihinto ang execution ng PHP.
    exit;
}


// Tinitingnan kung POST request ang ipinadala.
// POST ang ginagamit kapag nagsu-submit ng order form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Kapag hindi POST request, ibabalik ang user sa Orders page.
    header("Location: ../dashboard.php?page=orders");

    // Ihihinto ang execution ng script.
    exit;
}


// Kinukuha ang action mula sa submitted form.
// Halimbawa: add_order, update_status, delete_order.
$action = $_POST['action'] ?? '';


// Default success status ay false.
// Ibig sabihin, wala pang successful na operation.
$success = false;


// Default message kapag walang valid na order action.
$message = 'Invalid order action.';


// ADD ORDER
// Tinitingnan kung ang action ay pag-create ng bagong order.
if ($action === 'add_order') {

    // Kinukuha ang customer ID mula sa form.
    // FILTER_VALIDATE_INT para masigurong integer ang customer ID.
    $customer_id = filter_var($_POST['customer_id'] ?? null, FILTER_VALIDATE_INT);

    // Kinukuha ang cake details na inilagay ng user.
    // trim() removes extra spaces sa simula at dulo.
    $cake_details = trim($_POST['cake_details'] ?? '');

    // Kinukuha ang pickup date mula sa form.
    // Pinapalitan ang letter T ng space.
    // Halimbawa: 2026-11-14T15:30 magiging 2026-11-14 15:30.
    $pickup_date = str_replace('T', ' ', trim($_POST['pickup_date'] ?? ''));

    // Kinukuha ang total price ng order.
    $total = $_POST['total'] ?? '';

    // Kino-convert at chine-check kung valid ang pickup date.
    // createFromFormat() expects Year-Month-Day Hour:Minute.
    $date = DateTime::createFromFormat('Y-m-d H:i', $pickup_date);


    // VALIDATION
    // Tinitingnan kung valid ang customer ID at mas mataas sa zero.
    // Tinitingnan kung may cake details.
    // Tinitingnan kung valid ang pickup date.
    // Tinitingnan kung numeric at hindi negative ang total.
    if ($customer_id !== false && $customer_id > 0 && $cake_details !== '' && $date && is_numeric($total) && (float)$total >= 0) {

        // Kino-convert ang pickup date sa database format.
        // Example: 2026-11-14 15:30:00.
        $pickup_date = $date->format('Y-m-d H:i:s');

        // Kino-convert ang total price into float para suportado ang decimal.
        $total = (float)$total;


        // Gumagawa ng SQL INSERT query para magdagdag ng bagong order.
        // Ang status ay automatic na Pending kapag bagong order.
        // Ang ? ay placeholders para sa values na ipapasa.
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, cake_details, pickup_date, total, status) VALUES (?, ?, ?, ?, 'Pending')");

        // Tinitingnan kung successful ang paghahanda ng SQL query.
        if ($stmt) {

            // Ikinokonekta ang PHP variables sa SQL placeholders.
            // i = integer para sa customer ID.
            // s = string para sa cake details.
            // s = string para sa pickup date.
            // d = double para sa total price.
            // issd = integer, string, string, double.
            $stmt->bind_param("issd", $customer_id, $cake_details, $pickup_date, $total);

            // Ine-execute ang INSERT query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang order creation.
        $message = $success ? 'Order created.' : 'Unable to create order.';

    } else {

        // Lalabas kapag invalid o kulang ang order information.
        $message = 'Please enter valid order details.';
    }


// UPDATE ORDER STATUS
// Tinitingnan kung ang action ay pag-update ng order status.
} elseif ($action === 'update_status') {

    // Kinukuha ang order ID at chine-check kung valid integer.
    $id = filter_var($_POST['order_id'] ?? null, FILTER_VALIDATE_INT);

    // Kinukuha ang bagong status mula sa form.
    $status = $_POST['status'] ?? '';

    // Listahan ng mga status na pinapayagan sa system.
    // Hindi tatanggap ng status na wala sa listahang ito.
    $valid_statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];


    // VALIDATION
    // Tinitingnan kung valid ang order ID.
    // Tinitingnan kung ang status ay kabilang sa allowed statuses.
    // Ang true sa in_array() ay nangangahulugang strict comparison.
    if ($id !== false && $id > 0 && in_array($status, $valid_statuses, true)) {

        // SQL UPDATE query para baguhin ang status ng isang order.
        // WHERE id = ? para specific order lang ang mabago.
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");

        // Tinitingnan kung successful ang paghahanda ng SQL query.
        if ($stmt) {

            // Ikinokonekta ang status at order ID sa SQL placeholders.
            // s = string para sa status.
            // i = integer para sa order ID.
            $stmt->bind_param("si", $status, $id);

            // Ine-execute ang UPDATE query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang status update.
        $message = $success ? 'Order status updated.' : 'Unable to update order status.';

    } else {

        // Lalabas kapag invalid ang order ID o status.
        $message = 'Please select a valid order status.';
    }


// DELETE ORDER
// Tinitingnan kung ang action ay pagtanggal ng order.
} elseif ($action === 'delete_order') {

    // Kinukuha ang order ID mula sa submitted form.
    // Chine-check kung valid integer ang value.
    $id = filter_var($_POST['order_id'] ?? null, FILTER_VALIDATE_INT);

    // Tinitingnan kung valid at mas mataas sa zero ang order ID.
    if ($id !== false && $id > 0) {

        // SQL DELETE query para tanggalin ang isang order.
        // WHERE id = ? para specific order lang ang matatanggal.
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");

        // Tinitingnan kung successful ang paghahanda ng SQL query.
        if ($stmt) {

            // Ikinokonekta ang order ID sa SQL placeholder.
            // i means integer.
            $stmt->bind_param("i", $id);

            // Ine-execute ang DELETE query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang deletion.
        $message = $success ? 'Order deleted.' : 'Unable to delete order.';

    } else {

        // Lalabas kapag invalid ang order ID.
        $message = 'Invalid order.';
    }
}


// Sine-save ang notification message sa session.
// Magagamit ito sa Orders page para ipakita ang success o error message.
$_SESSION['order_message'] = $message;


// Sine-save kung successful o failed ang order operation.
// True = successful, False = failed.
$_SESSION['order_success'] = $success;


// Ire-redirect ang user pabalik sa Orders page.
header("Location: ../dashboard.php?page=orders");

// Ihihinto ang PHP execution pagkatapos ng redirect.
exit;

?>
