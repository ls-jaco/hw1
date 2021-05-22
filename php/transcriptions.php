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
    $solos_fetch = mysqli_fetch_assoc($solos_result);

    //Selezioniamo tutti gli elementi dalla tabella "tracce" del database
    $tracce_query = "SELECT * FROM tracce";
    $tracce_result = mysqli_query($db, $tracce_query);
    $tracce_fetch = mysqli_fetch_assoc($tracce_result);



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


    // foreach($solos_result as $solos_fetch) 

    // for($i = 0; $i< count($solos_fetch);){

    //   $tracce_query = "SELECT * FROM tracce WHERE album = '".$solos_fetch['album']."'";
    //   $tracce_result = mysqli_query($db, $tracce_query);
    //   $tracce_fetch = mysqli_fetch_array($tracce_result);



    //   $title = rawurlencode($solos_fetch['titolo_traccia']);
    //   $encoded_title = strtolower($title);

    //   //Query di ricerca
    //   $url = 'https://api.spotify.com/v1/search?q='.$encoded_title.'&type=track&market=IT&limit=10';
    //   $ch = curl_init();
    //   curl_setopt($ch, CURLOPT_URL, $url);
    //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //   //Impostiamo il token
    //   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'])); 
    //   $query_result = curl_exec($ch);
    //   curl_close($ch);

      
    //   $search_decode = json_decode($query_result, true);
    //   $tracks = $search_decode["tracks"];
    //   $items = $tracks["items"];

    //     for($i = 0; $i < count($items); $i++){
    //       $iter_items = $items[$i];
    //       $artists = $iter_items['artists'];
    //       $album = $iter_items['album'];
    //       $album_name = $album["name"];

    //         for($j = 0; $j < count($artists); $j++){
    //           $iter_artists = $artists[$j];
    //           $artist_name = $iter_artists['name'];
              
    //           if(($iter_items["name"] == $solos_fetch["titolo_traccia"]) && ($album_name == $tracce_fetch['album'])){
    //             // $prova = $iter_items['preview_url'];
    //             $prova = $artist_name;
    //             return $prova;

    //             // var_dump($solos_fetch["titolo_traccia"]);
    //             //  echo $tracce_fetch["album"];
    //           }

    //         }
    //     }

    //   }
    

    //Download

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

      $id = $_POST['download'];
      $filename = "Solo.pdf";

      $download_query = "SELECT pdf FROM solos WHERE id = '".$id."'";
      $download_result = mysqli_query($db, $download_query);
      $download_fetch = mysqli_fetch_assoc($download_result);
      while($download_fetch){
        $filedata = $download_fetch['pdf'];
        header("Content-length: ".strlen($filedata));
        header("Content-type: application/pdf");
        header("Content-disposition: download; filename = $filename");
        echo $filedata; 
      }
    }

    // }
    //Sbagliato
  //   if (isset($_POST['download'])) {
  //     $file = $_POST['download'];
  //     try {
  //       $sql = "SELECT * FROM `solos` WHERE `pdf` = '".$file."'";
  //         $results = $sql->query($db);

  //         while ($row = $results->fetch()) {
  //             $filename = $row['filename'];
  //             $mimetype = $row['mimetype'];
  //             $filedata = $row['filedata'];
  //             header("Content-length: ".strlen($filedata));
  //             header("Content-type: $mimetype");
  //             header("Content-disposition: download; filename=$filename"); //disposition of download forces a download
  //             echo $filedata; 
  //             // die();
  //         } //of While
  //     } //try
  //     catch (PDOException $e) {
  //         $error = '<br>Database ERROR fetching requested file.';
  //         echo $error;
  //         die();    
  //     } //catch
  // } //isset

    
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

    <?php
      if(isset($_SESSION['user'])) : 
    ?>
    
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

                echo '<tr>';
                echo '<td> '.$solos_fetch['titolo_traccia'].'</td>';
                echo '<td> '.$solos_fetch['album'].'</td>';
                echo '<td> '.$solos_fetch['strumento'].'</td>';
                echo '<td>' .$url.'</td>';
                echo '<td> 
                        <form action = "'.$_SERVER['PHP_SELF'].'" method = "POST">
                        <button type="submit" value= "'.$solos_fetch['id_solo'].'" name="download" class="material-icons"> download </button>
                        </form>
                      </td>';
                $error = $download_query;
                echo $error;
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