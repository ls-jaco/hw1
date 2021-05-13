<?php
    require_once "dbconfig.php";
    require_once "session.php";

    $error = '';

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $password_encrypt = md5($password);

        if(empty($email)) {
            $error .= '<p class = "error" Immettere un email! <!p>';
        }

        if(empty($password)) {
            $error .= '<p class = "error" Immettere una password! <!p>';
        }

        if(empty($error)) {
            $query = "SELECT * FROM users WHERE email = '$email' AND password ='$password_encrypt'";
            $result = mysqli_query($db, $query);
            $countResult = mysqli_num_rows($result);
            if($countResult == 1){
                session_start();
                $_SESSION['user'] = $email;
                header('Location: home.php');
                exit;
            }
            else{
                $error .= '<p class="error"> Email o Password errati. Riprovare.</p>';
                }
        }
        mysqli_close($db);
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Login</title>
        <link  rel="stylesheet" href="../style/login.css"> 

        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Chango&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
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
    <div class="container_login">
            <h2>Login</h2>

            <form action="" method="post">
                <div class="form_class">
                    <label>Email</label>
                    <input type="email" name="email" class ="form_control" required>
                </div>
                <div class="form_class">
                    <label>Password</label>
                    <input type="password" name="password" class ="form_control" required>
                </div>
                <div class="form_class">
                    <input type="submit" name="submit" class ="btn" value="submit">
                </div>

                <?php
                echo "$error";
                ?>

                <p>Non hai un account? <a href="signup.php">Registrati</a>!</p>
                

            </form>
        </div>
    </section>

    <footer>

      <div>Matteo Jacopo Schembri</div>
      <div>1000012121</div>

    </footer>
       
    </body>

</html>