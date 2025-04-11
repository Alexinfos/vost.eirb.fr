<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\DataResponse;

if (!isset($_SESSION['cas_data']) || $_SESSION['cas_data'] == "") {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
} else if (!isset($_GET['name'])) {
    http_response_code(400);
    exit();
} else if (!isset($PROTECTED_DATA[$_GET['name']])) {
    http_response_code(404);
    exit();
}
header('Location: ' . $PROTECTED_DATA[$_GET['name']]);
?>
