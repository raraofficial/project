<?php
session_start();

// hapus semua session
session_unset();
session_destroy();

// setelah logout, kembali ke login.php
header("Location: login.php");
exit;
?>