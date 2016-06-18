<?php

session_start();
setcookie("email", "", time() - 360000, "/");
setcookie("fname", "", time() - 360000, "/");
header("Location: index.html");

?>