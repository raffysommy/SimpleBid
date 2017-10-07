<?php
/**
 * Created by IntelliJ IDEA.
 * User: Raffaele
 * Date: 08/06/2017
 * Time: 19:33
 */
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
{
    die('Restricted access');
}
header('Content-Type: application/json');
$response=array(
    "ispresent"=>false
);
if(!empty($_GET["user"])){
    include("database.php");
    $user=mysqli_real_escape_string($conn,$_GET["user"]);
    $result=mysqli_query($conn,"SELECT NULL FROM `user` WHERE `username`=\"".$user."\";"); //non ho necessita di scaricarmi i dati
    if(mysqli_num_rows($result) > 0)
    {
        $response["ispresent"]=true;
    }
}
echo json_encode($response);