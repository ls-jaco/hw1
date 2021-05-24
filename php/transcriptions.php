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

    //Selezioniamo tutti gli elementi dalla tabella "solos" del database
    $solos_query = "SELECT * FROM solos";     
    $solos_result = mysqli_query($db, $solos_query);
    $solos_row = mysqli_num_rows($solos_result);


    $client_id = 'a3d490c5fd084afe820e482860e3c59e';
    $client_secret = '1bee37a7a4f3468f9e0c1f18ec16ad27';
    
    //Inizializziamo cURL
    $ch = curl_init();
    //Impostiamo la URL della risorsa remota da scaricare
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token' );
    //Vogliamo ritornato il valore, anziché un boolean (default)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //Eseguiamo la POST
    curl_setopt($ch, CURLOPT_POST, 1);
    //Settiamo body ed header della POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));
    $token=json_decode(curl_exec($ch), true);
    curl_close($ch);

  
      foreach($solos_result as $res) {
      // var_dump($res['titolo_traccia']);

      $title = rawurlencode($res['titolo_traccia']);
      $encoded_title = strtolower($title);

      //Query di ricerca
      $url = 'https://api.spotify.com/v1/search?q='.$encoded_title.'&type=track&market=IT&limit=10';
      $url_list[] = $url;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //Impostiamo il token
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
      $query_result = curl_exec($ch);
      curl_close($ch);
      
      $search_decode = json_decode($query_result, true);
      $tracks = $search_decode['tracks'];

      // if(!isset($search_decode)){
      //   $preview_url_list[$res['id_solo']] = "Not found";
      // }
      
      $items = $tracks["items"];

      foreach($items as $item){
        if($item['album']['name'] == $res['album']){
          $preview_url_list[$res['id_solo']] = $item['preview_url'] ?? "Preview non disponibile.";
          break;
        }
      }
    }
    

    //Download

    // if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    //   $id = $_POST['download'];
    //   $filename = "'".$solos_result['titolo_traccia']. "'.pdf";

    //   $download_query = "SELECT pdf FROM solos WHERE id_solo = '".$id."'";
    //   $download_result = mysqli_query($db, $download_query);
    //   $download_fetch = mysqli_fetch_array($download_result);
    //   // var_dump($download_query);
    //   // var_dump($download_result);
    //   // var_dump($download_fetch);
    //   // var_dump($filename);
    //   // echo $download_fetch;
    //   // clearstatcache();
    //   while($download_fetch){
    //     $filedata = $_GET['pdf'];
    //     header('Content-Description: File Transfer');
    //     header("Content-length: ".strlen($filedata));
    //     header("Content-type: application/pdf");
    //     header("Content-disposition: download; filename=$filename");
    //     header('Content-Transfer-Encoding: binary');
    //     readfile($filedata, true);
    //     die();
    //   }

    // }
?>




<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Sign Up</title>
        <link rel="stylesheet" href="../style/transcriptions.css"/>
        <script src='../scripts/transcriptions.js' type="text/javascript" defer></script>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
      >

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

      </nav>

    </header>

    <section>

    <?php
      if(isset($_SESSION['user'])) : 
    ?>
    


      <div class = "table_container">

      <div id="ricerca">
          <input type="text" id="searchbar" placeholder="Search..." onkeyup="search()">
      </div>


        <table id=table>
        
          <tr class="header">
            <th>Titolo</th>
            <th>Album</th>
            <th>Strumento</th>
            <th>Preview del brano</th>
            <th>Download</th>
          </tr>

            <?php

              foreach($solos_result as $index=> $res) {

                echo '<tr>';
                echo '<td  id="livesearch" >'.$res['titolo_traccia'].'</td>';
                echo '<td>'.$res['album'].'</td>';
                echo '<td>'.$res['strumento'].'</td>';
                echo '<td> <audio controls>
                      <source src = "'.$preview_url_list[$res['id_solo']].'" type="audio/mp3">
                     </audio></td>';
                echo '<td> 
                        <form action="'.$_SERVER['PHP_SELF'].'" method="POST">
                        <button id="downlaod" type="submit" value= "'.$res['id_solo'].'" name="download" class="material-icons"> download </button>
                        </form>
                      </td>';
                echo '</tr>';
              }
            ?>

        </table>

      </div>

    <?php
      else : 
    ?>

      <div>
        <h1>OPS!</h1>
        <p>Prima di accedere a questa sezione devi effettuare il <a href="login.php">login</a>!</p>
        <p>Non hai un account? <a href="signup.php">Iscriviti</a>!</p>
      </div>
    
    <?php
      endif;
    ?>

    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
    </body>
    
</html>