<?php
    require_once "dbconfig.php";
    require_once "session.php";

    $error = '';

    if(isset($_SESSION['user'])){
        $query = "SELECT nome FROM users WHERE email= '".$_SESSION['user']."'";     
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
     }
    
    $user = "SELECT * FROM users WHERE email = '".$_SESSION['user']."'";     
    $user_result = mysqli_query($db, $user);
    $user_fetch = mysqli_fetch_assoc($user_result);


    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        $old_password_encrypt = md5("$old_password");
        $new_password_encrypt = md5("$new_password");
        $confirm_password_encrypt = md5("$confirm_password");

        if($old_password_encrypt != $user_fetch['password']){
            $error .= "Password attuale errata. Riprovare.";
        }
        else {
            if($new_password_encrypt != $confirm_password_encrypt){
                $error .= "I due campi della nuova password non coincidono. Riprovare.";
            }
            else{

                $change_pw_query= "UPDATE users SET password = '".$new_password_encrypt."' WHERE email= '".$_SESSION['user']."'";
                $change_pw_result = mysqli_query($db, $change_pw_query);

                if($change_pw_result) {
                    $error .= '<p class = "success"> Password cambiata con successo!</p>';
                }
                else {
                    $error .= '<p> Qualcosa è andato storto!</p>';
                }
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
          <?php if(!isset($_SESSION['user'])) :?>
          <a class="nav-link" href="./login.php">LOGIN</a>
          <?php
            else :
            {
              $userpage = "./sub.php";
              echo "<a class= 'nav-link' href='$userpage'> ". $row['nome']." </a>";
            }
            endif;
            ?>
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
            

    <h2>Modifica password</h2>
    <form action="" method="post" >
        <div class="input_container">
        <i class="material-icons"> delete_sweep </i>
            <input
            type="password"
            placeholder="Password attuale"
            maxlength="16"
            name="old_password"
            class="input_field"
            required>
        </div>
        <div class="input_container">
        <i class="far fa-eye" id="toggleConfirmPassword"></i>
        <i class="material-icons"> lock </i>
            <input
            type="password"
            placeholder="Nuova password"
            maxlength="16"
            name="new_password"
            class="input_field"
            required>
        </div>
        <div class="input_container">
        <i class="far fa-eye" id="toggleConfirmPassword"></i>
        <i class="material-icons"> lock </i>
            <input
            type="password"
            placeholder="Conferma password"
            maxlength="16"
            name="confirm_password"
            class="input_field"
            required>
        </div>
        <div class="input_container">
            <input type="submit" name="submit" class="btn" value="Submit">
        </div>
        <?php
        
        echo "$error";

        ?>
    </form>

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
    
</html>