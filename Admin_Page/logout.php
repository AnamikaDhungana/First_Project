<?php
session_start();
session_unset(); // Removes all session variables
session_destroy(); // Destroys the session

header("Location: ../Login/login.html");
exit();
?>
