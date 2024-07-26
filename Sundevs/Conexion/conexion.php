<?php 

include("configuracion.php");

$conexion= new mysqli($Server,$User, $Password,$Bd);


if ($conexion->connect_error){
    die("fallo la conexion".$conexion->connect_error);
}else{
   //echo "Conectado";
}


?>