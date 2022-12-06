<?php
    //Inicializadores
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'hospital';

    //Conexión
    try {
        global $conn;
        $con = new PDO("mysql:host=$server;dbname=$database;",$username,$password);
    } catch (PDOExeption $e) {
        die("Hubo un error al intentar conectar con el servidor: " . $e);
    }
?>