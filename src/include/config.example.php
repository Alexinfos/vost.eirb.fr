<?php
session_start();

require_once __DIR__ . '/../../php/vendor/autoload.php';

$database = new PDO('sqlite:' . __DIR__ . "/../../php/data/vost.sqlite");
$session = new Eirb\Vost\Web\Utils\Session($_SESSION);

?>