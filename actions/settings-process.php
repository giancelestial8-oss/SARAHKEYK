
<?php

// Sinisimulan ang session para ma-access ang session variables ng naka-login na user.
session_start();

// Kinukuha ang database connection mula sa db.php file.
require '../config/db.php';


// Tinitingnan kung may naka-login na user.
// Kapag walang user_id, hindi siya pinapayagang mag-update ng settings.
if (!isset($_SESSION['user_id'])) {

    // Ire-redirect ang user sa login page.
    header("Location: ../index.php");

    // Ihihinto ang execution ng PHP.
    exit;
}


// Tinitingnan kung POST request ang ipinadala.
// POST ang ginagamit kapag nagsu-submit ng settings form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Kapag hindi POST request, ibabalik ang user sa Settings page.
    header("Location: ../dashboard.php?page=settings");

    // Ihihinto ang execution ng script.
    exit;
}


// Kinukuha ang bagong email address mula sa submitted form.
// trim() removes extra spaces sa simula at dulo ng email.
$email = trim($_POST['email'] ?? '');


// Kinukuha ang kasalukuyang password ng user.
// Ginagamit ito para ma-verify na siya talaga ang may-ari ng account.
$current_password = $_POST['current_password'] ?? '';


// Kinukuha ang bagong password na gustong gamitin ng user.
$new_password = $_POST['new_password'] ?? '';


// Kinukuha ang confirmation password.
// Ito ang ginagamit para matiyak na magkapareho ang bagong password.
$confirm_password = $_POST['confirm_password'] ?? '';


// Default success status ay false.
// Ibig sabihin, hindi pa successful ang account update.
$success = false;


// Default message kapag invalid ang email o walang current password.
$message = 'Please enter a valid email and current password.';


// VALIDATION
// Tinitingnan kung valid ang email format.
// Tinitingnan din kung may inilagay na current password.
if (filter_var($email, FILTER_VALIDATE_EMAIL) && $current_password !== '') {

    // SQL SELECT query para kunin ang password ng naka-login na user.
    // WHERE id = ? para specific user lang ang kukunan ng password.
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");

    // Tinitingnan kung successful ang paghahanda ng SQL query.
    if ($stmt) {

        // Ikinokonekta ang user ID sa SQL placeholder.
        // i means integer.
        // $_SESSION['user_id'] ang ID ng kasalukuyang naka-login na user.
        $stmt->bind_param("i", $_SESSION['user_id']);

        // Ine-execute ang SELECT query.
        if ($stmt->execute()) {

            // Kinukuha ang resulta ng SQL query.
            $result = $stmt->get_result();

            // Kinukuha ang isang user record bilang associative array.
            // Halimbawa: $user['password'].
            $user = $result->fetch_assoc();

            // Isinasara ang prepared statement.
            $stmt->close();


            // PASSWORD VERIFICATION
            // Tinitingnan kung may nahanap na user.
            // password_verify() checks kung match ang current password
            // sa hashed password na naka-save sa database.
            if ($user && password_verify($current_password, $user['password'])) {

                // EMAIL UPDATE ONLY
                // Tinitingnan kung walang inilagay na bagong password.
                // Ibig sabihin, email lang ang gustong palitan.
                if ($new_password === '' && $confirm_password === '') {

                    // SQL UPDATE query para email lang ang baguhin.
                    // WHERE id = ? para sa kasalukuyang user lang.
                    $update = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");

                    // Tinitingnan kung successful ang paghahanda ng UPDATE query.
                    if ($update) {

                        // Ikinokonekta ang email at user ID sa SQL placeholders.
                        // s = string para sa email.
                        // i = integer para sa user ID.
                        $update->bind_param("si", $email, $_SESSION['user_id']);

                        // Ine-execute ang UPDATE query.
                        $success = $update->execute();

                        // Isinasara ang prepared statement.
                        $update->close();
                    }


                // EMAIL AND PASSWORD UPDATE
                // Tinitingnan kung ang bagong password ay:
                // 1. May minimum na 8 characters.
                // 2. Magkapareho sa confirmation password.
                } elseif (strlen($new_password) >= 8 && $new_password === $confirm_password) {

                    // Gina-hash ang bagong password bago i-save sa database.
                    // PASSWORD_DEFAULT ang recommended PHP password hashing algorithm.
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    // SQL UPDATE query para sabay palitan ang email at password.
                    $update = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");

                    // Tinitingnan kung successful ang paghahanda ng UPDATE query.
                    if ($update) {

                        // Ikinokonekta ang email, hashed password, at user ID.
                        // s = string para sa email.
                        // s = string para sa hashed password.
                        // i = integer para sa user ID.
                        $update->bind_param("ssi", $email, $password_hash, $_SESSION['user_id']);

                        // Ine-execute ang UPDATE query.
                        $success = $update->execute();

                        // Isinasara ang prepared statement.
                        $update->close();
                    }

                } else {

                    // Lalabas kapag hindi magkapareho ang password
                    // o kulang sa 8 characters ang bagong password.
                    $message = 'New passwords must match and contain at least 8 characters.';
                }


                // Tinitingnan kung successful ang account update.
                if ($success) {

                    // Ina-update ang email sa session para updated din
                    // ang email na ipinapakita sa dashboard.
                    $_SESSION['email'] = $email;

                    // Success message kapag successful ang account update.
                    $message = 'Account settings updated.';

                // Kapag hindi successful at default message pa rin ang laman,
                // papalitan ito ng database update error message.
                } elseif ($message === 'Please enter a valid email and current password.') {

                    // Error message kapag hindi na-update ang account.
                    // Posibleng ginagamit na ng ibang user ang email.
                    $message = 'Unable to update account settings. Check if the email is already in use.';
                }

            } else {

                // Lalabas kapag walang nahanap na user
                // o hindi tugma ang current password.
                $message = 'Current password is incorrect.';
            }

        } else {

            // Isinasara ang prepared statement kapag nagkaroon ng execution error.
            $stmt->close();

            // Error message kapag hindi ma-verify ang account.
            $message = 'Unable to verify your account.';
        }

    } else {

        // Lalabas kapag hindi naihanda ang SELECT query.
        $message = 'Unable to verify your account.';
    }
}


// Sine-save ang notification message sa session.
// Magagamit ito sa Settings page para ipakita ang success o error message.
$_SESSION['settings_message'] = $message;


// Sine-save kung successful o failed ang account update.
// True = successful, False = failed.
$_SESSION['settings_success'] = $success;


// Ire-redirect ang user pabalik sa Settings page.
header("Location: ../dashboard.php?page=settings");

// Ihihinto ang execution ng PHP pagkatapos ng redirect.
exit;

?>
