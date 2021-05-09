<?php
    require_once "config.php";
    require_once "session.php";

    $error ='';

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $data_nascita = trim($_POST['data_nascita']);
        $email = trim($_POST['email']);
        $codice_fiscale = trim($_POST['cf']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        $password_encrypt = md5($password);

        $sqlemail = "SELECT * FROM users WHERE emeail = $email";
        $result = mysqli_query($db, $sqlemail);
        $countrow = mysqli_num_rows($result);

        if($countrow == 1) {
            $error .= '<p class = "error"> Questa email è già registrata!</p>';
        }
        else {
            if(strlen($password) < 8) {
                $error .= '<p class = "error"> La password deve contenere almeno 8 caratteri!</p>';
            }

            if(empty($confirm_password)) {
                $error .= '<p class = "error"> Inserire il campo conferma password!</p>';
            }
            else {
                if(empty($error) && ($password != $confirm_password)) {
                    $error .= '<p class = "error"> Le due password non coincidono!</p>';
                }
            }
        }

        if(empty($error)) {
            $insertdb = "INSERT INTO users (nome, cognome, cf, data_nascita, email, password) VALUES ($nome, $cognome, $codice_fiscale, $data_nascita, $email, $password_encrypt)";
            $result = mysqli_query($db, $insertdb);

            if ($result) {
                $error .= '<p class = "success"> Registrazione avvenuto con successo!</p>';
            }
            else {
                $error .= '<p class = "success"> Qualcosa è andato storto!</p>';
            }
        }

        mysqli_close($db);
    }
?>

<!DOCTYPE html>

</html>