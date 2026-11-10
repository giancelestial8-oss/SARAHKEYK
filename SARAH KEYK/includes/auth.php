<?php
require_once __DIR__ . '/../config/database.php';

function e($value): string
{
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
	$submitted = $_POST['csrf_token'] ?? '';
	if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
		http_response_code(400);
		exit('Your form session expired. Go back, refresh the page, and try again.');
	}
}

function is_admin(): bool
{
	return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function require_admin(): void
{
	if (!is_admin()) {
		header('Location: ' . app_url('login.php'));
		exit;
	}
}

function set_flash(string $type, string $message): void
{
	$_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
	$flash = $_SESSION['flash'] ?? null;
	unset($_SESSION['flash']);
	return is_array($flash) ? $flash : null;
}
