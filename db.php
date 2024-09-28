<?php

// Define las credenciales de la base de datos
$nombre_de_host = "localhost";
$nombre_de_usuario = "root";
$contraseña = "";
$nombre_de_base_de_datos = "edu_platform";

// Crea una conexión a la base de datos

try {
    //$con= new mysqli('localhost','root','','proyect')or die("Could not connect to mysql".mysqli_error($con));
    $conn = mysqli_connect($nombre_de_host, $nombre_de_usuario, $contraseña, $nombre_de_base_de_datos);
    $conne = new PDO("mysql:host=$nombre_de_host;dbname=$nombre_de_base_de_datos;", $nombre_de_usuario, $contraseña);
    $conexion = new mysqli($nombre_de_host, $nombre_de_usuario, $contraseña, $nombre_de_base_de_datos);
  } catch (PDOException $e) {
    if ($conexion->connect_error){
      die("La conexión ha fallado " . $conexion->connect_error);
      die('Connection Failed: ' . $e->getMessage());
      }
  }


?>