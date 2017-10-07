<?php
session_start();

/**
 * Created by IntelliJ IDEA.
 * User: Raffaele
 * Date: 08/06/2017
 * Time: 23:55
 */
function myDestroySession() {
    $_SESSION=array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time()-3600*24,
            $params["path"],$params["domain"],
            $params["secure"], $params["httponly"]);
    }
    session_destroy(); // destroy session
}
function csrfProtect($token = null){
        if($token===null){
            /* Generate csrf Token for first time or reuse old */
            if(empty($_SESSION["csrftok"])) {
                $token = base64_encode(sha1(uniqid(rand(), TRUE)));
                $_SESSION["csrftok"] = $token;
                return $token;
            }else{
                return $_SESSION["csrftok"];
            }
        }else{
            if($_SESSION["csrftok"]===$token){
                return true;
            }else{
                return false;
            }
        }
}
function userLogged(){
        if(checkSessionExpire()){
            return false;
        }
        return isset($_SESSION["auth"])&&$_SESSION["auth"]==true;
}
function checkSessionExpire(){
    if (isset($_SESSION['last-timestamp']) && (time() - $_SESSION['last-timestamp'] > 120)) {
        myDestroySession();
        return true;
    }
    $_SESSION['last-timestamp']=time();
    return false;
}
function userLogin($user,$pass){
        if(!empty($user)&&!empty($pass)) {
            include("database.php");
            $user = mysqli_real_escape_string($conn, $user);
            $pass = mysqli_real_escape_string($conn, $pass);
            $pass = sha1($pass);
            $result = mysqli_query($conn, 'SELECT NULL FROM `user` WHERE `username`="' . $user . '" AND `password`="' . $pass . '";');
            if (mysqli_num_rows($result) > 0) {
                //print_r("ciao");
                $_SESSION["auth"] = true;
                $_SESSION["user"] = $user;
                return true;
            } else {
                return false;
            }
        }
}
function checkHttps(){
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}
function amIBest(){
    include("database.php");
    $result=mysqli_query($conn,"SELECT `maxbidder`  FROM `asta` WHERE `id`=1;");
    return(mysqli_fetch_row($result)[0]==$_SESSION["user"]);
}
function myTHR(){
    include("database.php");
    $user=mysqli_escape_string($conn,$_SESSION["user"]);
    $result=mysqli_query($conn,"SELECT `thr_i`  FROM `thr` WHERE `id_asta`=1 AND `id_user`=\"".$user."\";");
    if(mysqli_num_rows($result)>0){
        return mysqli_fetch_row($result)[0];
    }
    return 0;
}
function checkTHR($thr_i){
    include("database.php");
    mysqli_begin_transaction($conn);
    $result=mysqli_query($conn,"SELECT `objbid` FROM `asta` WHERE `id`=1;");
    if(mysqli_num_rows($result)>0){
        $bid=mysqli_fetch_row($result)[0];
        if($thr_i>$bid){
            $user=mysqli_escape_string($conn,$_SESSION["user"]);
            $thr_im=mysqli_escape_string($conn,$thr_i);
            $result=mysqli_query($conn,"INSERT INTO `thr`(`id_asta`, `id_user`, `thr_i`) VALUES (1,\"".$user."\",".$thr_im.") ON DUPLICATE KEY UPDATE `thr_i`=".$thr_im.";");
            if(!$result){
                mysqli_rollback($conn);
                die("Errore");
            }
            $result=mysqli_query($conn,"SELECT MAX(`thr_i`)  FROM `thr` WHERE `id_asta`=1;");
            $thr_i_absolute_max=mysqli_fetch_row($result)[0];
            $result=mysqli_query($conn,"SELECT `id_user` FROM `thr` WHERE `id_asta`=1 AND `thr_i`=\"".$thr_i_absolute_max."\" ORDER BY `thr`.`timestamp` ASC LIMIT 1;");
            $user_absolute_max=mysqli_fetch_row($result)[0];
            $result=mysqli_query($conn,"SELECT MAX(`thr_i`)  FROM `thr` WHERE `id_asta`=1 AND `id_user`!=\"".$user_absolute_max."\";");
            $max_thr_i=mysqli_fetch_row($result)[0];
            if(is_null($max_thr_i)){
                $result = mysqli_query($conn, "UPDATE `asta` SET `maxbidder`=\"".$user_absolute_max."\" WHERE `id`=1;");
                mysqli_commit($conn); //sono solo io il massimo
                return true;
            }
            if($thr_i_absolute_max==$max_thr_i){
                if($user_absolute_max===$user){
                    $result = mysqli_query($conn, "UPDATE `asta` SET `maxbidder`=\"".$user_absolute_max."\" WHERE `id`=1;");
                    mysqli_commit($conn); //o sono io
                    return true;
                }else{
                    $result = mysqli_query($conn, "UPDATE `asta` SET `maxbidder`=\"".$user_absolute_max."\",`objbid`=\"" .$thr_i_absolute_max. "\" WHERE `id`=1;");
                    mysqli_commit($conn); //o è un altro con la mia stessa offerta
                    return false;
                }
            }else{
                $result=mysqli_query($conn,"SELECT `maxbidder` FROM `asta` WHERE `id`=1;");
                if(mysqli_fetch_row($result)[0]===$user && $user_absolute_max===$user){
                    //i'm the best and i have incremented only the THR so do nothing
                    mysqli_commit($conn);
                    return true;
                }
                $newbid=$max_thr_i+0.01;
                $result = mysqli_query($conn, "UPDATE `asta` SET `maxbidder`=\"".$user_absolute_max."\",`objbid`=\"" .$newbid. "\" WHERE `id`=1;");
                mysqli_commit($conn);
                if($user_absolute_max===$user) {
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            mysqli_rollback($conn);
        }
    }else{
        mysqli_rollback($conn);
    }
}