<?php

use function PHPSTORM_META\map;

    require_once "dbconfig.php";
    require_once "session.php";

    $error = '';

    if(isset($_SESSION['user'])){
      $query = "SELECT nome FROM users WHERE email = '".$_SESSION['user']."'";     
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_array($result);
     }

    if(isset($_SESSION['user'])){
      $user = "SELECT * FROM users WHERE email = '".$_SESSION['user']."'";     
      $user_result = mysqli_query($db, $user);
      $user_row = mysqli_fetch_assoc($user_result);
   }
    
    if(isset($_SESSION['user'])){
      $payment = "SELECT * FROM metodipagamento WHERE cf_utente = '".$user_row['cf']."'";
      $paymentresult = mysqli_query($db, $payment);
      $paymentrow = mysqli_num_rows($paymentresult);
      if($paymentrow != 0){
      $checkresult = true;
      }
      else $checkresult = false;
    }

    if($checkresult == true) {
      $creditcard = mysqli_fetch_assoc($paymentresult);
    }
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/subup.css"/>
        
        
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
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
              $userpage = "./userpage.php";
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

    <?php
        if(!isset($_SESSION['user'])) : 
    ?>
    <div class="sub_error">
        <h1>OPS!</h1>
        <h2>Qualcosa non va!</h2>
        <p>Prima di abbonarti devi effetuare il <a href="./login.php">login</a>!</p>
        <p>Non hai un account? <a href="./signup.php">Registrati</a>!</p>
    </div>

    <?php elseif($checkresult == false) : ?>

      <div>
        <h1>SUBSCRIBE</h1>
        <p>Prima di abbonarti devi aggiungere un<a href="payment.php"> metodo di pagamento</a>!</p>
      </div>

    <?php elseif($checkresult == true) : ?>

            <h2>SUBSCRIBE!</h2>
            <p>Abbonati, è conveniente!</p>

            <form action="" method="post">
              <?php

                foreach($paymentresult as $creditcard)
                echo '<div class="input_container">
                      <input type="radio" class="input_check" name="creditcard" value='.$creditcard["n_carta"].' required"> '.$creditcard['n_carta'].'</input>
                      </div>';
              ?>


              <div class="input_container">
                      <input type="submit" name="submit" class="btn" value="Submit">
              </div>

              <?php
                echo "$error";
              ?>

            </form>

            <p>Registra un altro<a href="payment.php"> metodo di pagamento</a>.</p>


                <?php
                echo "$error";
                ?>
            </form>
        </div>

        <?php endif; ?>

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
    
</html>