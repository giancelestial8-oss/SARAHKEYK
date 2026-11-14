<?php
// 1. Simulan ang session para ma-track kung sino ang naka-login
session_start();

// 2. Isali o ikonekta ang file ng database (pinaikot pataas gamit ang '../' dahil nasa loob ito ng actions folder)
require '../config/db.php';

// 3. Mag-set ng blangkong variable para sa error message sa simula
$error = '';

// 4. I-check kung pinindot na ba ang submit button na may pangalang 'login_btn'
if (isset($_POST['login_btn'])) {

    // 5. Kunin ang nilagay ng user sa email at password, tinanggalan ng extra spaces (trim)
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 6. I-check kung may laman pareho ang email at password fields
    if (!empty($email) && !empty($password)) {

        // 7. Gumawa ng SQL query para hanapin ang user gamit ang email
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        // 8. I-prepare ang SQL statement para ligtas sa SQL Injection
        $stmt = mysqli_prepare($conn, $sql);

        // 9. I-bind ang variable na $email
        mysqli_stmt_bind_param($stmt, "s", $email);

        // 10. I-execute ang query sa database
        mysqli_stmt_execute($stmt);

        // 11. Kunin ang resulta
        $result = mysqli_stmt_get_result($stmt);

        // 12. I-check kung may nahanap na user account
        if ($user = mysqli_fetch_assoc($result)) {

            // 13. I-verify ang password
            if (password_verify($password, $user['password'])) {

                // 14. I-save ang ID at email sa session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // 15. Dalhin ang user papunta sa dashboard page (nasa labas din ng actions folder)
                header("Location: ../dashboard.php");
                exit;
            } else {
                // 16. Error kapag mali ang password
                $error = "Mali ang password mo boss.";
            }
        } else {
            // 17. Error kapag walang nahanap na email
            $error = "Walang rehistradong account sa email na yan.";
        }
    } else {
        // 18. Error kapag may naiwang blangko
        $error = "Paki-fill up lahat ng fields.";
    }
}

// Opsyonal: Kung sakaling direktang puntahan ang file na ito nang hindi nag-a-submit, ibalik sa index.php
header("Location: ../index.php");
exit;
