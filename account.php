<?php
include "main.php";

$key = "vj8n&s@2";

$encrypted = mcrypt_ecb(MCRYPT_DES, $key, $_POST['pass'], MCRYPT_ENCRYPT);

change_password($_GET[$encrypted]);
?>