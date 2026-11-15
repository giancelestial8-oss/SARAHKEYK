
<?php

// Sinisimulan ang session para ma-access natin ang session variables
// tulad ng user_id at customer_message.
session_start();

// Kinukuha ang database connection mula sa db.php file.
require '../config/db.php';


// Tinitingnan kung naka-login ang user.
// Kapag walang user_id sa session, ibig sabihin hindi siya naka-login.
if (!isset($_SESSION['user_id'])) {

    // Ire-redirect ang user sa login page.
    header("Location: ../index.php");

    // Ihihinto ang execution ng PHP para hindi na magpatuloy ang code.
    exit;
}


// Tinitingnan kung POST request ang ipinadalang request.
// POST ang ginagamit kapag nagsu-submit ng form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Kapag hindi POST request, ibabalik ang user sa Customers page.
    header("Location: ../dashboard.php?page=customers");

    // Ihihinto ang execution ng script.
    exit;
}


// Kinukuha ang value ng action mula sa submitted form.
// Ang ?? '' ay nangangahulugang kapag walang action, empty string ang gagamitin.
$action = $_POST['action'] ?? '';


// Default value na false ang success.
// Ibig sabihin, hindi pa successful ang anumang operation.
$success = false;


// Default message kapag walang valid na customer action.
$message = 'Invalid customer action.';


// Tinitingnan kung ang action ay add_customer o update_customer.
if ($action === 'add_customer' || $action === 'update_customer') {

    // Kinukuha ang customer name mula sa form.
    // trim() removes unnecessary spaces sa unahan at hulihan ng text.
    $name = trim($_POST['name'] ?? '');

    // Kinukuha ang phone number ng customer.
    $phone = trim($_POST['phone'] ?? '');

    // Kinukuha ang email address ng customer.
    $email = trim($_POST['email'] ?? '');

    // Kinukuha ang address ng customer.
    $address = trim($_POST['address'] ?? '');

    // Kinukuha ang customer ID at chine-check kung valid integer ito.
    // Ang FILTER_VALIDATE_INT ay ginagamit para mag-validate ng integer.
    $id = filter_var($_POST['customer_id'] ?? null, FILTER_VALIDATE_INT);

    // Kapag walang email na inilagay, gagawing NULL ang value nito.
    // Mahalaga ito kung optional ang email sa database.
    $email = $email === '' ? null : $email;


    // Chine-check kung may pangalan ang customer.
    // Kapag may email, kailangan din itong valid email format.
    if ($name !== '' && ($email === null || filter_var($email, FILTER_VALIDATE_EMAIL))) {

        // ADD CUSTOMER
        // Tinitingnan kung ang action ay pagdaragdag ng bagong customer.
        if ($action === 'add_customer') {

            // Gumagawa ng SQL INSERT query para magdagdag ng customer.
            // Ang ? ay placeholders para sa values na ipapasa natin.
            $stmt = $conn->prepare("INSERT INTO customers (name, phone, email, address) VALUES (?, ?, ?, ?)");

            // Tinitingnan kung successful ang paghahanda ng SQL statement.
            if ($stmt) {

                // Ikinokonekta ang PHP variables sa SQL placeholders.
                // ssss means string, string, string, string.
                $stmt->bind_param("ssss", $name, $phone, $email, $address);

                // Ine-execute ang INSERT query.
                // Kapag successful, magiging true ang $success.
                $success = $stmt->execute();

                // Isinasara ang prepared statement pagkatapos gamitin.
                $stmt->close();
            }

            // Naglalagay ng message depende kung successful ang pag-add.
            $message = $success ? 'Customer added.' : 'Unable to add customer.';


        // UPDATE CUSTOMER
        // Kapag hindi add_customer, iche-check kung update_customer.
        } elseif ($id !== false && $id > 0) {

            // Gumagawa ng SQL UPDATE query para baguhin ang customer information.
            // WHERE id = ? para specific customer lang ang mabago.
            $stmt = $conn->prepare("UPDATE customers SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?");

            // Tinitingnan kung successful ang paghahanda ng SQL statement.
            if ($stmt) {

                // Ikinokonekta ang PHP variables sa SQL placeholders.
                // ssssi means four strings at isang integer.
                $stmt->bind_param("ssssi", $name, $phone, $email, $address, $id);

                // Ine-execute ang UPDATE query.
                $success = $stmt->execute();

                // Isinasara ang prepared statement.
                $stmt->close();
            }

            // Naglalagay ng message depende kung successful ang update.
            $message = $success ? 'Customer updated.' : 'Unable to update customer.';

        } else {

            // Lalabas ang message na ito kapag invalid ang customer ID.
            $message = 'Invalid customer.';
        }

    } else {

        // Lalabas kapag walang pangalan o invalid ang email address.
        $message = 'Please enter a name and a valid email address.';
    }


// DELETE CUSTOMER
// Tinitingnan kung ang action ay delete_customer.
} elseif ($action === 'delete_customer') {

    // Kinukuha ang customer ID at chine-check kung valid integer.
    $id = filter_var($_POST['customer_id'] ?? null, FILTER_VALIDATE_INT);

    // Tinitingnan kung valid at mas mataas sa zero ang customer ID.
    if ($id !== false && $id > 0) {

        // Gumagawa ng SQL DELETE query para tanggalin ang customer.
        // WHERE id = ? para isang specific customer lang ang matanggal.
        $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");

        // Tinitingnan kung successful ang paghahanda ng SQL statement.
        if ($stmt) {

            // Ikinokonekta ang customer ID sa SQL placeholder.
            // i means integer.
            $stmt->bind_param("i", $id);

            // Ine-execute ang DELETE query.
            $success = $stmt->execute();

            // Isinasara ang prepared statement.
            $stmt->close();
        }

        // Naglalagay ng message depende kung successful ang deletion.
        $message = $success ? 'Customer deleted.' : 'Unable to delete customer.';

    } else {

        // Lalabas kapag invalid ang customer ID.
        $message = 'Invalid customer.';
    }
}


// Sine-save ang result message sa session.
// Magagamit ito sa Customers page para ipakita ang notification.
$_SESSION['customer_message'] = $message;


// Sine-save kung successful o failed ang operation.
// True = successful, False = failed.
$_SESSION['customer_success'] = $success;


// Ibinabalik ang user sa Customers page pagkatapos ng operation.
header("Location: ../dashboard.php?page=customers");

// Ihihinto ang execution pagkatapos ng redirect.
exit;

?>
