<?php

// Realizar o logout de um usuário
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;

?>
