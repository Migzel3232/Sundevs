<?php
$Server = "localhost";
$User = "root";
$Password = "";
$Bd="sundevs";

$conn = mysqli_connect($Server, $User, $Password,$Bd);

try{
    $conection=new PDO('mysql:host=localhost;dbname='.$Bd,$User,$Password);//conexion con base de datos
    // echo "Bienvenido";
}catch(PDOException $fail){
    echo "Error de conexion".$fail->getMesage();//mesaje de error si llega haber uno
}

?>