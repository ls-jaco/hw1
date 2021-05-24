<?php
    require_once "dbconfig.php";
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

        $password_encrypt = md5("$password");

        $sqlemail = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db, $sqlemail);
        $countrow = mysqli_num_rows($result);
        
        $sqlcf = "SELECT * FROM users WHERE cf = '$codice_fiscale'";
        $resultcf = mysqli_query($db, $sqlcf);
        $countrowcf = mysqli_num_rows($resultcf);

        if($countrow === 1) {
            $error .= '<p class = "error"> Questa email è già registrata!</p>';
        }
        else if($countrowcf === 1){
            $error .= '<p class = "error"> Questo utente è già registrato!</p>';
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
            $insertdb = "INSERT INTO users (nome, cognome, cf, data_nascita, email, password) VALUES ('$nome', '$cognome', '$codice_fiscale', '$data_nascita', '$email', '$password_encrypt')";
            $result = mysqli_query($db, $insertdb);

            if($result) {
                $error .= '<p class = "success"> Registrazione avvenuto con successo!</p>';
            }
            else {
                $error .= '<p> Qualcosa è andato storto!</p>';
            }
        }
        mysqli_close($db);
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/signup.css"/>
        
        
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
        <script src='../scripts/signup.js' type="text/javascript" defer></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    </head>

    <body>
    <header>
    <div>
        <strong class="titolo">DATAJAZZ</strong>
      </div>

      <nav class="nav">
        <img id="logo" src="../img/logo.png" />
        <div id="links">
          <a class="nav-link" href="./home.php">HOME</a>
          <a class="nav-link" href="./transcriptions.php">TRANSCRIPTIONS</a>
          <a class="nav-link" href="./subup.php">SUBSCRIBE</a>
          <a class="nav-link" href="./login.php">LOGIN</a>
        </div>

      </nav>

    </header>

    <section>
    <div class="signup_container">
            <h2>SIGN UP!</h2>
            <p>Iscriviti, è gratis!</p>

            <form action="" method="post" >

                <div class="input_container">
                    <i class="material-icons"> badge </i>
                    <input
                        type="text"
                        placeholder="Nome"
                        name="nome"
                        id="nome"
                        text-transform="capitalize"
                        class="input_field"
                        required>
                    <script>
                        document.getElementById("nome").addEventListener("keypress", function(e) {
                            if(this.selectionStart == 0) {
                            // uppercase first letter
                            forceKeyPressUppercase(e);
                            }
                        }, false);
                    </script>
                </div>
                <div class="input_container">
                    <i class="material-icons"> badge </i>
                    <input
                        type="text"
                        placeholder="Cognome"
                        name="cognome"
                        id="cognome"
                        class="input_field"
                        required>
                    <script>
                        document.getElementById("cognome").addEventListener("keypress", function(e) {
                            if(this.selectionStart == 0) {
                            // uppercase first letter
                            forceKeyPressUppercase(e);
                            } 
                        }, false);
                    </script>
                </div>
                <div class="input_container">
                    <i class="material-icons"> alternate_email </i>
                    <input
                        type="email"
                        placeholder="Email"
                        name="email"
                        class="input_field"
                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> code </i>
                    <input
                        type="text" 
                        placeholder="Codice fiscale"
                        name="cf"
                        id="cf"
                        maxlength="16"
                        class="input_field"
                        required
                        onkeyup="MakeMeUpper(this)">
                </div>
                <div class="input_container">
                    <i class="material-icons"> event </i>
                    <input 
                        type="text"
                        placeholder="Data di nascita"
                        onfocus="(this.type='date')"
                        name="data_nascita"
                        class="input_field"
                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> lock </i>
                    <input
                        type="password"
                        placeholder="Password"
                        id="password"
                        maxlength="16"
                        name="password"
                        class="input_field"
                        required>
                    <i class="far fa-eye" id="togglePassword"></i>
                    </div>
                <div class="input_container">
                    <i class="material-icons"> lock </i>
                    <input
                        type="password"
                        placeholder="Conferma password"
                        id="confirm_password"
                        maxlength="16"
                        name="confirm_password"
                        class="input_field"
                        required>
                    <i class="far fa-eye" id="toggleConfirmPassword"></i>
                </div>
                
                <div class="input_container">
                    <input type="submit" name="submit" class="btn" value="Submit">
                </div>

                <?php
                echo "$error";
                ?>

                <p>Hai già un account? Effettua il <a href="login.php">login</a>!</p>
            </form>
        </div>

        

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
</html>