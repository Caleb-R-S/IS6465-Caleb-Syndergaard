<?php
require_once __DIR__ . '/includes/auth.php';
startSecureSession();
header('Location: php/' . (!empty($_SESSION['user_id']) ? 'profile.php' : 'login.php'), true, 303);
exit;
