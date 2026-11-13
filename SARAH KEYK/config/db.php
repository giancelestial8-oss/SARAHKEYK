<?php
$dbHost = 'localhost';
$dbName = 'sarahkeyk_db';
$dbUser = 'root';
$dbPassword = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
	$db = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
	mysqli_set_charset($db, 'utf8mb4');
} catch (mysqli_sql_exception $exception) {
	http_response_code(500);
	exit('Database connection failed. Start MySQL in XAMPP and import database.sql.');
}
