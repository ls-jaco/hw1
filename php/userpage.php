<?php
    require_once "dbconfig.php";
    require_once "session.php";

    $error = '';

    $date = date('Y-m-d');

    if(isset($_SESSION['user'])){
        $query = "SELECT nome FROM users WHERE email= '".$_SESSION['user']."'";     
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
     }
    
    $user = "SELECT * FROM users WHERE email = '".$_SESSION['user']."'";     
    $user_result = mysqli_query($db, $user);
    $user_fetch = mysqli_fetch_assoc($user_result);

    if($user_fetch['tipo'] == "Premium"){
      $abbonamenti_query = "SELECT * FROM abbonamenti WHERE cf_utente = '".$user_fetch['cf']."' && stato = 'Attivo'";
      $abbonamenti_result = mysqli_query($db, $abbonamenti_query);
      $abbonamenti_fetch = mysqli_fetch_assoc($abbonamenti_result);
    }


    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

      $disdici_query = "UPDATE users SET tipo = 'Standard' WHERE cf = '".$user_fetch['cf']."'";
      $disdici_result = mysqli_query($db, $disdici_query);

      $disdici_query_abb = "UPDATE abbonamenti SET stato = 'Scaduto' WHERE cf_utente = '".$user_fetch['cf']."'";
      $disdici_result_abb = mysqli_query($db, $disdici_query_abb);

      $disdici_set_data_query = "UPDATE abbonamenti SET data_scadenza = '".$date."' WHERE cf_utente = '".$user_fetch['cf']."'";
      $disdici_set_data_result = mysqli_query($db, $disdici_set_data_query);

      $disdici_giorni_query = "UPDATE abbonamenti SET giorni_rimanenti = '0' WHERE cf_utente = '".$user_fetch['cf']."'";
      $disdici_giorni_result = mysqli_query($db, $disdici_giorni_query);

      $error .= "Abbonamento disdetto con successo";
    }

?>


<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/userpage.css"/>
        
        <script src='../scripts/userpage.js' type="text/javascript" defer></script>
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

      </nav>

    </header>

    <section>
    
    <?php

    echo '<div class="output_container">
          <i class="material-icons"> account_circle </i>
          <p class="output_field">'. $user_fetch['nome'].' '. $user_fetch['cognome'].' </p>
          </div>';

    echo '<div class="output_container">
          <i class="material-icons"> star_rate </i>
          <p class="output_field">Account: '. $user_fetch['tipo'].' </p>
          </div>';

    if($user_fetch['tipo'] == 'Premium'){
    echo '<div class="output_container">
         <i class="material-icons"> event </i>
         <p class="output_field"> Giorni rimanenti: '. $abbonamenti_fetch['giorni_rimanenti'].' </p>
         </div>';
    }

    echo '<div class="output_container">
         <i class="material-icons"> download </i>
         <a class="output_field" href="">Downloads</a>
         </div>';

    echo '<div class="output_container">
         <i class="material-icons"> lock </i>
         <a class="output_field" href="changepassword-php">Modifica password</a>
         </div>';
    // echo '<p>Modifica metodi di pagamento</p>';

    if($user_fetch['tipo'] == 'Premium'){
    echo '<div class="output_container">
         <i class="material-icons"> delete_forever </i>
         <a class="output_field" <button id="myBtn">Disdici abbonamento</button></a>
         </div>';
    // echo '<button id="myBtn">Disdici abbonamento</button>';
    }
    echo'<div class="output_container">
         <i class="material-icons"> logout </i>
         <a class="output_field" href="./logout.php">Logout</a>
         </div>';
    ?>

<div id="myModal" class="modal">

<div class="modal-content">
  <span class="close">&times;</span>
  <p>Vuoi veramente disdire l'abbonamento?</p>
  <div class="output_container">
    <form action="" method="post">
      <input type="submit" name="submit" class="btn" value="Disdici">
    </form>
    <?php
      $error;
    ?>
  </div>
</div>
</div>

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
    
</html>