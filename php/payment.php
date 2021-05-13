<?php
    require_once "dbconfig.php";
    require_once "session.php";

    $error = '';

    if(isset($_SESSION['user'])){
        $query = "SELECT nome FROM users WHERE email= '".$_SESSION['user']."'";     
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
     }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $n_carta = trim($_POST['n_carta']);
        $proprietario = trim($_POST['proprietario']);
        $cvv = trim ($_POST['CVV']);
        $data_scadenza_carta = trim($_POST['data_scadenza_carta']);
        $cf_utente = trim($_POST['cf_utente']);

        $cvv_encrypt = md5("$cvv");

        // $sql_paynum = "SELECT * FROM metodipagamento WHERE n_carta = '$n_carta";
        // $result_paynum = mysqli_query($db, $sql_paynum);
        // $countrow_paynum = mysqli_num_rows($result_paynum);

        // $sql_payusr = "SELECT * FROM metodipagamento WHERE cf_utente = '$cf_utente";
        // $result_payusr = mysqli_query($db, $sql_payusr);
        // $countrow_payusr = mysqli_num_rows($result_payusr);

        // if(($countrownum === 1) && ($countrow_payusr === 1)) {
        //     $error .= '<p>Questa carta è già registrata per questo utente!</p>';
        // }

        //Aggiungere tutti gli errori del caso

        if(empty($error)) {
            $insertpaymemt = "INSERT INTO metodipagamento (n_carta, proprietario, CVV, data_scadenza_carta, cf_utente) VALUES ('$n_carta', '$proprietario', '$cvv_encrypt', '$data_scadenza_carta', '$cf_utente')";
            $result = mysqli_query($db, $insertpaymemt);

            if($result) {
                $error .= '<p class = "success"> Registrazione del metodo di pagamento avvenuta con successo!</p>';
                header('Location: subup.php');
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
        <link rel="stylesheet" href="../style/subup.css"/>
        
        
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
        <!-- <script src='../scripts/signup.js' type="text/javascript" defer></script> -->
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
          <a class="nav-link">TRANSCRIPTIONS</a>
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

    <?php else : ?>
        
    <div class="sub_container">
            <h2>SUBSCRIBE!</h2>
            <p>Abbonati, è conveniente!</p>

            <form action="" method="post">

                <div class="input_container">
                    <i class="material-icons"> badge </i>
                    <input
                        type="text"
                        name="n_carta"
                        id="n_carta"
                        maxlength="16"
                        class="input_field"
                        placeholder="Numero carta"
                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> credit_card </i>
                    <input
                        type="text"
                        name="proprietario"
                        id="proprietario"
                        class="input_field"
                        placeholder="Proprietario"
                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> lock </i>
                    <input
                        type="password"
                        name="CVV"
                        maxlength="3"
                        class="input_field"
                        placeholder="CVV"

                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> event </i>
                    <input
                        type="text" 
                        onfocus="(this.type='date')"
                        name="data_scadenza_carta"
                        class="input_field"
                        id="data_scadenza_carta"
                        placeholder="Data di scadenza"
                        required>
                </div>
                <div class="input_container">
                    <i class="material-icons"> code </i>
                    <input
                        type="text" 
                        name="cf_utente"
                        id="cf_utente"
                        maxlength="16"
                        class="input_field"
                        required
                        placeholder="Codice fiscale"
                        >
                </div>
                <div class="input_container">
                    <input type="submit" name="submit" class="btn" value="Submit">
                </div>

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