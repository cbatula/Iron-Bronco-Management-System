<?php
// end the session to clear all data
session_name('user');
session_start();
session_destroy();
header('Location: ../HTML/login.html');
?>
