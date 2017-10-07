<?php
include("funzioni.php");
$auth=userLogged();
$formatfailed=false;
$bidfailed=true;
if($auth){
    checkHttps();
    $bidfailed=!amIBest();
    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["csrftok"])) {
        if(csrfProtect($_POST["csrftok"])) {
            if (!empty($_POST["thr"])) {
                $myfloat = $_POST["thr"];
                if (preg_match("/^\d+([\,]\d+)*([\.]\d+)?$/", $myfloat)) {
                    $myfloat = floatval(str_replace(",", ".", $myfloat));
                    $bidfailed = !checkTHR($myfloat);
                } else {
                    $formatfailed = true;
                }

            } else {
                $authfailed = true;
            }
        }else{
            die("Possibile attacco CSRF");
        }
    }
}

?>
<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">

        <title>La Baia</title>

        <link rel="stylesheet" type="text/css" href="css/style.css">
        <noscript>
            <style type="text/css">
                #load{
                    background-image: url("img/errorjs.png");
                }
            </style>
        </noscript>
        <script type="text/javascript">
            if(navigator.cookieEnabled===false){
                document.write("<h2>You must enable cookie for use this website!</h2>");
                window.stop();
                if ($.browser.msie) {document.execCommand("Stop");};
            }
        </script>
        <script src="js/jquery.min.js"></script>
        <?php
        if($auth) {
            ?>
            <style type="text/css">
                @media (min-width: 1200px){
                    .split2 div{
                        width: 28%;
                        display: block;
                    }
                }
                @media (max-width: 1200px){
                    .split2 div{
                        margin:3px;
                        margin-top: 10px;
                        width: 95%;
                    }
                    .objimg{
                        display: block;
                        margin-left: auto;
                        margin-right: auto
                    }
                }
            </style>
            <?php
        }
        ?>
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
                   <?php
                   if($auth) {
                       ?>
                            <p><a href="logout.php" class="mybutton buttonr">Logout</a></p>
                       <?php
                   }else {
                       ?>

                       <p><a href="login.php" class="mybutton buttonr">Login</a></p>

                       <p><a href="signup.php" class="mybutton buttonb">Sign Up</a></p>
                       <?php
                   }
                   ?>
            </aside>
        <div class="mymaincontent">
          <div class="split2">
            <div>
               <img id="immogg" class="objimg" src="img/placeholder.png" alt="PlaceHolder">
            </div>

              <?php
              if($auth) {
                  ?>
                  <div>
                      <p class="objname">Area Utente</p>
                      <p>Benvenuto <?php /* Avoid XSS */ echo htmlspecialchars($_SESSION["user"], ENT_QUOTES, 'UTF-8');?></p>
                      <form action="index.php" method="post" onsubmit="return checkOffer()">
                          <p><b><h3>THR_i: <?php /* Avoid XSS */ echo htmlspecialchars(myTHR(), ENT_QUOTES, 'UTF-8'); ?></h3></b></p>
                          <input type="text" id="thr" name="thr" value="" placeholder="0">
                          <input type="hidden" name="csrftok" value=<?php echo csrfProtect();?>>
                          <br>
                          <p id="offsup" class="errmess">Offerta Superata</p>
                          <p id="onsup" class="okmess">Sei il massimo offerente</p>
                          <p id="bidmin" class="errmess">THR_i non può essere inferiore al Bid attuale</p>
                          <p id="invalidbid" class="errmess">THR_i deve essere un numero valido</p>
                          <?php
                          if($bidfailed) {
                                echo "<script type=\"text/javascript\">$(\"#offsup\").show();</script>";
                          }else{
                                echo "<script type=\"text/javascript\">$(\"#onsup\").show();</script>";
                          }
                          ?>
                          <br>
                          <button class="mybutton buttong" type="submit" name="button">Set THR_i</button>
                      </form>
                  </div>
                  <?php
              }
              ?>
            <div>
                  <p id="nomeoggetto" class="objname">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                  <p id="descrizione"> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  <table class=tablebid>
                      <tr>
                          <td>
                              <p class="objbid">Actual Bid</p>
                          </td>
                          <td>
                              <p id="bid" class="objbid">999999999€</p>
                          </td>
                      </tr>
                      <tr>
                          <td>
                              <p class="objbid">Highest Bidder</p>
                          </td>
                          <td>
                              <p id="bidder" class="objbid">Lorem ipsum dolor@sit.amet</p>
                          </td>
                      </tr>
                  </table>
              </div>
          <div class=”clearer”> </div>
         </div>
            <div id="load"></div>
        </div>
        </div>
        <?php
        if($auth) {
            ?>
                <script type="text/javascript" src="js/privatebid.js"></script>
            <?php
            }
        ?>
        <script type="text/javascript" src="js/homebid.js"></script>
    </body>

</html>
