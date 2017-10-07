<?php
    include("funzioni.php");
    checkHttps();
    $authfailed=false;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["csrftok"])) {
        if(csrfProtect($_POST["csrftok"])) {
            if (userLogin($_POST["username"], $_POST["password"])) {
                header("Location: index.php");
                exit();
            } else {
                $authfailed = true;
            }
        }else{
            die("Possibile attacco CSRF");
        }
    }
?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">

        <title>Login</title>

        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <noscript>
            <style type="text/css">
                #load{
                    background-image: url("img/errorjs.png");
                }
            </style>
            <div id="load"></div>
        </noscript>
        <script type="text/javascript">
            if(navigator.cookieEnabled===false){
                document.write("<h2>You must enable cookie for use this website!</h2>");
                window.stop();
                if ($.browser.msie) {document.execCommand("Stop");};
            }
        </script>
        <script src="js/jquery.min.js"></script>

    </head>

    <body>

        <div class="container">

        <header class="myheader">
            <div class=logo>
              <h1><img style='height: 100%; width: 100%;' src="img/logo.png" alt="La Baia"><h1>
            </div>
        </header>
           <aside class="menulat">
              		<p class=menutit>Menu:</p>

                              <p><a href="index.php" class="mybutton buttong">Home</a></p>

                              <p><a href="login.php" class="mybutton buttonr">Login</a></p>

                              <p><a href="signup.php" class="mybutton buttonb">Sign Up</a></p>
            </aside>
        <div class="mymaincontent">
                <div class="loginform">
                  <h2>Login</h2>
                  <form action="login.php" method="post">
                      <p><b><h3>Username/Email:</h3></b></p>
                      <input type="text" name="username" value="">
                      <p><b><h3>Password:</h3></b></p>
                      <input type="password" name="password" value="">
                      <input type="hidden" name="csrftok" value=<?php echo csrfProtect();?>>
                      <br>
                      <?php
                      if($authfailed) {
                          echo "<p class=\"errmess\" style='display: block'>Autenticazione Fallita</p>";
                      }
                      ?>
                      <br>
                      <button class="mybutton buttong" type="submit" name="button">Login</button>
                  </form>
                </div>
         </div>
        </div>
    </body>

</html>
