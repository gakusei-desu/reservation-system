<?php
if (session_status() === PHP_SESSION_NONE) {
	ini_set('session.cookie_httponly', 1);
	if (!empty($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443) {
		ini_set('session.cookie_secure', 1);
	}
	session_start();
}
?>
