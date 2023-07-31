<?php
// Start the session (if not already started)
session_start();

// Clear the session data
session_destroy();

// Redirect the user to the login page
header("Location: index.php");
exit();
?>




