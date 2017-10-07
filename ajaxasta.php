<?php
/**
 * Created by IntelliJ IDEA.
 * User: Raffaele
 * Date: 09/06/2017
 * Time: 16:59
 */
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
{
    die('Restricted access');
}
include("database.php");
header("Content-type:application/json");
$result=mysqli_query($conn,"SELECT * FROM `asta` WHERE `id`=1");
if(mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_row($result);
    $output=array(
        'id'=>$row[0],
        'objname'=>$row[1],
        'objdescr'=>$row[2],
        'objbid'=>$row[3],
        'objimg'=>$row[4],
        'email'=>$row[5]
    );
    echo json_encode($output,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

}else {
    $output=array(
        'id'=>null,
        'objname'=>null,
        'objdescr'=>null,
        'objbid'=>null,
        'objimg'=>null,
        'email'=>null
    );
    echo json_encode($output);
}