<?php
    
include 'main.php';


if (strlen($_POST['pass']) >= 6 && preg_match("#[0-9]+#", $_POST['pass']) && preg_match("#[a-zA-Z]+#", $_POST['pass'])){
    $key = "vj8n&s@2";

    $encrypted = mcrypt_ecb(MCRYPT_DES, $key, $_POST['pass'], MCRYPT_ENCRYPT);

    change_password($encrypted);
} else {
    echo "<script type = 'text/javascript'>
        alert('Your password needs to have at least 6 characters, and use a combination of both numbers and letters');
        window.location = 'register.html';
            </script>
            ";
}


?>
