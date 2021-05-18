<?php
    require_once "dbconfig.php";
    require_once "session.php";

    // Impostiamo l'header della risposta
    //header('Content-Type: application/json');

    if(isset($_SESSION['user'])){
      $query = "SELECT nome FROM users WHERE email= '".$_SESSION['user']."'";     
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_array($result);
    }

    $solos_query = "SELECT * FROM solos";     
    $solos_result = mysqli_query($db, $solos_query);
    $solos_fetch = mysqli_fetch_assoc($solos_result);

    function spotify(){

      $client_id = 'a3d490c5fd084afe820e482860e3c59e';
      $client_secret = '1bee37a7a4f3468f9e0c1f18ec16ad27';
      
      //Inizializziamo cURL
      $ch = curl_init();
      //Impostiamo la URL della risorsa remota da scaricare
      curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
      //Vogliamo ritornato il valore, anziché un boolean (default)
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //Eseguiamo la POST
      curl_setopt($ch, CURLOPT_POST, 1);
      //Settiamo body ed header della POST
      curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));
      $token=json_decode(curl_exec($ch), true);
      curl_close($ch);
    }


?>





<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/transcriptions.css"/>
        
        
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

            <div id="ricerca">
              <div id="container">
                <input type="text" id="searchbar" placeholder="Search...">
              </div>
            </div>

            <div class = "table_container">
            
              <table>
              
                <tr>
                  <th>Titolo</th>
                  <th>Album</th>
                  <th>Strumento</th>
                  <th>Preview del brano</th>
                  <th>Download</th>
                </tr>

                  <?php
                    foreach($solos_result as $solos_fetch) {

                      $title = rawurlencode($solos_fetch['titolo_traccia']);
                      $encoded_title = strtolower($title);

                      //Query di ricerca
                      $url = 'https://api.spotify.com/v1/search?q='.$encoded_title.'&type=track&market=IT&limit=1';
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      //Impostiamo il token
                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token']));
                      $query_result = curl_exec($ch);
                      curl_close($ch);

                      

                      echo '<tr>';
                      echo '<td> '.$solos_fetch['titolo_traccia'].'</td>';
                      echo '<td> '.$solos_fetch['album'].'</td>';
                      echo '<td> '.$solos_fetch['strumento'].'</td>';
                      echo '<td>' .$url.'</td>';
                      echo '<td> <i class="material-icons"> download </i></td>';
                      echo '</tr>';
                    }
                  ?>



              
              </table>
            
            
            </div>

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
    
</html>