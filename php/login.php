<?php
    require_once "config.php";
    require_once "session. php";

    $error = '';

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $data_nascita = trim($_POST['data_nascita']);
        $email = trim($_POST['email']);
        $codice_fiscale = trim($_POST['cf']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $password = stripslashes($password);
        $email = stripslashes($email);

        $password_encrypt = md5($password);

        if(empty($email)) {
            $error .= '<p class = "error" Immettere un email! <!p>';
        }

        if(empty($password)) {
            $error .= '<p class = "error" Immettere una password! <!p>';
        }

        if(empty($error)) {
            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password_encrypt'";
        }
    }
?>
