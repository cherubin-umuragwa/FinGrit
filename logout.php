<?php
require_once 'php/auth.php';

$auth = new Auth();
$auth->logout();

header("Location: index.php");
exit();
?>