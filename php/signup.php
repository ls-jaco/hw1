<?php
    require_once "dbconfiguration.php";
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
            echo "<p>$insertdb</p>";
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
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/signup.css"/>
        <script src='../scripts/signup.js' type="text/javascript" defer></script>
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
          <a class="nav-link">TRANSCRIPTIONS</a>
          <a class="nav-link">SUBSCRIBE</a>
          <a class="nav-link" href="./login.php">LOGIN</a>
        </div>


        <div class="mobile__nav">

          <!-- navigation links, hidden by default -->
          <div id="mobile__links">
            <a href="./home.php">HOME</a>
            <a href="#">TRANSCRIPTIONS</a>
            <a href="#">SUBSCRIBE</a>
            <a href="./login.php">LOGIN</a>
          </div>

          <!-- Menu and Bar Icon -->
          <a href="#" class="icon__nav" onclick="showMenu()">
            <i class="fa fa-bars"></i>
          </a>
        </div>
      </nav>

    </header>

    <section>
    <div class="signup_container">
            <h2>SIGN UP</h2>
            <p>Iscriviti, è gratis!</p>

            <form action="" method="post">

                <div class="form_class">
                    <label>Nome</label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        text-transform="capitalize"
                        class="form_control"
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
                <div class="form_class">
                    <label>Cognome</label>
                    <input
                        type="text"
                        name="cognome"
                        id="cognome"
                        class="form_control"
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
                <div class="form_class">
                    <label>Email</label>
                    <input type="email" name="email" class="form_control" required>
                </div>
                <div class="form_class">
                    <label>Codice Fiscale</label>
                    <input
                        type="text" 
                        name="cf"
                        id="cf"
                        maxlength="16"
                        class="form_control"
                        required
                        onkeyup="MakeMeUpper(this)">
                </div>
                <div class="form_class">
                    <label>Data di nascita</label>
                    <input type="date" name="data_nascita" class="form_control" required>
                </div>
                <div class="form_class">
                    <label>Password</label>
                    <input type="password" maxlength="16" name="password" class="form_control" required>
                </div>
                <div class="form_class">
                    <label>Confirm Password</label>
                    <input type="password" maxlength="16" name="confirm_password" class="form_control" required>
                </div>
                <div class="form_class">
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