<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$server = $_ENV['SERVERDB'];
$user = $_ENV['USERDB'];
$pass = $_ENV['PASSWORDDB'];
$db = $_ENV['NAMEDB'];


echo "Servidor: $server\n";
echo "Usuario: $user\n";
echo "Contraseña: $pass\n";
echo "Base de Datos: $db\n";

$conexion = new mysqli($server, $user, $pass, $db);

// if($conexion->connect_errno){
//     die("Conexion Fallida" . $conexion->connect_errno);

// }else{
//     echo "conectado";
// }


?>