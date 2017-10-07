<?php
    include("funzioni.php");
    checkHttps();
    $invaliduser = false;
    $invalidpass = false;
    $dupuser = false;
    $success=false;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["csrftok"])) {
        if(csrfProtect($_POST["csrftok"])) {
            if (!empty($_POST["username"]) && !empty($_POST["password"])) {
                $reg1 = '/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
                $reg2 = '/^(?:[0-9]+[a-zA-Z]|[a-zA-Z]+[0-9])[A-Za-z0-9]*$/';
                include("database.php");
                $user = mysqli_real_escape_string($conn, $_POST["username"]);
                $pass = mysqli_real_escape_string($conn, $_POST["password"]);
                $invaliduser = !preg_match($reg1, $user);
                $invalidpass = !preg_match($reg2, $pass);
                if (!$invaliduser && !$invalidpass) {
                    $pass = sha1($pass);
                    $result = mysqli_query($conn, "INSERT INTO `user`(`username`, `password`) VALUES (\"" . $user . "\",\"" . $pass . "\")");
                    if ($result) {
                        $dupuser = false;
                        $success = true;
                        userLogin($_POST["username"], $_POST["password"]); //auto login after registration
                    } else {
                        if (mysqli_errno($conn) == 1062) {//duplicate user
                            $dupuser = true;
                        } else {
                            die("Errore generale nel database!");
                        }
                    }
                }
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

        <title>Sing Up</title>

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
                  <h2>Sign Up</h2>
                  <?php if($success){?>
                      <p style="color:green"><b><h3>Success</h3></b></p>
                      <img style="width:100%;height: 100%;margin: 0 auto" src="img/ok.png" alt="Ok"/>
                      <p><a href="index.php" class="mybutton buttonb">Go to Home</a></p>
                  <?php }else{ ?>
                  <form action="signup.php" onsubmit="return checkValue();" method="post">
                      <p><b><h3>Username/Email:</h3></b></p>
                      <input type="text" autocomplete="off"  id="username" name="username" value="" placeholder="mail@example.com">

                      <p class="errmess" id="invusername"><b>Username invalid</b></p>
                      <p class="errmess" id="dupusername"><b>Username already exist</b></p>
                      <p><b><h3>Password:</h3></b></p>
                      <input type="password" autocomplete="off" id="password" name="password" value="" placeholder="example1">
                      <input type="hidden" name="csrftok" value=<?php echo csrfProtect();?>>
                      <br>
                      <p class="errmess" id="invpassword"><b>Password Invalid</b></p>
                      <p><b>Username must be a valid email</b></p>
                      <p><b>Password must contain at least:<br> 1 letter and 1 number</b></p>
                      <br>
                      <button class="mybutton buttong" type="submit" name="button">Sign Up</button>
                  </form>
                  <script type="text/javascript" src="js/messagehandler.js"></script>
                  <?php
                      if($invaliduser){echo"<script type=\"text/javascript\">showMessage(\"invaliduser\");</script>";}
                      if($invalidpass){echo"<script type=\"text/javascript\">showMessage(\"invalidpass\");</script>";}
                      if($dupuser){echo"<script type=\"text/javascript\">showMessage(\"dupuser\");</script>";}
                    }
                  ?>
                </div>
         </div>
        </div>

        <script type="text/javascript" src="js/checklogin.js"></script>

    </body>

</html>
