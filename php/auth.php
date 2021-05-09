<!-- User check, usato per non dover richiedere il login -->
<?php
    require_once 'dbconfig.php';
    session_start();

    // Se c'è una sessione esistente la ritorniamo, altrimenti return 0
    function checkAuth() {
        if(isset($_SESSION["user_id"])) {
            return $_SESSION("user_id");
        }
        else
            return 0;
    }
?>