<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$appRoot = realpath(__DIR__ . '/..');
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
$baseUrl = '';
if ($appRoot && $documentRoot && strpos($appRoot, $documentRoot) === 0) {
	$baseUrl = str_replace('\\', '/', substr($appRoot, strlen($documentRoot)));
}
if (!defined('BASE_URL')) {
	define('BASE_URL', rtrim($baseUrl, '/'));
}

function app_url(string $path = ''): string
{
	return BASE_URL . ($path !== '' ? '/' . ltrim($path, '/') : '/');
}

$dbHost = 'localhost';
$dbName = 'sarahkeyk_db';
$dbUser = 'root';
$dbPassword = '';

try {
	$pdo = new PDO(
		"mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
		$dbUser,
		$dbPassword,
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		]
	);
} catch (PDOException $exception) {
	http_response_code(500);
	exit('Database connection failed. Start MySQL in XAMPP and import database.sql.');
}
