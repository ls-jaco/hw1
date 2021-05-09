<?php
session_start();

if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== true) {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="style.css" />
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="/img/logopiccolissimo.png" />
  <title>Datajazz</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">

  <script src="contents.js" defer></script>
  <script src="script.js" defer></script>
</head>

<body>

  <header class="page-header">

    <div>
      <strong class="titolo">DATAJAZZ</strong>
    </div>

    <nav class="nav">
      <img id="logo" src="img/logo.png" />
      <div id="links">
        <a class="nav-link">HOME</a>
        <a class="nav-link">ABOUT</a>
        <a class="nav-link">TRANSCRIPTIONS</a>
        <a class="nav-link">LOGIN</a>
      </div>


      <div class="mobile__nav">

        <!-- navigation links, hidden by default -->
        <div id="mobile__links">
          <a href="#">HOME</a>
          <a href="#">ABOUT</a>
          <a href="#">TRANSCRIPTIONS</a>
          <a href="#">LOGIN</a>
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

    <div class="contenuti">
      <div id="b1">at.jpg" />
        <button class="link-button">Transcription</button>
        <p>Controlla la lista delle trascrizioni</p>
        <img src="" />
      </div>
      <div id="b2">
        <img src="img/abd.jpg" />
        <button class="link-button">Subscribe</button>
        <p>Iscriviti al nostro sito per restare sempre aggiornato!</p>
      </div>
    </div>

  </section>

  <footer>

    <div>Matteo Jacopo Schembri</div>
    <div>1000012121</div>

  </footer>
</body>

</html>