<?php
/**
 * Created by IntelliJ IDEA.
 * User: Raffaele
 * Date: 08/06/2017
 * Time: 17:27
 */
$db_server="localhost";
$db_user="root";
$db_password="";
$db_name="progetto";
$conn=@mysqli_connect($db_server,$db_user,$db_password,$db_name);
if(mysqli_connect_errno()){
    die("<h1>Errore nella Connessione al database. Riprova più tardi</h1>");
    //die("Failed to connect to MySQL: " . mysqli_connect_error());
}