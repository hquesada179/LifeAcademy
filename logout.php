<?php
session_start();
session_destroy();
header("Location: Ingreso.html");
exit();
?>
